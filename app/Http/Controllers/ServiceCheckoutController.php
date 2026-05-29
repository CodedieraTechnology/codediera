<?php

namespace App\Http\Controllers;

use App\Models\ServiceInquiry;
use App\Models\ServiceInquiryPayment;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Throwable;

class ServiceCheckoutController extends Controller
{
    private const TRIAL_DAYS = 3;

    public function show(string $orderCode)
    {
        $inquiry = ServiceInquiry::query()
            ->with(['service', 'payments'])
            ->where('order_code', $orderCode)
            ->firstOrFail();

        return view('pages.service_checkout', compact('inquiry'));
    }

    public function startTrial(Request $request, string $orderCode)
    {
        $inquiry = ServiceInquiry::query()
            ->with('service')
            ->where('order_code', $orderCode)
            ->firstOrFail();

        if (!in_array($inquiry->payment_status, [null, 'pending', 'trialing'], true)) {
            return redirect()->route('services.checkout', $inquiry->order_code);
        }

        $servicePaymentType = $inquiry->service?->payment_type ?: 'one_time';
        if (!in_array($servicePaymentType, ['monthly', 'yearly', 'custom'], true)) {
            return redirect()->route('services.checkout', $inquiry->order_code);
        }

        $data = $request->validate([
            'payment_type' => ['nullable', 'string', 'in:monthly,yearly'],
        ]);

        $chosen = $data['payment_type'] ?? null;
        if (in_array($servicePaymentType, ['monthly', 'yearly'], true)) {
            $chosen = $servicePaymentType;
        }
        if (!in_array($chosen, ['monthly', 'yearly'], true)) {
            $chosen = 'monthly';
        }

        $inquiryAmount = $this->computePlanAmountNaira($inquiry, $chosen);
        if (!is_null($inquiryAmount)) {
            $inquiry->amount = $inquiryAmount;
        }

        $reference = 'TRIAL-' . strtoupper(Str::random(10));

        $inquiry->payment_status = 'trialing';
        $inquiry->paid_at = now();
        $inquiry->status = 'active';
        $inquiry->payment_type = $chosen;
        $inquiry->access_key = $inquiry->access_key ?: Str::random(32);
        $inquiry->next_renewal_at = now()->addDays($this->trialDays());
        $inquiry->save();

        ServiceInquiryPayment::query()->create([
            'service_inquiry_id' => $inquiry->id,
            'amount' => 0,
            'currency' => $inquiry->currency ?: 'NGN',
            'status' => 'trialing',
            'reference' => $reference,
            'paid_at' => $inquiry->paid_at,
        ]);

        return redirect()->route('service-portal.dashboard')->with('service_portal_auto', [
            'order_code' => $inquiry->order_code,
            'access_key' => $inquiry->access_key,
            'email' => $inquiry->email,
        ]);
    }

    public function pay(Request $request, string $orderCode)
    {
        $inquiry = ServiceInquiry::query()
            ->with('service')
            ->where('order_code', $orderCode)
            ->firstOrFail();

        $data = $request->validate([
            'payment_type' => ['nullable', 'string', 'in:monthly,yearly,one_time'],
        ]);

        if ($inquiry->payment_status === 'quote_required') {
            return redirect()->route('services.checkout', $inquiry->order_code);
        }

        $servicePlan = $inquiry->service?->payment_type ?: 'one_time';
        $chosen = $this->resolveChosenPaymentType($servicePlan, $data['payment_type'] ?? null, $inquiry->payment_type);

        if (in_array($chosen, ['monthly', 'yearly'], true)) {
            if (!$this->paystackEnabled()) {
                return redirect()->route('services.checkout', $inquiry->order_code)->withErrors([
                    'payment' => 'Paystack is not configured yet. Please contact support.',
                ]);
            }

            $amountNaira = $this->computePlanAmountNaira($inquiry, $chosen);
            if (is_null($amountNaira)) {
                return redirect()->route('services.checkout', $inquiry->order_code);
            }

            $inquiry->payment_type = $chosen;
            $inquiry->amount = $amountNaira;
            $inquiry->save();

            try {
                $redirectUrl = $this->initializePaystackSubscriptionSetup($inquiry, $chosen);
                return redirect()->away($redirectUrl);
            } catch (Throwable $e) {
                return redirect()->route('services.checkout', $inquiry->order_code)->withErrors([
                    'payment' => 'Unable to start Paystack payment. Please try again.',
                ]);
            }
        }

        $amountNaira = (float) ($inquiry->amount ?? 0);
        if ($amountNaira <= 0) {
            $this->markInquiryActive($inquiry, $chosen, 'free', null, null);
            ServiceInquiryPayment::query()->create([
                'service_inquiry_id' => $inquiry->id,
                'amount' => 0,
                'currency' => $inquiry->currency ?: 'NGN',
                'status' => 'free',
                'reference' => 'FREE-' . strtoupper(Str::random(10)),
                'paid_at' => $inquiry->paid_at,
            ]);

            $this->sendActivationEmail($inquiry, false);

            return redirect()->route('service-portal.dashboard')->with('service_portal_auto', [
                'order_code' => $inquiry->order_code,
                'access_key' => $inquiry->access_key,
                'email' => $inquiry->email,
            ]);
        }

        if (!$this->paystackEnabled()) {
            return redirect()->route('services.checkout', $inquiry->order_code)->withErrors([
                'payment' => 'Paystack is not configured yet. Please contact support.',
            ]);
        }

        try {
            $redirectUrl = $this->initializePaystackOneTimePayment($inquiry);
            return redirect()->away($redirectUrl);
        } catch (Throwable $e) {
            return redirect()->route('services.checkout', $inquiry->order_code)->withErrors([
                'payment' => 'Unable to start Paystack payment. Please try again.',
            ]);
        }
    }

    public function paystackCallback(Request $request, string $orderCode)
    {
        $reference = trim((string) $request->query('reference', ''));
        if ($reference === '') {
            return redirect()->route('services.checkout', $orderCode)->withErrors([
                'payment' => 'Missing payment reference.',
            ]);
        }

        $inquiry = ServiceInquiry::query()
            ->with('service')
            ->where('order_code', $orderCode)
            ->firstOrFail();

        if (!$this->paystackEnabled()) {
            return redirect()->route('services.checkout', $inquiry->order_code)->withErrors([
                'payment' => 'Paystack is not configured yet. Please contact support.',
            ]);
        }

        if ($inquiry->paystack_setup_reference && $inquiry->paystack_setup_reference !== $reference) {
            return redirect()->route('services.checkout', $inquiry->order_code)->withErrors([
                'payment' => 'Payment reference mismatch.',
            ]);
        }

        try {
            $tx = $this->paystackGet('/transaction/verify/' . urlencode($reference));
        } catch (Throwable $e) {
            return redirect()->route('services.checkout', $inquiry->order_code)->withErrors([
                'payment' => 'Unable to verify payment. Please try again.',
            ]);
        }

        if (!is_array($tx) || (($tx['status'] ?? null) !== 'success')) {
            return redirect()->route('services.checkout', $inquiry->order_code)->withErrors([
                'payment' => 'Payment not successful.',
            ]);
        }

        $metadata = is_array($tx['metadata'] ?? null) ? $tx['metadata'] : [];
        $mode = (string) ($metadata['mode'] ?? '');

        if ($mode === 'subscription') {
            $paymentType = (string) ($metadata['payment_type'] ?? $inquiry->payment_type ?? 'monthly');
            if (!in_array($paymentType, ['monthly', 'yearly'], true)) {
                $paymentType = 'monthly';
            }

            $amountNaira = $this->computePlanAmountNaira($inquiry, $paymentType);
            if (is_null($amountNaira)) {
                return redirect()->route('services.checkout', $inquiry->order_code);
            }

            $amountKobo = $this->nairaToKobo($amountNaira);
            $planCode = (string) ($metadata['plan_code'] ?? '');
            if ($planCode === '') {
                try {
                    $planCode = $this->ensurePaystackPlan($inquiry->service, $paymentType, $amountKobo);
                } catch (Throwable $e) {
                    return redirect()->route('services.checkout', $inquiry->order_code)->withErrors([
                        'payment' => 'Unable to create billing plan. Please try again.',
                    ]);
                }
            }

            $customerCode = (string) data_get($tx, 'customer.customer_code', '');
            $authorizationCode = (string) data_get($tx, 'authorization.authorization_code', '');

            if ($customerCode === '' || $authorizationCode === '') {
                return redirect()->route('services.checkout', $inquiry->order_code)->withErrors([
                    'payment' => 'Unable to save card authorization. Please try again.',
                ]);
            }

            $startDate = $this->resolveSubscriptionStartDate($inquiry, $metadata);

            $inquiry->paystack_customer_code = $customerCode;
            $inquiry->paystack_authorization_code = $authorizationCode;
            $inquiry->payment_type = $paymentType;
            $inquiry->amount = $amountNaira;
            $inquiry->payment_status = 'trialing';
            $inquiry->paid_at = $inquiry->paid_at ?: now();
            $inquiry->status = 'active';
            $inquiry->access_key = $inquiry->access_key ?: Str::random(32);
            $inquiry->next_renewal_at = $startDate;
            $inquiry->grace_trial_enabled = (bool) ($inquiry->grace_trial_enabled ?? ($inquiry->service?->grace_trial_enabled ?? true));
            $inquiry->save();

            $authAmountKobo = $this->authAmountKobo();
            $authAmountNaira = $authAmountKobo > 0 ? round($authAmountKobo / 100, 2) : 0;
            if (!ServiceInquiryPayment::query()->where('reference', $reference)->exists()) {
                ServiceInquiryPayment::query()->create([
                    'service_inquiry_id' => $inquiry->id,
                    'amount' => $authAmountNaira,
                    'currency' => $inquiry->currency ?: 'NGN',
                    'status' => 'authorized',
                    'reference' => $reference,
                    'paid_at' => now(),
                ]);
            }

            try {
                $sub = $this->paystackPost('/subscription', [
                    'customer' => $customerCode,
                    'plan' => $planCode,
                    'authorization' => $authorizationCode,
                    'start_date' => $startDate->toIso8601String(),
                ]);
            } catch (Throwable $e) {
                return redirect()->route('services.checkout', $inquiry->order_code)->withErrors([
                    'payment' => 'Card saved, but subscription setup failed. Please try again.',
                ]);
            }

            $subscriptionCode = (string) ($sub['subscription_code'] ?? '');
            $emailToken = (string) ($sub['email_token'] ?? '');

            if ($subscriptionCode !== '') {
                $inquiry->paystack_subscription_code = $subscriptionCode;
            }
            if ($emailToken !== '') {
                $inquiry->paystack_email_token = $emailToken;
            }
            $inquiry->save();

            return redirect()->route('services.checkout', $inquiry->order_code)->with('status', 'Subscription created. Your card will be debited automatically after the trial.');
        }

        $amountKobo = (int) ($tx['amount'] ?? 0);
        $amountNaira = round($amountKobo / 100, 2);

        $isRenewal = in_array($inquiry->payment_status, ['paid', 'free'], true);
        $this->markInquiryActive($inquiry, $inquiry->payment_type ?: 'one_time', 'paid', Carbon::parse($tx['paid_at'] ?? now()), null);

        if (!ServiceInquiryPayment::query()->where('reference', $reference)->exists()) {
            ServiceInquiryPayment::query()->create([
                'service_inquiry_id' => $inquiry->id,
                'amount' => $amountNaira,
                'currency' => $inquiry->currency ?: 'NGN',
                'status' => 'paid',
                'reference' => $reference,
                'paid_at' => $inquiry->paid_at,
            ]);
        }

        $this->sendActivationEmail($inquiry, $isRenewal);

        return redirect()->route('service-portal.dashboard')->with('service_portal_auto', [
            'order_code' => $inquiry->order_code,
            'access_key' => $inquiry->access_key,
            'email' => $inquiry->email,
        ]);
    }

    public function paystackWebhook(Request $request)
    {
        if (!$this->paystackEnabled()) {
            return response()->json(['ok' => false], 200);
        }

        $payload = (string) $request->getContent();
        $signature = (string) $request->header('x-paystack-signature', '');
        $secret = $this->paystackSecret();
        if (!$secret || $signature === '' || hash_hmac('sha512', $payload, $secret) !== $signature) {
            return response()->json(['ok' => false], 401);
        }

        $event = json_decode($payload, true);
        if (!is_array($event)) {
            return response()->json(['ok' => false], 400);
        }

        $eventName = (string) ($event['event'] ?? '');
        $data = is_array($event['data'] ?? null) ? $event['data'] : [];

        $subscriptionCode = (string) data_get($data, 'subscription.subscription_code', data_get($data, 'subscription_code', ''));
        if ($subscriptionCode === '' && is_string($data['subscription'] ?? null)) {
            $subscriptionCode = (string) ($data['subscription'] ?? '');
        }

        $orderCode = (string) data_get($data, 'metadata.order_code', '');

        $inquiry = null;
        if ($subscriptionCode !== '') {
            $inquiry = ServiceInquiry::query()->where('paystack_subscription_code', $subscriptionCode)->first();
        }
        if (!$inquiry && $orderCode !== '') {
            $inquiry = ServiceInquiry::query()->where('order_code', $orderCode)->first();
        }

        if (!$inquiry) {
            return response()->json(['ok' => true], 200);
        }

        if ($eventName === 'charge.success') {
            $amountKobo = (int) ($data['amount'] ?? 0);
            $amountNaira = round($amountKobo / 100, 2);
            $reference = (string) ($data['reference'] ?? $data['id'] ?? '');
            if ($reference === '') {
                $reference = 'PSK-' . strtoupper(Str::random(10));
            }

            $paidAt = null;
            try {
                $paidAt = isset($data['paid_at']) ? Carbon::parse($data['paid_at']) : null;
            } catch (Throwable $e) {
                $paidAt = null;
            }
            $paidAt = $paidAt ?: now();

            $inquiry->paystack_customer_code = $inquiry->paystack_customer_code ?: (string) data_get($data, 'customer.customer_code', '');
            $inquiry->paystack_authorization_code = $inquiry->paystack_authorization_code ?: (string) data_get($data, 'authorization.authorization_code', '');
            $inquiry->payment_status = 'paid';
            $inquiry->paid_at = $paidAt;
            $inquiry->status = 'active';

            if ($inquiry->payment_type === 'monthly') {
                $inquiry->next_renewal_at = $paidAt->copy()->addMonth();
            } elseif ($inquiry->payment_type === 'yearly') {
                $inquiry->next_renewal_at = $paidAt->copy()->addYear();
            }

            $inquiry->save();

            if (!ServiceInquiryPayment::query()->where('reference', $reference)->exists()) {
                ServiceInquiryPayment::query()->create([
                    'service_inquiry_id' => $inquiry->id,
                    'amount' => $amountNaira,
                    'currency' => (string) ($data['currency'] ?? ($inquiry->currency ?: 'NGN')),
                    'status' => 'paid',
                    'reference' => $reference,
                    'paid_at' => $paidAt,
                ]);
            }

            return response()->json(['ok' => true], 200);
        }

        if (in_array($eventName, ['invoice.payment_failed', 'charge.failed'], true)) {
            $reference = (string) ($data['reference'] ?? $data['id'] ?? '');
            if ($reference === '') {
                $reference = 'PSK-FAIL-' . strtoupper(Str::random(10));
            }

            if (!ServiceInquiryPayment::query()->where('reference', $reference)->exists()) {
                $amountKobo = (int) ($data['amount'] ?? 0);
                $amountNaira = round($amountKobo / 100, 2);
                ServiceInquiryPayment::query()->create([
                    'service_inquiry_id' => $inquiry->id,
                    'amount' => $amountNaira,
                    'currency' => (string) ($data['currency'] ?? ($inquiry->currency ?: 'NGN')),
                    'status' => 'failed',
                    'reference' => $reference,
                    'paid_at' => now(),
                ]);
            }

            return response()->json(['ok' => true], 200);
        }

        if (in_array($eventName, ['subscription.disable', 'subscription.not_renew'], true)) {
            $inquiry->status = 'expired';
            $inquiry->save();
            return response()->json(['ok' => true], 200);
        }

        return response()->json(['ok' => true], 200);
    }

    private function paystackEnabled(): bool
    {
        if (!(bool) (config('services.paystack.enabled') ?? false)) {
            return false;
        }

        $secret = $this->paystackSecret();
        return is_string($secret) && $secret !== '';
    }

    private function paystackSecret(): ?string
    {
        $secret = config('services.paystack.secret_key');
        if (!is_string($secret) || trim($secret) === '') {
            return null;
        }
        return trim($secret);
    }

    private function trialDays(): int
    {
        $trialDays = (int) (config('services.paystack.trial_days') ?? self::TRIAL_DAYS);
        return max(0, $trialDays);
    }

    private function authAmountKobo(): int
    {
        $amount = (int) (config('services.paystack.auth_amount_kobo') ?? 0);
        return max(0, $amount);
    }

    private function nairaToKobo(float $naira): int
    {
        return (int) round($naira * 100);
    }

    private function resolveChosenPaymentType(string $servicePlan, ?string $requested, ?string $existing): string
    {
        if (in_array($servicePlan, ['monthly', 'yearly'], true)) {
            return $servicePlan;
        }

        if ($servicePlan === 'custom') {
            $req = $requested ?: $existing;
            if (in_array($req, ['monthly', 'yearly'], true)) {
                return $req;
            }
            return 'monthly';
        }

        return 'one_time';
    }

    private function computePlanAmountNaira(ServiceInquiry $inquiry, string $paymentType): ?float
    {
        $base = null;
        if (!is_null($inquiry->service?->price)) {
            $base = (float) $inquiry->service->price;
        } elseif (!is_null($inquiry->amount)) {
            $base = (float) $inquiry->amount;
        }

        if (is_null($base)) {
            return null;
        }

        if ($paymentType === 'yearly') {
            return round($base * 12, 2);
        }

        return round($base, 2);
    }

    private function resolveSubscriptionStartDate(ServiceInquiry $inquiry, array $metadata): Carbon
    {
        $requested = (string) ($metadata['subscription_start_date'] ?? '');
        $start = null;

        if ($requested !== '') {
            try {
                $start = Carbon::parse($requested);
            } catch (Throwable $e) {
                $start = null;
            }
        }

        if (!$start) {
            if ($inquiry->payment_status === 'trialing' && $inquiry->next_renewal_at) {
                $start = $inquiry->next_renewal_at->copy();
            } else {
                $start = now()->addDays($this->trialDays());
            }
        }

        if ($start->isPast()) {
            $start = now();
        }

        return $start;
    }

    private function ensurePaystackPlan($service, string $paymentType, int $amountKobo): string
    {
        if (!$service) {
            throw new \RuntimeException('Missing service.');
        }

        $field = $paymentType === 'yearly' ? 'paystack_plan_code_yearly' : 'paystack_plan_code_monthly';
        $existing = (string) ($service->{$field} ?? '');
        if ($existing !== '') {
            return $existing;
        }

        $interval = $paymentType === 'yearly' ? 'annually' : 'monthly';
        $name = trim((string) $service->title) . ' #' . $service->id . ' ' . $interval;

        $plan = $this->paystackPost('/plan', [
            'name' => $name,
            'interval' => $interval,
            'amount' => $amountKobo,
            'currency' => 'NGN',
        ]);

        $planCode = (string) ($plan['plan_code'] ?? '');
        if ($planCode === '') {
            throw new \RuntimeException('Plan code missing.');
        }

        $service->{$field} = $planCode;
        $service->save();

        return $planCode;
    }

    private function initializePaystackSubscriptionSetup(ServiceInquiry $inquiry, string $paymentType): string
    {
        $amountNaira = $this->computePlanAmountNaira($inquiry, $paymentType);
        if (is_null($amountNaira)) {
            throw new \RuntimeException('Amount missing.');
        }

        $planAmountKobo = $this->nairaToKobo($amountNaira);
        $planCode = $this->ensurePaystackPlan($inquiry->service, $paymentType, $planAmountKobo);
        $startDate = $this->resolveSubscriptionStartDate($inquiry, []);

        $reference = 'PSK-SUB-' . strtoupper(Str::random(12));

        $init = $this->paystackPost('/transaction/initialize', [
            'email' => $inquiry->email,
            'amount' => $this->authAmountKobo(),
            'reference' => $reference,
            'callback_url' => route('services.checkout.paystack.callback', $inquiry->order_code),
            'metadata' => [
                'mode' => 'subscription',
                'order_code' => $inquiry->order_code,
                'service_inquiry_id' => $inquiry->id,
                'payment_type' => $paymentType,
                'plan_code' => $planCode,
                'subscription_start_date' => $startDate->toIso8601String(),
            ],
        ]);

        $url = (string) ($init['authorization_url'] ?? '');
        if ($url === '') {
            throw new \RuntimeException('Missing authorization url.');
        }

        $inquiry->paystack_setup_reference = $reference;
        $inquiry->save();

        return $url;
    }

    private function initializePaystackOneTimePayment(ServiceInquiry $inquiry): string
    {
        $amountNaira = (float) ($inquiry->amount ?? 0);
        $amountKobo = $this->nairaToKobo($amountNaira);
        if ($amountKobo <= 0) {
            throw new \RuntimeException('Invalid amount.');
        }

        $reference = 'PSK-ONE-' . strtoupper(Str::random(12));

        $init = $this->paystackPost('/transaction/initialize', [
            'email' => $inquiry->email,
            'amount' => $amountKobo,
            'reference' => $reference,
            'callback_url' => route('services.checkout.paystack.callback', $inquiry->order_code),
            'metadata' => [
                'mode' => 'one_time',
                'order_code' => $inquiry->order_code,
                'service_inquiry_id' => $inquiry->id,
            ],
        ]);

        $url = (string) ($init['authorization_url'] ?? '');
        if ($url === '') {
            throw new \RuntimeException('Missing authorization url.');
        }

        $inquiry->paystack_setup_reference = $reference;
        $inquiry->save();

        return $url;
    }

    private function paystackGet(string $path): array
    {
        return $this->paystackRequest('get', $path, null);
    }

    private function paystackPost(string $path, array $payload): array
    {
        return $this->paystackRequest('post', $path, $payload);
    }

    private function paystackRequest(string $method, string $path, ?array $payload): array
    {
        $secret = $this->paystackSecret();
        if (!$secret) {
            throw new \RuntimeException('Paystack not configured.');
        }

        try {
            $http = Http::withToken($secret)
                ->acceptJson()
                ->asJson()
                ->timeout(25);

            $url = 'https://api.paystack.co' . $path;

            $res = $payload === null ? $http->{$method}($url) : $http->{$method}($url, $payload);
        } catch (ConnectionException $e) {
            throw $e;
        }

        if (!$res->ok()) {
            throw new \RuntimeException('Paystack request failed.');
        }

        $json = $res->json();
        if (!is_array($json) || !($json['status'] ?? false)) {
            throw new \RuntimeException('Paystack returned an error.');
        }

        $data = $json['data'] ?? [];
        return is_array($data) ? $data : [];
    }

    private function markInquiryActive(ServiceInquiry $inquiry, string $paymentType, string $paymentStatus, ?Carbon $paidAt, ?Carbon $baseRenewal): void
    {
        $isRenewal = in_array($inquiry->payment_status, ['paid', 'free'], true);

        $inquiry->payment_type = $paymentType;
        $inquiry->payment_status = $paymentStatus;
        $inquiry->paid_at = $paidAt ?: now();
        $inquiry->status = 'active';
        $inquiry->access_key = Str::random(32);

        if ($paymentType === 'monthly') {
            $base = $baseRenewal ?: (($isRenewal && $inquiry->next_renewal_at && $inquiry->next_renewal_at->isFuture()) ? $inquiry->next_renewal_at : now());
            $inquiry->next_renewal_at = $base->copy()->addMonth();
        } elseif ($paymentType === 'yearly') {
            $base = $baseRenewal ?: (($isRenewal && $inquiry->next_renewal_at && $inquiry->next_renewal_at->isFuture()) ? $inquiry->next_renewal_at : now());
            $inquiry->next_renewal_at = $base->copy()->addYear();
        } else {
            $inquiry->next_renewal_at = null;
        }

        $inquiry->save();
    }

    private function sendActivationEmail(ServiceInquiry $inquiry, bool $isRenewal): void
    {
        try {
            $portalUrl = route('service-portal.login');
            $checkoutUrl = route('services.checkout', $inquiry->order_code);
            $serviceTitle = $inquiry->service?->title ?: 'Service';
            $subject = ($isRenewal ? 'Service Renewed: ' : 'Service Activated: ') . $serviceTitle;

            $html = '<p>Dear '.e($inquiry->name).',</p>'
                .'<p>Your service has been '.($isRenewal ? 'renewed' : 'activated').' successfully.</p>'
                .'<p><strong>Service:</strong> '.e($serviceTitle).'<br>'
                .'<strong>Service ID:</strong> '.e($inquiry->order_code).'<br>'
                .'<strong>Access Key:</strong> '.e($inquiry->access_key).'</p>'
                .'<p><a href="'.e($portalUrl).'">Open Service Portal</a><br>'
                .'<a href="'.e($checkoutUrl).'">Open Checkout</a></p>';

            if ($inquiry->next_renewal_at) {
                $html .= '<p><strong>Next renewal:</strong> '.e($inquiry->next_renewal_at->format('Y-m-d')).'</p>';
            }

            if ($inquiry->service?->download_url) {
                $html .= '<p><strong>Download / App link:</strong> <a href="'.e($inquiry->service->download_url).'">'.e($inquiry->service->download_url).'</a></p>';
            }

            if ($inquiry->service?->instructions) {
                $html .= '<hr><p><strong>Instructions</strong></p><div>'.$inquiry->service->instructions.'</div>';
            }

            $html .= '<p>Regards,<br>'.e(config('app.name')).'</p>';

            Mail::send([], [], function ($message) use ($inquiry, $subject, $html) {
                $message->to($inquiry->email)
                    ->subject($subject)
                    ->setBody($html, 'text/html');
            });
        } catch (Throwable $e) {
        }
    }
}
