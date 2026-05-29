<?php

namespace App\Http\Controllers;

use App\Models\Service;
use App\Models\ServiceInquiry;
use App\Models\ServiceType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class ServiceInquiryController extends Controller
{
    public function store(Request $request)
    {
        $base = $request->validate([
            'service_id' => ['required', 'integer', 'exists:services,id'],
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255'],
        ]);

        $service = Service::query()->where('is_active', true)->findOrFail($base['service_id']);

        $enabled = array_values(array_unique(array_filter($service->inquiry_fields ?? ['phone', 'company', 'budget', 'message'])));
        $rules = [];
        if (in_array('phone', $enabled, true)) $rules['phone'] = ['nullable', 'string', 'max:50'];
        if (in_array('company', $enabled, true)) $rules['company'] = ['nullable', 'string', 'max:255'];
        if (in_array('budget', $enabled, true)) $rules['budget'] = ['nullable', 'string', 'max:255'];
        if (in_array('message', $enabled, true)) $rules['message'] = ['nullable', 'string', 'max:2000'];

        $data = $rules ? $request->validate($rules) : [];

        $meta = [];
        $serviceTypeKey = strtolower(trim((string) ($service->service_type ?? '')));
        if ($serviceTypeKey !== '') {
            $meta['service_type'] = $serviceTypeKey;
        }

        $schema = null;
        if ($serviceTypeKey !== '') {
            $type = ServiceType::query()
                ->where('key', $serviceTypeKey)
                ->where('is_active', true)
                ->first();
            if (is_array($type?->schema ?? null)) {
                $schema = $type->schema;
            }
        }

        $metaFields = [];
        $rules = [];

        if (is_array($schema) && count($schema)) {
            foreach ($schema as $f) {
                if (!is_array($f)) continue;
                $kRaw = trim((string) ($f['key'] ?? ''));
                if ($kRaw === '') continue;
                $k = preg_replace('/[^a-zA-Z0-9_]/', '_', $kRaw);
                $k = trim((string) $k, '_');
                if ($k === '') continue;

                $t = strtolower(trim((string) ($f['type'] ?? 'text')));
                $req = !empty($f['required']);
                $opt = $this->extractOptionValues($f['options'] ?? null);

                if ($t === 'image') {
                    $rules['meta_files.' . $k] = [$req ? 'required' : 'nullable', 'image', 'max:4096'];
                } elseif (in_array($t, ['multi_select', 'priced_multi_select'], true)) {
                    $rules['meta.' . $k] = [$req ? 'required' : 'nullable', 'array', 'max:50'];
                    if (count($opt)) {
                        $rules['meta.' . $k . '.*'] = ['string', 'in:' . implode(',', $opt)];
                    } else {
                        $rules['meta.' . $k . '.*'] = ['string', 'max:255'];
                    }
                } elseif ($t === 'select') {
                    $r = [$req ? 'required' : 'nullable', 'string', 'max:255'];
                    if (count($opt)) {
                        $r[] = 'in:' . implode(',', $opt);
                    }
                    $rules['meta.' . $k] = $r;
                } elseif ($t === 'number') {
                    $rules['meta.' . $k] = [$req ? 'required' : 'nullable', 'numeric'];
                } elseif ($t === 'checkbox') {
                    $rules['meta.' . $k] = ['nullable'];
                } else {
                    $rules['meta.' . $k] = [$req ? 'required' : 'nullable', 'string', 'max:5000'];
                }
            }
        } else {
            $rules['meta'] = ['nullable', 'array', 'max:50'];
            $rules['meta.*'] = ['nullable', 'string', 'max:2000'];
        }

        if (count($rules)) {
            $request->validate($rules);
        }

        if (is_array($schema) && count($schema)) {
            foreach ($schema as $f) {
                if (!is_array($f)) continue;
                $kRaw = trim((string) ($f['key'] ?? ''));
                if ($kRaw === '') continue;
                $k = preg_replace('/[^a-zA-Z0-9_]/', '_', $kRaw);
                $k = trim((string) $k, '_');
                if ($k === '') continue;

                $t = strtolower(trim((string) ($f['type'] ?? 'text')));

                if ($t === 'image') {
                    if ($request->hasFile('meta_files.' . $k)) {
                        $metaFields[$k] = $request->file('meta_files.' . $k)->store('service_inquiries/' . $serviceTypeKey, 'public');
                    }
                    continue;
                }

                if ($t === 'checkbox') {
                    $metaFields[$k] = $request->boolean('meta.' . $k);
                    continue;
                }

                if (in_array($t, ['multi_select', 'priced_multi_select'], true)) {
                    $arr = (array) $request->input('meta.' . $k, []);
                    $arr = array_values(array_unique(array_filter(array_map(function ($v) {
                        return trim((string) $v);
                    }, $arr), function ($v) {
                        return $v !== '';
                    })));
                    $metaFields[$k] = $arr;
                    continue;
                }

                $val = $request->input('meta.' . $k);
                if (is_array($val)) continue;
                if (is_object($val)) continue;
                $val = trim((string) $val);
                if ($val !== '') {
                    $metaFields[$k] = $val;
                }
            }
        } else {
            $rawMeta = $request->input('meta');
            if (is_array($rawMeta) && count($rawMeta)) {
                $filtered = [];
                foreach ($rawMeta as $k => $v) {
                    if (!is_string($k) || $k === '') continue;
                    if (is_array($v)) continue;
                    if (is_object($v)) continue;
                    if ($v === null) continue;
                    $val = trim((string) $v);
                    if ($val === '') continue;
                    $filtered[$k] = mb_strlen($val) > 2000 ? mb_substr($val, 0, 2000) : $val;
                }
                if (count($filtered)) {
                    $metaFields = $filtered;
                }
            }
        }

        if (count($metaFields)) {
            $meta['fields'] = $metaFields;
        }

        $orderCode = $this->generateOrderCode();
        $accessKey = Str::random(32);

        $amount = null;
        if ($service->is_free) {
            $amount = 0;
        } else {
            $computed = $this->computePricedAmountFromSchema($schema, $metaFields);
            if (!is_null($computed)) {
                $amount = $computed;
                $meta['pricing'] = [
                    'amount' => $amount,
                    'currency' => 'NGN',
                ];
            } elseif (!is_null($service->price)) {
                $amount = (float) $service->price;
            }
        }

        $paymentType = $service->payment_type ?: 'one_time';

        $inquiry = ServiceInquiry::query()->create([
            'service_id' => $service->id,
            'order_code' => $orderCode,
            'access_key' => $accessKey,
            'grace_trial_enabled' => (bool)($service->grace_trial_enabled ?? true),
            'name' => $base['name'],
            'email' => $base['email'],
            'phone' => $data['phone'] ?? null,
            'company' => $data['company'] ?? null,
            'budget' => $data['budget'] ?? null,
            'message' => $data['message'] ?? null,
            'meta' => count($meta) ? $meta : null,
            'payment_type' => $paymentType,
            'amount' => $amount,
            'currency' => 'NGN',
            'payment_status' => $service->is_free ? 'free' : ($amount === null ? 'quote_required' : 'pending'),
            'status' => $service->is_free ? 'active' : 'new',
        ]);

        if ($service->is_free) {
            $inquiry->paid_at = now();
            $inquiry->payment_status = 'free';
            $inquiry->status = 'active';

            if ($paymentType === 'monthly') {
                $inquiry->next_renewal_at = now()->addMonth();
            } elseif ($paymentType === 'yearly') {
                $inquiry->next_renewal_at = now()->addYear();
            }

            $inquiry->save();

            $this->sendCredentialsEmail($inquiry, true);

            return redirect()->route('service-portal.dashboard')->with('service_portal_auto', [
                'order_code' => $inquiry->order_code,
                'access_key' => $inquiry->access_key,
                'email' => $inquiry->email,
            ]);
        }

        $this->sendCredentialsEmail($inquiry, false);

        if ($amount === null) {
            return redirect()->route('services.checkout', $inquiry->order_code)
                ->with('status', 'Request received. We will contact you with pricing and payment steps.');
        }

        return redirect()->route('services.checkout', $inquiry->order_code);
    }

    private function generateOrderCode(): string
    {
        do {
            $code = 'SVC-' . strtoupper(Str::random(8));
        } while (ServiceInquiry::query()->where('order_code', $code)->exists());

        return $code;
    }

    private function extractOptionValues($raw): array
    {
        if (!is_array($raw)) {
            return [];
        }

        $out = [];
        foreach ($raw as $opt) {
            if (is_string($opt)) {
                $v = trim($opt);
                if ($v !== '') $out[] = $v;
                continue;
            }
            if (is_array($opt)) {
                $v = (string) ($opt['value'] ?? $opt['label'] ?? '');
                $v = trim($v);
                if ($v !== '') $out[] = $v;
            }
        }

        $out = array_values(array_unique(array_filter($out, function ($v) {
            return is_string($v) && $v !== '';
        })));

        return array_slice($out, 0, 50);
    }

    private function computePricedAmountFromSchema($schema, array $metaFields): ?float
    {
        if (!is_array($schema) || !count($schema)) {
            return null;
        }

        $found = false;
        $total = 0.0;

        foreach ($schema as $f) {
            if (!is_array($f)) continue;
            $type = strtolower(trim((string) ($f['type'] ?? 'text')));
            if ($type !== 'priced_multi_select') continue;

            $kRaw = trim((string) ($f['key'] ?? ''));
            if ($kRaw === '') continue;
            $k = preg_replace('/[^a-zA-Z0-9_]/', '_', $kRaw);
            $k = trim((string) $k, '_');
            if ($k === '') continue;

            $selected = $metaFields[$k] ?? null;
            if (!is_array($selected)) continue;

            $options = is_array($f['options'] ?? null) ? $f['options'] : [];
            $prices = [];
            foreach ($options as $opt) {
                if (!is_array($opt)) continue;
                $val = (string) ($opt['value'] ?? $opt['label'] ?? '');
                $val = trim($val);
                if ($val === '') continue;
                $price = isset($opt['price']) ? (float) $opt['price'] : 0.0;
                if ($price < 0) $price = 0.0;
                $prices[$val] = $price;
            }

            $sum = 0.0;
            foreach ($selected as $s) {
                $s = trim((string) $s);
                if ($s === '') continue;
                if (array_key_exists($s, $prices)) {
                    $sum += (float) $prices[$s];
                }
            }

            $pricingMode = strtolower(trim((string) ($f['pricing_mode'] ?? 'none')));
            $fixedAmount = isset($f['fixed_amount']) ? (float) $f['fixed_amount'] : 0.0;
            if ($fixedAmount < 0) $fixedAmount = 0.0;
            $percent = isset($f['percent']) ? (float) $f['percent'] : 0.0;
            if ($percent < 0) $percent = 0.0;
            if ($percent > 100) $percent = 100;

            $extra = 0.0;
            if ($pricingMode === 'fixed' || $pricingMode === 'fixed_percent') {
                $extra += $fixedAmount;
            }
            if ($pricingMode === 'percent' || $pricingMode === 'fixed_percent') {
                $extra += ($sum * ($percent / 100));
            }

            $found = true;
            $total += ($sum + $extra);
        }

        if (!$found) {
            return null;
        }

        if ($total < 0) {
            $total = 0;
        }

        return round($total, 2);
    }

    private function sendCredentialsEmail(ServiceInquiry $inquiry, bool $isFree): void
    {
        try {
            $portalUrl = route('service-portal.login');
            $checkoutUrl = route('services.checkout', $inquiry->order_code);
            $serviceTitle = $inquiry->service?->title ?: optional($inquiry->service)->title;
            $serviceTitle = $serviceTitle ?: 'Service';
            $subject = ($isFree ? 'Service Activated: ' : 'Service Request Received: ') . $serviceTitle;

            $html = '<p>Dear '.e($inquiry->name).',</p>'
                .'<p>'.($isFree ? 'Your service is active.' : 'We have received your service request.').'</p>'
                .'<p><strong>Service:</strong> '.e($serviceTitle).'<br>'
                .'<strong>Service ID:</strong> '.e($inquiry->order_code).'<br>'
                .'<strong>Access Key:</strong> '.e($inquiry->access_key).'</p>'
                .'<p><a href="'.e($portalUrl).'">Open Service Portal</a><br>'
                .'<a href="'.e($checkoutUrl).'">Open Checkout</a></p>';

            if ($isFree) {
                $html .= '<p><strong>Payment:</strong> Free</p>';
            } elseif ($inquiry->payment_status === 'quote_required') {
                $html .= '<p><strong>Payment:</strong> Quote required. We will contact you with pricing.</p>';
            } else {
                $html .= '<p><strong>Payment:</strong> Pending. Complete payment from the checkout link.</p>';
            }

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
        } catch (\Throwable $e) {
        }
    }
}
