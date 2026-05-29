@csrf
@php
    $serviceType = $serviceType ?? null;
@endphp

<div class="mb-3">
    <label class="form-label" for="key">Key</label>
    <input class="form-control @error('key') is-invalid @enderror" id="key" name="key" value="{{ old('key', $serviceType?->key ?? '') }}" placeholder="e.g. social_media_management" required>
    @error('key')
        <div class="invalid-feedback">{{ $message }}</div>
    @enderror
</div>

<div class="mb-3">
    <label class="form-label" for="name">Name</label>
    <input class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name', $serviceType?->name ?? '') }}" placeholder="e.g. Social media Management" required>
    @error('name')
        <div class="invalid-feedback">{{ $message }}</div>
    @enderror
</div>

<div class="mb-3">
    <div class="d-flex justify-content-between align-items-center mb-2">
        <label class="form-label mb-0">Fields</label>
        <button class="btn btn-sm btn-outline-primary" id="addServiceTypeFieldBtn" type="button">Add field</button>
    </div>
    <div class="table-responsive">
        <table class="table table-sm align-middle mb-0" id="serviceTypeFieldsTable">
            <thead>
            <tr>
                <th style="width: 14%;">Key</th>
                <th style="width: 18%;">Label</th>
                <th style="width: 16%;">Type</th>
                <th style="width: 18%;">Options</th>
                <th style="width: 14%;">Pricing</th>
                <th style="width: 10%;">Fixed (₦)</th>
                <th style="width: 10%;">Percent (%)</th>
                <th style="width: 6%;">Req</th>
                <th style="width: 6%;"></th>
            </tr>
            </thead>
            <tbody>
            @php
                $existingFields = old('fields');
                if (!is_array($existingFields)) {
                    $existingFields = is_array($serviceType?->schema ?? null) ? $serviceType->schema : [];
                }
            @endphp
            @foreach($existingFields as $i => $f)
                @php
                    $fKey = is_array($f) ? ($f['key'] ?? '') : '';
                    $fLabel = is_array($f) ? ($f['label'] ?? '') : '';
                    $fType = is_array($f) ? ($f['type'] ?? 'text') : 'text';
                    $fReq = is_array($f) ? !empty($f['required']) : false;
                    $fOpts = is_array($f) ? ($f['options'] ?? []) : [];
                    $fPricingMode = is_array($f) ? (string)($f['pricing_mode'] ?? 'none') : 'none';
                    $fFixedAmount = is_array($f) ? (string)($f['fixed_amount'] ?? '') : '';
                    $fPercent = is_array($f) ? (string)($f['percent'] ?? '') : '';

                    $fOptsText = '';
                    if (is_array($fOpts)) {
                        $pairs = [];
                        foreach ($fOpts as $opt) {
                            if (is_string($opt)) {
                                $pairs[] = $opt;
                                continue;
                            }
                            if (is_array($opt)) {
                                $lbl = (string)($opt['label'] ?? $opt['value'] ?? '');
                                $lbl = trim($lbl);
                                if ($lbl === '') continue;
                                $price = $opt['price'] ?? null;
                                if ($price === null || $price === '') {
                                    $pairs[] = $lbl;
                                } else {
                                    $pairs[] = $lbl . '=' . $price;
                                }
                            }
                        }
                        $fOptsText = implode(', ', $pairs);
                    } else {
                        $fOptsText = (string) $fOpts;
                    }
                @endphp
                <tr>
                    <td><input class="form-control form-control-sm" name="fields[{{ $loop->index }}][key]" value="{{ $fKey }}" placeholder="e.g. school_name"></td>
                    <td><input class="form-control form-control-sm" name="fields[{{ $loop->index }}][label]" value="{{ $fLabel }}" placeholder="Label"></td>
                    <td>
                        <select class="form-select form-select-sm" name="fields[{{ $loop->index }}][type]">
                            @foreach(['text' => 'Text', 'textarea' => 'Textarea', 'number' => 'Number', 'select' => 'Select', 'multi_select' => 'Multi Select', 'priced_multi_select' => 'Priced Multi Select', 'checkbox' => 'Checkbox', 'image' => 'Image'] as $k => $v)
                                <option value="{{ $k }}" @selected($fType === $k)>{{ $v }}</option>
                            @endforeach
                        </select>
                    </td>
                    <td><input class="form-control form-control-sm" name="fields[{{ $loop->index }}][options]" value="{{ $fOptsText }}" placeholder="a, b, c"></td>
                    <td>
                        <select class="form-select form-select-sm" name="fields[{{ $loop->index }}][pricing_mode]">
                            @foreach(['none' => 'None', 'fixed' => 'Fixed', 'percent' => 'Percent', 'fixed_percent' => 'Fixed + Percent'] as $k => $v)
                                <option value="{{ $k }}" @selected($fPricingMode === $k)>{{ $v }}</option>
                            @endforeach
                        </select>
                    </td>
                    <td><input class="form-control form-control-sm" name="fields[{{ $loop->index }}][fixed_amount]" value="{{ $fFixedAmount }}" placeholder="0"></td>
                    <td><input class="form-control form-control-sm" name="fields[{{ $loop->index }}][percent]" value="{{ $fPercent }}" placeholder="0"></td>
                    <td class="text-center">
                        <input class="form-check-input" name="fields[{{ $loop->index }}][required]" type="checkbox" value="1" @checked($fReq)>
                    </td>
                    <td class="text-end">
                        <button class="btn btn-sm btn-outline-danger removeServiceTypeFieldBtn" type="button">Remove</button>
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>
    @error('fields')
        <div class="text-danger small mt-2">{{ $message }}</div>
    @enderror
</div>

<div class="row g-2">
    <div class="col-12 col-md-6">
        <label class="form-label" for="sort_order">Order</label>
        <input class="form-control @error('sort_order') is-invalid @enderror" id="sort_order" name="sort_order" type="number" min="0" value="{{ old('sort_order', $serviceType?->sort_order ?? 0) }}">
        @error('sort_order')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>
    <div class="col-12 col-md-6 d-flex align-items-end">
        <div class="form-check">
            <input class="form-check-input" id="is_active" name="is_active" type="checkbox" value="1" @checked(old('is_active', $serviceType?->is_active ?? true))>
            <label class="form-check-label" for="is_active">Active</label>
        </div>
    </div>
</div>
