@extends('admin.layout')

@section('title', 'Slider')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h1 class="h3 mb-0">Slider</h1>
        <a class="btn btn-primary" href="{{ route('admin.sliders.create') }}">Add</a>
    </div>

    <div class="table-responsive">
        <table class="table table-striped align-middle">
            <thead>
            <tr>
                <th>ID</th>
                <th>Title</th>
                <th>Active</th>
                <th>Order</th>
                <th>Media</th>
                <th class="text-end">Actions</th>
            </tr>
            </thead>
            <tbody>
            @forelse($items as $item)
                <tr>
                    <td>{{ $item->id }}</td>
                    <td>{{ $item->title }}</td>
                    <td>{{ $item->is_active ? 'Yes' : 'No' }}</td>
                    <td>{{ $item->sort_order }}</td>
                    <td>
                        @if($item->image_path)
                            <img src="{{ asset('storage/'.$item->image_path) }}" alt="" style="height:48px">
                        @elseif($item->video_path)
                            <span class="badge text-bg-secondary">Video</span>
                        @endif
                    </td>
                    <td class="text-end">
                        <a class="btn btn-sm btn-outline-primary" href="{{ route('admin.sliders.edit', $item) }}">Edit</a>
                        <form method="post" action="{{ route('admin.sliders.destroy', $item) }}" class="d-inline">
                            @csrf
                            @method('DELETE')
                            <button class="btn btn-sm btn-outline-danger" type="submit">Delete</button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" class="text-center text-muted">No items</td>
                </tr>
            @endforelse
            </tbody>
        </table>
    </div>
@endsection
