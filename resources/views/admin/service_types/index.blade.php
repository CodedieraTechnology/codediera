@extends('admin.layout')

@section('title', 'Service Types')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-3">
        <div>
            <div class="text-muted small">Services</div>
            <h1 class="h3 mb-0">Service Types</h1>
        </div>
        <a class="btn btn-primary" href="{{ route('admin.service-types.create') }}">Add type</a>
    </div>

    @if(session('status'))
        <div class="alert alert-success">{{ session('status') }}</div>
    @endif

    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead>
                    <tr>
                        <th>ID</th>
                        <th>Key</th>
                        <th>Name</th>
                        <th>Active</th>
                        <th>Order</th>
                        <th class="text-end">Actions</th>
                    </tr>
                    </thead>
                    <tbody>
                    @forelse($types as $t)
                        <tr>
                            <td>{{ $t->id }}</td>
                            <td><span class="badge text-bg-light text-dark">{{ $t->key }}</span></td>
                            <td class="fw-semibold">{{ $t->name }}</td>
                            <td>
                                <span class="badge {{ $t->is_active ? 'text-bg-success' : 'text-bg-secondary' }}">{{ $t->is_active ? 'Yes' : 'No' }}</span>
                            </td>
                            <td>{{ $t->sort_order }}</td>
                            <td class="text-end">
                                <div class="d-flex justify-content-end gap-2">
                                    <a class="btn btn-sm btn-outline-primary" href="{{ route('admin.service-types.edit', $t) }}">Edit</a>
                                    <form method="post" action="{{ route('admin.service-types.destroy', $t) }}" onsubmit="return confirm('Delete this type?')">
                                        @csrf
                                        @method('DELETE')
                                        <button class="btn btn-sm btn-outline-danger" type="submit">Delete</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-muted">No types yet.</td>
                        </tr>
                    @endforelse
                    </tbody>
                </table>
            </div>

            <div class="mt-3">
                {{ $types->links() }}
            </div>
        </div>
    </div>
@endsection

