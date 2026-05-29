<?php

namespace App\Http\Controllers;

use App\Models\ServiceInquiry;
use Illuminate\Http\Request;

class ServicePortalController extends Controller
{
    public function dashboard(Request $request)
    {
        $auto = session()->pull('service_portal_auto');
        if (is_array($auto)) {
            $this->attemptLogin($request, $auto);
        }

        $inquiryId = $request->session()->get('service_portal_inquiry_id');
        if (!$inquiryId) {
            return redirect()->route('service-portal.login');
        }

        $inquiry = ServiceInquiry::query()
            ->with(['service', 'payments'])
            ->findOrFail($inquiryId);

        $this->normalizeSubscriptionState($inquiry);

        if ($inquiry->status === 'expired') {
            $request->session()->forget('service_portal_inquiry_id');
            return redirect()->route('service-portal.login')->withErrors([
                'order_code' => 'Service expired. Renew to reactivate and receive a new access key.',
            ]);
        }

        return view('pages.service_portal_dashboard', compact('inquiry'));
    }

    public function showLogin()
    {
        return view('pages.service_portal_login');
    }

    public function login(Request $request)
    {
        $data = $request->validate([
            'order_code' => ['required', 'string', 'max:50'],
            'email' => ['required', 'email', 'max:255'],
            'access_key' => ['required', 'string', 'max:255'],
        ]);

        if (!$this->attemptLogin($request, $data)) {
            $inquiry = ServiceInquiry::query()
                ->where('order_code', $data['order_code'])
                ->where('email', $data['email'])
                ->first();

            if ($inquiry) {
                $this->normalizeSubscriptionState($inquiry);
                if ($inquiry->status === 'expired') {
                    return redirect()->back()->withErrors(['order_code' => 'Service expired. Renew to reactivate and receive a new access key.']);
                }
            }

            return redirect()->back()->withErrors(['order_code' => 'Invalid service credentials']);
        }

        return redirect()->route('service-portal.dashboard');
    }

    public function logout(Request $request)
    {
        $request->session()->forget('service_portal_inquiry_id');

        return redirect()->route('service-portal.login');
    }

    public function renew(Request $request)
    {
        $inquiryId = $request->session()->get('service_portal_inquiry_id');
        if (!$inquiryId) {
            return redirect()->route('service-portal.login');
        }

        $inquiry = ServiceInquiry::query()->findOrFail($inquiryId);

        return redirect()->route('services.checkout', $inquiry->order_code);
    }

    private function normalizeSubscriptionState(ServiceInquiry $inquiry): void
    {
        if (!$inquiry->isRenewable()) {
            return;
        }

        if (!in_array($inquiry->payment_status, ['paid', 'free', 'trialing'], true)) {
            return;
        }

        if (!$inquiry->next_renewal_at) {
            return;
        }

        if ($inquiry->isExpiredBeyondGrace()) {
            if ($inquiry->status !== 'expired') {
                $inquiry->status = 'expired';
                $inquiry->save();
            }
            return;
        }

        if ($inquiry->next_renewal_at->isPast()) {
            if ($inquiry->status !== 'trial') {
                $inquiry->status = 'trial';
                $inquiry->save();
            }
            return;
        }

        if ($inquiry->status !== 'active') {
            $inquiry->status = 'active';
            $inquiry->save();
        }
    }

    private function attemptLogin(Request $request, array $data): bool
    {
        $inquiry = ServiceInquiry::query()
            ->where('order_code', $data['order_code'])
            ->where('email', $data['email'])
            ->where('access_key', $data['access_key'])
            ->first();

        if (!$inquiry) {
            return false;
        }

        $this->normalizeSubscriptionState($inquiry);
        if ($inquiry->status === 'expired') {
            return false;
        }

        $request->session()->put('service_portal_inquiry_id', $inquiry->id);
        return true;
    }
}
