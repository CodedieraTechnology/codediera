<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ServiceType;
use Illuminate\Http\Request;

class ServiceTypeController extends Controller
{
    private const FIELD_TYPES = [
        'text',
        'textarea',
        'number',
        'select',
        'multi_select',
        'priced_multi_select',
        'checkbox',
        'image',
    ];

    public function index()
    {
        $types = ServiceType::query()
            ->orderBy('sort_order')
            ->orderBy('name')
            ->paginate(20);

        return view('admin.service_types.index', compact('types'));
    }

    public function create()
    {
        return view('admin.service_types.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'key' => ['required', 'string', 'max:100', 'unique:service_types,key'],
            'name' => ['required', 'string', 'max:255'],
            'fields' => ['nullable', 'array', 'max:50'],
            'fields.*.key' => ['nullable', 'string', 'max:100'],
            'fields.*.label' => ['nullable', 'string', 'max:255'],
            'fields.*.type' => ['nullable', 'string', 'in:' . implode(',', self::FIELD_TYPES)],
            'fields.*.required' => ['nullable'],
            'fields.*.options' => ['nullable', 'string', 'max:2000'],
            'fields.*.pricing_mode' => ['nullable', 'string', 'in:none,fixed,percent,fixed_percent'],
            'fields.*.fixed_amount' => ['nullable', 'string', 'max:50'],
            'fields.*.percent' => ['nullable', 'string', 'max:50'],
            'sort_order' => ['nullable', 'integer', 'min:0'],
            'is_active' => ['nullable'],
        ]);

        $schema = $this->buildSchemaFromFields($data['fields'] ?? []);

        ServiceType::query()->create([
            'key' => $data['key'],
            'name' => $data['name'],
            'schema' => $schema,
            'sort_order' => (int) ($data['sort_order'] ?? 0),
            'is_active' => $request->boolean('is_active', true),
        ]);

        return redirect()->route('admin.service-types.index')->with('status', 'Service type created');
    }

    public function edit(ServiceType $serviceType)
    {
        return view('admin.service_types.edit', compact('serviceType'));
    }

    public function update(Request $request, ServiceType $serviceType)
    {
        $data = $request->validate([
            'key' => ['required', 'string', 'max:100', 'unique:service_types,key,' . $serviceType->id],
            'name' => ['required', 'string', 'max:255'],
            'fields' => ['nullable', 'array', 'max:50'],
            'fields.*.key' => ['nullable', 'string', 'max:100'],
            'fields.*.label' => ['nullable', 'string', 'max:255'],
            'fields.*.type' => ['nullable', 'string', 'in:' . implode(',', self::FIELD_TYPES)],
            'fields.*.required' => ['nullable'],
            'fields.*.options' => ['nullable', 'string', 'max:2000'],
            'fields.*.pricing_mode' => ['nullable', 'string', 'in:none,fixed,percent,fixed_percent'],
            'fields.*.fixed_amount' => ['nullable', 'string', 'max:50'],
            'fields.*.percent' => ['nullable', 'string', 'max:50'],
            'sort_order' => ['nullable', 'integer', 'min:0'],
            'is_active' => ['nullable'],
        ]);

        $schema = $this->buildSchemaFromFields($data['fields'] ?? []);

        $serviceType->key = $data['key'];
        $serviceType->name = $data['name'];
        $serviceType->schema = $schema;
        $serviceType->sort_order = (int) ($data['sort_order'] ?? 0);
        $serviceType->is_active = $request->boolean('is_active', false);
        $serviceType->save();

        return redirect()->route('admin.service-types.index')->with('status', 'Service type updated');
    }

    public function destroy(ServiceType $serviceType)
    {
        $serviceType->delete();

        return redirect()->route('admin.service-types.index')->with('status', 'Service type deleted');
    }

    private function buildSchemaFromFields($fields): ?array
    {
        if (!is_array($fields) || count($fields) === 0) {
            return null;
        }

        $schema = [];
        $seen = [];

        foreach ($fields as $f) {
            if (!is_array($f)) continue;

            $keyRaw = trim((string) ($f['key'] ?? ''));
            if ($keyRaw === '') continue;
            $key = preg_replace('/[^a-zA-Z0-9_]/', '_', $keyRaw);
            $key = trim((string) $key, '_');
            if ($key === '') continue;
            if (isset($seen[$key])) continue;

            $type = strtolower(trim((string) ($f['type'] ?? 'text')));
            if (!in_array($type, self::FIELD_TYPES, true)) {
                $type = 'text';
            }

            $label = trim((string) ($f['label'] ?? ''));
            if ($label === '') $label = $key;

            $required = !empty($f['required']);

            $item = [
                'key' => $key,
                'label' => $label,
                'type' => $type,
                'required' => $required,
            ];

            if (in_array($type, ['select', 'multi_select'], true)) {
                $optsRaw = trim((string) ($f['options'] ?? ''));
                $opts = array_values(array_unique(array_filter(array_map(function ($v) {
                    return trim((string) $v);
                }, preg_split('/,/', $optsRaw) ?: []), function ($v) {
                    return $v !== '';
                })));
                $opts = array_slice($opts, 0, 50);
                $item['options'] = $opts;
            }

            if ($type === 'priced_multi_select') {
                $optsRaw = trim((string) ($f['options'] ?? ''));
                $rawParts = preg_split('/,/', $optsRaw) ?: [];
                $options = [];
                $seenOpt = [];

                foreach ($rawParts as $part) {
                    $part = trim((string) $part);
                    if ($part === '') continue;

                    $labelPart = $part;
                    $pricePart = null;

                    if (str_contains($part, '=')) {
                        [$labelPart, $pricePart] = array_pad(explode('=', $part, 2), 2, null);
                        $labelPart = trim((string) $labelPart);
                        $pricePart = trim((string) $pricePart);
                    }

                    if ($labelPart === '') continue;

                    $labelPart = preg_replace('/\s+/', ' ', $labelPart);
                    $labelPart = trim((string) $labelPart);
                    if ($labelPart === '') continue;
                    if (isset($seenOpt[$labelPart])) continue;

                    $price = 0;
                    if ($pricePart !== null && $pricePart !== '') {
                        $price = (float) preg_replace('/[^0-9.]/', '', $pricePart);
                    }
                    if ($price < 0) $price = 0;

                    $options[] = [
                        'value' => $labelPart,
                        'label' => $labelPart,
                        'price' => round($price, 2),
                    ];
                    $seenOpt[$labelPart] = true;

                    if (count($options) >= 50) {
                        break;
                    }
                }

                $pricingMode = strtolower(trim((string) ($f['pricing_mode'] ?? 'none')));
                if (!in_array($pricingMode, ['none', 'fixed', 'percent', 'fixed_percent'], true)) {
                    $pricingMode = 'none';
                }

                $fixedAmountRaw = trim((string) ($f['fixed_amount'] ?? ''));
                $fixedAmount = $fixedAmountRaw === '' ? 0 : (float) preg_replace('/[^0-9.]/', '', $fixedAmountRaw);
                if ($fixedAmount < 0) $fixedAmount = 0;

                $percentRaw = trim((string) ($f['percent'] ?? ''));
                $percent = $percentRaw === '' ? 0 : (float) preg_replace('/[^0-9.]/', '', $percentRaw);
                if ($percent < 0) $percent = 0;
                if ($percent > 100) $percent = 100;

                $item['options'] = $options;
                $item['pricing_mode'] = $pricingMode;
                $item['fixed_amount'] = round($fixedAmount, 2);
                $item['percent'] = round($percent, 2);
            }

            $schema[] = $item;
            $seen[$key] = true;
        }

        return count($schema) ? $schema : null;
    }
}
