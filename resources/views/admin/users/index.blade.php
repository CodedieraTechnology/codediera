@extends('admin.layout')

@section('title', 'Manage Users')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h1 class="h3 mb-0">Manage Users</h1>
        <a class="btn btn-primary" href="{{ route('admin.users.create') }}">Add User</a>
    </div>

    <div class="card">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                    <tr>
                        <th class="ps-4">ID</th>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Roles</th>
                        <th>Admin?</th>
                        <th>Instructor?</th>
                        <th>Created At</th>
                        <th class="text-end pe-4">Actions</th>
                    </tr>
                    </thead>
                    <tbody>
                    @forelse($users as $user)
                        <tr>
                            <td class="ps-4">{{ $user->id }}</td>
                            <td class="fw-semibold">{{ $user->name }}</td>
                            <td>{{ $user->email }}</td>
                            <td>
                                @forelse($user->roles as $role)
                                    <span class="badge bg-indigo text-white px-2.5 py-1.5" style="background-color: var(--cd-primary-color, #6366f1) !important;">{{ $role->name }}</span>
                                @empty
                                    <span class="text-muted small">—</span>
                                @endforelse
                            </td>
                            <td>
                                @if($user->is_admin)
                                    <span class="badge bg-success text-white">Yes</span>
                                @else
                                    <span class="badge bg-secondary text-white">No</span>
                                @endif
                            </td>
                            <td>
                                @if($user->is_instructor)
                                    <span class="badge bg-info text-white">Yes</span>
                                @else
                                    <span class="badge bg-secondary text-white">No</span>
                                @endif
                            </td>
                            <td>{{ $user->created_at->format('M j, Y') }}</td>
                            <td class="text-end pe-4">
                                <div class="d-flex justify-content-end gap-2">
                                    <a class="btn btn-sm btn-outline-primary" href="{{ route('admin.users.edit', $user) }}" title="Edit" aria-label="Edit">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" fill="currentColor" viewBox="0 0 16 16" aria-hidden="true">
                                            <path d="M15.502 1.94a.5.5 0 0 1 0 .706L14.459 3.69l-2-2L13.502.646a.5.5 0 0 1 .707 0l1.293 1.293z"/>
                                            <path d="M13.752 4.396l-2-2L4.939 9.21a.5.5 0 0 0-.121.196l-.805 2.414a.25.25 0 0 0 .316.316l2.414-.805a.5.5 0 0 0 .196-.12l6.813-6.814z"/>
                                            <path fill-rule="evenodd" d="M1 13.5A1.5 1.5 0 0 0 2.5 15h11a1.5 1.5 0 0 0 1.5-1.5v-6a.5.5 0 0 0-1 0v6a.5.5 0 0 1-.5.5h-11a.5.5 0 0 1-.5-.5v-11a.5.5 0 0 1 .5-.5H9a.5.5 0 0 0 0-1H2.5A1.5 1.5 0 0 0 1 2.5v11z"/>
                                        </svg>
                                    </a>
                                    @if(auth()->id() !== $user->id)
                                        <form method="post" action="{{ route('admin.users.destroy', $user) }}" class="d-inline" onsubmit="return confirm('Are you sure you want to delete this user?');">
                                            @csrf
                                            @method('DELETE')
                                            <button class="btn btn-sm btn-outline-danger" type="submit" title="Delete" aria-label="Delete">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" fill="currentColor" viewBox="0 0 16 16" aria-hidden="true">
                                                    <path d="M5.5 5.5a.5.5 0 0 1 .5.5v7a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5zm2.5.5a.5.5 0 0 0-1 0v7a.5.5 0 0 0 1 0V6zm2 .5a.5.5 0 0 1 .5-.5.5.5 0 0 1 .5.5v7a.5.5 0 0 1-1 0V6z"/>
                                                    <path fill-rule="evenodd" d="M14.5 3a1 1 0 0 1-1 1H13v9a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V4h-.5a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1H6a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1h3.5a1 1 0 0 1 1 1v1zM4.118 4 4 4.059V13a1 1 0 0 0 1 1h6a1 1 0 0 0 1-1V4.059L11.882 4H4.118zM2.5 3V2h11v1h-11z"/>
                                                </svg>
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center py-4 text-muted">No users found.</td>
                        </tr>
                    @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="mt-3">
        {{ $users->links() }}
    </div>
@endsection
