@extends('admin.layout')

@section('title', 'Jobs')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h1 class="h3 mb-0">Jobs</h1>
        <a class="btn btn-primary" href="{{ route('admin.jobs.create') }}">Add</a>
    </div>

    <div class="table-responsive">
        <table class="table table-striped align-middle">
            <thead>
            <tr>
                <th>ID</th>
                <th>Title</th>
                <th>Location</th>
                <th>Type</th>
                <th>Active</th>
                <th>Posted</th>
                <th>Order</th>
                <th class="text-end">Actions</th>
            </tr>
            </thead>
            <tbody>
            @forelse($items as $item)
                <tr>
                    <td>{{ $item->id }}</td>
                    <td>{{ $item->title }}</td>
                    <td>{{ $item->location }}</td>
                    <td>{{ $item->employment_type }}</td>
                    <td>{{ $item->is_active ? 'Yes' : 'No' }}</td>
                    <td>{{ $item->posted_at }}</td>
                    <td>{{ $item->sort_order }}</td>
                    <td class="text-end">
                        <a class="btn btn-sm btn-outline-primary" href="{{ route('admin.jobs.edit', $item) }}">Edit</a>
                        <form method="post" action="{{ route('admin.jobs.destroy', $item) }}" class="d-inline">
                            @csrf
                            @method('DELETE')
                            <button class="btn btn-sm btn-outline-danger" type="submit">Delete</button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="8" class="text-center text-muted">No jobs</td>
                </tr>
            @endforelse
            </tbody>
        </table>
    </div>
@endsection

