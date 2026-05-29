@extends('admin.layout')

@section('title', 'Contact Messages')

@section('content')
    <h1 class="h3 mb-3">Contact Messages</h1>

    <div class="table-responsive">
        <table class="table table-striped align-middle">
            <thead>
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Email</th>
                <th>Subject</th>
                <th>Status</th>
                <th class="text-end">Actions</th>
            </tr>
            </thead>
            <tbody>
            @forelse($items as $item)
                <tr>
                    <td>{{ $item->id }}</td>
                    <td>{{ $item->name }}</td>
                    <td>{{ $item->email }}</td>
                    <td>{{ $item->subject }}</td>
                    <td>{{ $item->status }}</td>
                    <td class="text-end">
                        <a class="btn btn-sm btn-outline-primary" href="{{ route('admin.contact-messages.show', $item) }}">View</a>
                        <form method="post" action="{{ route('admin.contact-messages.destroy', $item) }}" class="d-inline">
                            @csrf
                            @method('DELETE')
                            <button class="btn btn-sm btn-outline-danger" type="submit">Delete</button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" class="text-center text-muted">No messages</td>
                </tr>
            @endforelse
            </tbody>
        </table>
    </div>
@endsection

