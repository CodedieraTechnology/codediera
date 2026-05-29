@extends('admin.layout')

@section('title', 'Services')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h1 class="h3 mb-0">Services</h1>
        <a class="btn btn-primary" href="{{ route('admin.services.create') }}">Add</a>
    </div>

    <div class="table-responsive">
        <table class="table table-striped align-middle">
            <thead>
            <tr>
                <th>ID</th>
                <th>Title</th>
                <th>Type</th>
                <th>Pricing</th>
                <th>Payment</th>
                <th>Approach</th>
                <th>Active</th>
                <th>Order</th>
                <th class="text-end">Actions</th>
            </tr>
            </thead>
            <tbody>
            @forelse($items as $item)
                <tr>
                    <td>{{ $item->id }}</td>
                    <td>{{ $item->title }}</td>
                    <td>
                        @if(!empty($item->service_type))
                            <span class="badge text-bg-light text-dark">{{ $item->service_type }}</span>
                        @else
                            <span class="text-muted">—</span>
                        @endif
                    </td>
                    <td>
                        @if($item->is_free)
                            <span class="badge text-bg-success">Free</span>
                        @elseif(!is_null($item->price))
                            <span class="badge text-bg-primary">₦{{ number_format((float)$item->price, 2) }}</span>
                        @else
                            <span class="text-muted">Custom</span>
                        @endif
                    </td>
                    <td>
                        @if(($item->payment_type ?? 'one_time') === 'monthly')
                            <span class="badge text-bg-warning text-dark">Monthly</span>
                        @elseif(($item->payment_type ?? 'one_time') === 'yearly')
                            <span class="badge text-bg-warning text-dark">Yearly</span>
                        @elseif(($item->payment_type ?? 'one_time') === 'custom')
                            <span class="badge text-bg-dark">Custom</span>
                        @else
                            <span class="badge text-bg-dark">One-time</span>
                        @endif
                    </td>
                    <td>
                        @if($item->approach_image_path)
                            <a class="btn btn-sm btn-outline-secondary" href="{{ asset('storage/'.$item->approach_image_path) }}" target="_blank" rel="noopener">Open</a>
                        @elseif($item->screenshot_path)
                            <a class="btn btn-sm btn-outline-secondary" href="{{ asset('storage/'.$item->screenshot_path) }}" target="_blank" rel="noopener">Screenshot</a>
                        @else
                            <span class="text-muted">—</span>
                        @endif
                    </td>
                    <td>{{ $item->is_active ? 'Yes' : 'No' }}</td>
                    <td>{{ $item->sort_order }}</td>
                    <td class="text-end">
                        <div class="d-flex justify-content-end gap-2">
                            <a class="btn btn-sm btn-outline-success" href="{{ route('admin.services.show', $item) }}" title="View" aria-label="View">
                                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" fill="currentColor" viewBox="0 0 16 16" aria-hidden="true">
                                    <path d="M16 8s-3-5.5-8-5.5S0 8 0 8s3 5.5 8 5.5S16 8 16 8z"/>
                                    <path d="M8 5a3 3 0 1 0 0 6 3 3 0 0 0 0-6z"/>
                                </svg>
                            </a>
                            <a class="btn btn-sm btn-outline-primary" href="{{ route('admin.services.edit', $item) }}" title="Edit" aria-label="Edit">
                                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" fill="currentColor" viewBox="0 0 16 16" aria-hidden="true">
                                    <path d="M15.502 1.94a.5.5 0 0 1 0 .706L14.459 3.69l-2-2L13.502.646a.5.5 0 0 1 .707 0l1.293 1.293z"/>
                                    <path d="M13.752 4.396l-2-2L4.939 9.21a.5.5 0 0 0-.121.196l-.805 2.414a.25.25 0 0 0 .316.316l2.414-.805a.5.5 0 0 0 .196-.12l6.813-6.814z"/>
                                    <path fill-rule="evenodd" d="M1 13.5A1.5 1.5 0 0 0 2.5 15h11a1.5 1.5 0 0 0 1.5-1.5v-6a.5.5 0 0 0-1 0v6a.5.5 0 0 1-.5.5h-11a.5.5 0 0 1-.5-.5v-11a.5.5 0 0 1 .5-.5H9a.5.5 0 0 0 0-1H2.5A1.5 1.5 0 0 0 1 2.5v11z"/>
                                </svg>
                            </a>
                            <form method="post" action="{{ route('admin.services.destroy', $item) }}" class="d-inline">
                                @csrf
                                @method('DELETE')
                                <button class="btn btn-sm btn-outline-danger" type="submit" title="Delete" aria-label="Delete">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" fill="currentColor" viewBox="0 0 16 16" aria-hidden="true">
                                        <path d="M5.5 5.5a.5.5 0 0 1 .5.5v7a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5zm2.5.5a.5.5 0 0 0-1 0v7a.5.5 0 0 0 1 0V6zm2 .5a.5.5 0 0 1 .5-.5.5.5 0 0 1 .5.5v7a.5.5 0 0 1-1 0V6z"/>
                                        <path fill-rule="evenodd" d="M14.5 3a1 1 0 0 1-1 1H13v9a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V4h-.5a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1H6a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1h3.5a1 1 0 0 1 1 1v1zM4.118 4 4 4.059V13a1 1 0 0 0 1 1h6a1 1 0 0 0 1-1V4.059L11.882 4H4.118zM2.5 3V2h11v1h-11z"/>
                                    </svg>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="9" class="text-center text-muted">No services</td>
                </tr>
            @endforelse
            </tbody>
        </table>
    </div>
@endsection
