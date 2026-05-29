@extends('admin.layout')

@section('title', $service->title)

@section('content')
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center gap-2 mb-3">
        <div>
            <h1 class="h3 mb-1">{{ $service->title }}</h1>
            <div class="text-muted">Service details and requests</div>
        </div>
        <div class="d-flex gap-2">
            <a class="btn btn-outline-secondary" href="{{ route('admin.services.index') }}">Back</a>
            <a class="btn btn-primary" href="{{ route('admin.services.edit', $service) }}">Edit</a>
        </div>
    </div>

    <div class="row g-3 mb-4">
        <div class="col-12 col-lg-6">
            <div class="card">
                <div class="card-body">
                    @php($heroImage = $service->approach_image_path ?: $service->screenshot_path)
                    @if($heroImage)
                        <a href="{{ asset('storage/'.$heroImage) }}" target="_blank" rel="noopener" class="d-block mb-3">
                            <img class="img-fluid rounded-4 w-100" src="{{ asset('storage/'.$heroImage) }}" alt="{{ $service->title }}">
                        </a>
                    @endif
                    <div class="d-flex flex-wrap gap-2 mb-3">
                        @if($service->is_free)
                            <span class="badge text-bg-success">Free</span>
                        @elseif(!is_null($service->price))
                            <span class="badge text-bg-primary">₦{{ number_format((float)$service->price, 2) }}</span>
                        @else
                            <span class="badge text-bg-secondary">Custom</span>
                        @endif
                        @php($paymentType = $service->payment_type ?: 'one_time')
                        @if($paymentType === 'monthly')
                            <span class="badge text-bg-warning text-dark">Monthly</span>
                        @elseif($paymentType === 'yearly')
                            <span class="badge text-bg-warning text-dark">Yearly</span>
                        @elseif($paymentType === 'custom')
                            <span class="badge text-bg-dark">Custom plan</span>
                        @else
                            <span class="badge text-bg-dark">One-time</span>
                        @endif
                        <span class="badge text-bg-{{ $service->is_active ? 'success' : 'secondary' }}">{{ $service->is_active ? 'Active' : 'Inactive' }}</span>
                        <span class="badge text-bg-light text-dark">Order: {{ $service->sort_order }}</span>
                        @if($service->icon)
                            <span class="badge text-bg-light text-dark">Icon: {{ $service->icon }}</span>
                        @endif
                        @if(!empty($service->service_type))
                            <span class="badge text-bg-light text-dark">Type: {{ $service->service_type }}</span>
                        @endif
                    </div>
                    <div class="text-muted small mb-3">
                        @php($enabled = array_values(array_unique(array_filter($service->inquiry_fields ?? ['phone', 'company', 'budget', 'message']))))
                        Request fields: {{ implode(', ', $enabled) }}
                    </div>
                    <div class="d-flex flex-wrap gap-2 mb-3">
                        <a class="btn btn-sm btn-outline-primary" href="{{ route('services.show', $service) }}" target="_blank" rel="noopener">Open public page</a>
                    </div>
                    <div class="card border-0" style="background: rgba(0,0,0,0.02);">
                        <div class="card-body">
                            <div class="fw-semibold mb-2">Delivery</div>
                            <div class="row g-2 small">
                                <div class="col-12"><span class="text-muted">Download URL:</span> {{ $service->download_url ?: '—' }}</div>
                            </div>
                            @if($service->instructions)
                                <hr>
                                <div class="fw-semibold mb-2">Instructions</div>
                                <div>{!! $service->instructions !!}</div>
                            @endif
                        </div>
                    </div>

                    @if($service->description)
                        <div class="mb-0">{!! $service->description !!}</div>
                    @else
                        <div class="text-muted">No description provided.</div>
                    @endif

                    @if($service->images->count())
                        <hr>
                        <div class="fw-semibold mb-2">Gallery</div>
                        <div class="row g-2">
                            @foreach($service->images as $img)
                                <div class="col-6 col-md-4">
                                    <a href="{{ asset('storage/'.$img->image_path) }}" target="_blank" rel="noopener" class="d-block">
                                        <img class="img-fluid rounded-4 w-100" src="{{ asset('storage/'.$img->image_path) }}" alt="">
                                    </a>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <div class="col-12 col-lg-6">
            <div class="card">
                <div class="card-body">
                    <div class="text-muted small mb-2">Requests</div>
                    <div class="d-flex flex-wrap gap-2">
                        <span class="badge text-bg-dark">Total: {{ $counts['total'] ?? 0 }}</span>
                        <span class="badge text-bg-primary">New: {{ $counts['new'] ?? 0 }}</span>
                        <span class="badge text-bg-warning text-dark">In progress: {{ $counts['in_progress'] ?? 0 }}</span>
                        <span class="badge text-bg-success">Done: {{ $counts['done'] ?? 0 }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-body">
            <h2 class="h5 mb-3">Service Requests</h2>
            <div class="table-responsive">
                <table class="table table-striped align-middle mb-0">
                    <thead>
                    <tr>
                        <th>ID</th>
                        <th>Client</th>
                        <th>Contact</th>
                        <th>Company</th>
                        <th>Budget</th>
                        <th>Message</th>
                        <th>Status</th>
                        <th style="min-width: 200px;">Progress</th>
                        <th>Created</th>
                        <th class="text-end">Actions</th>
                    </tr>
                    </thead>
                    <tbody>
                    @forelse($service->inquiries as $inquiry)
                        <tr>
                            <td>{{ $inquiry->id }}</td>
                            <td>{{ $inquiry->name }}</td>
                            <td>
                                <div>{{ $inquiry->email }}</div>
                                @if($inquiry->phone)
                                    <div class="text-muted small">{{ $inquiry->phone }}</div>
                                @endif
                            </td>
                            <td>{{ $inquiry->company ?: '—' }}</td>
                            <td>{{ $inquiry->budget ?: '—' }}</td>
                            <td style="max-width: 360px;">
                                <div class="small">{{ $inquiry->message ?: '—' }}</div>
                            </td>
                            <td>
                                <form method="post" action="{{ route('admin.service-inquiries.status', $inquiry) }}" class="d-flex gap-2 align-items-center">
                                    @csrf
                                    @method('PUT')
                                    <select class="form-select form-select-sm" name="status">
                                        <option value="new" @selected($inquiry->status === 'new')>new</option>
                                        <option value="in_progress" @selected($inquiry->status === 'in_progress')>in_progress</option>
                                        <option value="done" @selected($inquiry->status === 'done')>done</option>
                                    </select>
                                    <button class="btn btn-sm btn-outline-primary" type="submit">Save</button>
                                </form>
                            </td>
                            <td>
                                @php($p = (int)($inquiry->progress_percent ?? 0))
                                <div class="d-flex justify-content-between align-items-center mb-1">
                                    <div class="text-muted small">Progress</div>
                                    <div class="small fw-semibold">{{ $p }}%</div>
                                </div>
                                <div class="progress" style="height: 8px;">
                                    <div class="progress-bar" role="progressbar" style="width: {{ $p }}%;" aria-valuenow="{{ $p }}" aria-valuemin="0" aria-valuemax="100"></div>
                                </div>
                                @if($inquiry->progress_note)
                                    <div class="text-muted small mt-1">{{ $inquiry->progress_note }}</div>
                                @endif
                                <div class="mt-1">
                                    <a class="small text-decoration-none" href="{{ route('admin.service-subscriptions.show', $inquiry) }}">Open subscription</a>
                                </div>
                            </td>
                            <td class="text-muted small">{{ $inquiry->created_at?->format('Y-m-d H:i') }}</td>
                            <td class="text-end">
                                <form method="post" action="{{ route('admin.service-inquiries.destroy', $inquiry) }}" class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn btn-sm btn-outline-danger" type="submit">Delete</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="10" class="text-center text-muted">No requests yet.</td>
                        </tr>
                    @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
