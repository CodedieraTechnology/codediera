@extends('admin.layout')

@section('title', 'Job Applications')

@section('content')
    <h1 class="h3 mb-3">Job Applications</h1>

    <div class="table-responsive">
        <table class="table table-striped align-middle">
            <thead>
            <tr>
                <th>ID</th>
                <th>Job</th>
                <th>Full Name</th>
                <th>Email</th>
                <th>Phone</th>
                <th>Status</th>
                <th class="text-end">Actions</th>
            </tr>
            </thead>
            <tbody>
            @forelse($items as $item)
                <tr>
                    <td>{{ $item->id }}</td>
                    <td>{{ $item->vacancy?->title ?? $item->position }}</td>
                    <td>{{ $item->full_name }}</td>
                    <td>{{ $item->email }}</td>
                    <td>{{ $item->phone }}</td>
                    <td>{{ $item->status }}</td>
                    <td class="text-end">
                        <a class="btn btn-sm btn-outline-primary" href="{{ route('admin.job-applications.show', $item) }}">View</a>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="7" class="text-center text-muted">No applications</td>
                </tr>
            @endforelse
            </tbody>
        </table>
    </div>
@endsection
