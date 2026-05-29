@extends('admin.layout')

@section('title', 'Team')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h1 class="h3 mb-0">Team</h1>
        <a class="btn btn-primary" href="{{ route('admin.team.create') }}">Add</a>
    </div>

    <div class="table-responsive">
        <table class="table table-striped align-middle">
            <thead>
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Role</th>
                <th>Active</th>
                <th>Order</th>
                <th>Photo</th>
                <th class="text-end">Actions</th>
            </tr>
            </thead>
            <tbody>
            @forelse($items as $item)
                <tr>
                    <td>{{ $item->id }}</td>
                    <td>{{ $item->name }}</td>
                    <td>{{ $item->role }}</td>
                    <td>{{ $item->is_active ? 'Yes' : 'No' }}</td>
                    <td>{{ $item->sort_order }}</td>
                    <td>
                        @if($item->photo_path)
                            <img src="{{ asset('storage/'.$item->photo_path) }}" alt="" style="height:48px;border-radius:24px">
                        @endif
                    </td>
                    <td class="text-end">
                        <a class="btn btn-sm btn-outline-primary" href="{{ route('admin.team.edit', $item) }}">Edit</a>
                        <form method="post" action="{{ route('admin.team.destroy', $item) }}" class="d-inline">
                            @csrf
                            @method('DELETE')
                            <button class="btn btn-sm btn-outline-danger" type="submit">Delete</button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="7" class="text-center text-muted">No team members</td>
                </tr>
            @endforelse
            </tbody>
        </table>
    </div>
@endsection

