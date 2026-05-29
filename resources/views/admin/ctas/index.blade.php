@extends('admin.layout')

@section('title', 'Home CTAs')

@section('content')
    <h1 class="h3 mb-3">Home CTAs</h1>

    <div class="table-responsive">
        <table class="table table-striped align-middle">
            <thead>
            <tr>
                <th>Slug</th>
                <th>Heading</th>
                <th>Active</th>
                <th class="text-end">Actions</th>
            </tr>
            </thead>
            <tbody>
            @forelse($items as $item)
                <tr>
                    <td><code>{{ $item->slug }}</code></td>
                    <td>{{ $item->heading }}</td>
                    <td>{{ $item->is_active ? 'Yes' : 'No' }}</td>
                    <td class="text-end">
                        <a class="btn btn-sm btn-outline-primary" href="{{ route('admin.ctas.edit', $item) }}">Edit</a>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="4" class="text-center text-muted">No CTAs</td>
                </tr>
            @endforelse
            </tbody>
        </table>
    </div>
@endsection

