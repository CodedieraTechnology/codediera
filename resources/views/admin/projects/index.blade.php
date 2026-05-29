@extends('admin.layout')

@section('title', 'Projects')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h1 class="h3 mb-0">Projects</h1>
        <a class="btn btn-primary" href="{{ route('admin.projects.create') }}">Add</a>
    </div>

    <div class="table-responsive">
        <table class="table table-striped align-middle">
            <thead>
            <tr>
                <th>ID</th>
                <th>Title</th>
                <th>Cost</th>
                <th>Active</th>
                <th>Order</th>
                <th>Image</th>
                <th>ZIP</th>
                <th class="text-end">Actions</th>
            </tr>
            </thead>
            <tbody>
            @forelse($items as $item)
                <tr>
                    <td>{{ $item->id }}</td>
                    <td>{{ $item->title }}</td>
                    <td>
                        @if(!is_null($item->cost))
                            ₦{{ number_format((float)$item->cost, 2) }}
                        @else
                            <span class="text-muted">—</span>
                        @endif
                    </td>
                    <td>{{ $item->is_active ? 'Yes' : 'No' }}</td>
                    <td>{{ $item->sort_order }}</td>
                    <td>
                        @if($item->image_path)
                            <img src="{{ asset('storage/'.$item->image_path) }}" alt="" style="height:48px">
                        @endif
                    </td>
                    <td>
                        @if($item->zip_path)
                            <a class="btn btn-sm btn-outline-primary" href="{{ asset('storage/'.$item->zip_path) }}" download>ZIP</a>
                        @else
                            <span class="text-muted">—</span>
                        @endif
                    </td>
                    <td class="text-end">
                        <a class="btn btn-sm btn-outline-primary" href="{{ route('admin.projects.edit', $item) }}">Edit</a>
                        <form method="post" action="{{ route('admin.projects.destroy', $item) }}" class="d-inline">
                            @csrf
                            @method('DELETE')
                            <button class="btn btn-sm btn-outline-danger" type="submit">Delete</button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="8" class="text-center text-muted">No projects</td>
                </tr>
            @endforelse
            </tbody>
        </table>
    </div>
@endsection
