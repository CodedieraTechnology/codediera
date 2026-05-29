@extends('admin.layout')

@section('title', 'Edit Service Type')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h1 class="h3 mb-0">Edit Service Type</h1>
        <a class="btn btn-outline-secondary" href="{{ route('admin.service-types.index') }}">Back</a>
    </div>

    <div class="card">
        <div class="card-body">
            <form method="post" action="{{ route('admin.service-types.update', $serviceType) }}">
                @csrf
                @method('PUT')
                @include('admin.service_types.form', ['serviceType' => $serviceType])
                <div class="mt-3">
                    <button class="btn btn-primary" type="submit">Save</button>
                </div>
            </form>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            var table = document.getElementById('serviceTypeFieldsTable');
            var addBtn = document.getElementById('addServiceTypeFieldBtn');
            if (!table || !addBtn) return;
            var tbody = table.querySelector('tbody');
            if (!tbody) return;

            function nextIndex() {
                var maxIdx = -1;
                tbody.querySelectorAll('input[name^="fields["], select[name^="fields["], textarea[name^="fields["]').forEach(function (el) {
                    var n = el.getAttribute('name') || '';
                    var m = n.match(/^fields\[(\d+)\]/);
                    if (!m) return;
                    var i = parseInt(m[1], 10);
                    if (!isNaN(i) && i > maxIdx) maxIdx = i;
                });
                return maxIdx + 1;
            }

            function createRow(idx, data) {
                var key = data && data.key ? data.key : '';
                var label = data && data.label ? data.label : '';
                var type = data && data.type ? data.type : 'text';
                var options = data && data.options ? data.options : '';
                var pricingMode = data && data.pricing_mode ? data.pricing_mode : 'none';
                var fixedAmount = (data && (data.fixed_amount || data.fixed_amount === 0)) ? data.fixed_amount : '';
                var percent = (data && (data.percent || data.percent === 0)) ? data.percent : '';
                var required = !!(data && data.required);

                var tr = document.createElement('tr');
                tr.innerHTML =
                    '<td><input class="form-control form-control-sm" name="fields[' + idx + '][key]" placeholder="e.g. school_name" value="' + String(key).replace(/"/g, '&quot;') + '"></td>' +
                    '<td><input class="form-control form-control-sm" name="fields[' + idx + '][label]" placeholder="Label" value="' + String(label).replace(/"/g, '&quot;') + '"></td>' +
                    '<td>' +
                    '  <select class="form-select form-select-sm" name="fields[' + idx + '][type]">' +
                    '    <option value="text">Text</option>' +
                    '    <option value="textarea">Textarea</option>' +
                    '    <option value="number">Number</option>' +
                    '    <option value="select">Select</option>' +
                    '    <option value="multi_select">Multi Select</option>' +
                    '    <option value="priced_multi_select">Priced Multi Select</option>' +
                    '    <option value="checkbox">Checkbox</option>' +
                    '    <option value="image">Image</option>' +
                    '  </select>' +
                    '</td>' +
                    '<td><input class="form-control form-control-sm" name="fields[' + idx + '][options]" placeholder="a, b, c" value="' + String(options).replace(/"/g, '&quot;') + '"></td>' +
                    '<td>' +
                    '  <select class="form-select form-select-sm" name="fields[' + idx + '][pricing_mode]">' +
                    '    <option value="none">None</option>' +
                    '    <option value="fixed">Fixed</option>' +
                    '    <option value="percent">Percent</option>' +
                    '    <option value="fixed_percent">Fixed + Percent</option>' +
                    '  </select>' +
                    '</td>' +
                    '<td><input class="form-control form-control-sm" name="fields[' + idx + '][fixed_amount]" placeholder="0" value="' + String(fixedAmount).replace(/"/g, '&quot;') + '"></td>' +
                    '<td><input class="form-control form-control-sm" name="fields[' + idx + '][percent]" placeholder="0" value="' + String(percent).replace(/"/g, '&quot;') + '"></td>' +
                    '<td class="text-center"><input class="form-check-input" name="fields[' + idx + '][required]" type="checkbox" value="1"' + (required ? ' checked' : '') + '></td>' +
                    '<td class="text-end"><button class="btn btn-sm btn-outline-danger removeServiceTypeFieldBtn" type="button">Remove</button></td>';

                var select = tr.querySelector('select');
                if (select) select.value = type;
                var pricingSelect = tr.querySelector('select[name*="[pricing_mode]"]');
                if (pricingSelect) pricingSelect.value = pricingMode;
                return tr;
            }

            function syncOptionsVisibility(row) {
                var typeEl = row.querySelector('select[name*="[type]"]');
                var optsEl = row.querySelector('input[name*="[options]"]');
                var pricingEl = row.querySelector('select[name*="[pricing_mode]"]');
                var fixedEl = row.querySelector('input[name*="[fixed_amount]"]');
                var percentEl = row.querySelector('input[name*="[percent]"]');
                if (!typeEl || !optsEl) return;
                var t = (typeEl.value || '').toLowerCase();
                var needs = (t === 'select' || t === 'multi_select' || t === 'priced_multi_select');
                optsEl.placeholder = (t === 'priced_multi_select') ? 'Facebook=5000, Instagram=5000' : (needs ? 'a, b, c' : '—');
            }

            addBtn.addEventListener('click', function () {
                var idx = nextIndex();
                var row = createRow(idx, null);
                tbody.appendChild(row);
                syncOptionsVisibility(row);
            });

            tbody.addEventListener('click', function (e) {
                var btn = e.target && e.target.closest ? e.target.closest('.removeServiceTypeFieldBtn') : null;
                if (!btn) return;
                var row = btn.closest('tr');
                if (!row) return;
                row.remove();
            });

            tbody.addEventListener('change', function (e) {
                var sel = e.target && e.target.matches ? (e.target.matches('select[name*="[type]"]') ? e.target : null) : null;
                if (!sel) return;
                var row = sel.closest('tr');
                if (!row) return;
                syncOptionsVisibility(row);
            });

            Array.prototype.slice.call(tbody.querySelectorAll('tr')).forEach(function (row) {
                syncOptionsVisibility(row);
            });
        });
    </script>
@endsection
