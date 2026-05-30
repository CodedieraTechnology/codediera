@extends('admin.layout')

@section('title', 'Edit User')

@section('content')
    <div class="mb-3">
        <a class="text-decoration-none small d-inline-flex align-items-center gap-1" href="{{ route('admin.users.index') }}">
            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" fill="currentColor" viewBox="0 0 16 16" aria-hidden="true">
                <path fill-rule="evenodd" d="M15 8a.5.5 0 0 0-.5-.5H2.707l3.147-3.146a.5.5 0 1 0-.708-.708l-4 4a.5.5 0 0 0 0 .708l4 4a.5.5 0 0 0 .708-.708L2.707 8.5H14.5A.5.5 0 0 0 15 8z"/>
            </svg>
            Back to Users
        </a>
        <h1 class="h3 mb-0 mt-2">Edit User</h1>
    </div>

    <div class="card">
        <div class="card-body p-4">
            <form method="post" action="{{ route('admin.users.update', $user) }}">
                @csrf
                @method('PUT')
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label" for="name">Name</label>
                        <input class="form-control" id="name" name="name" type="text" value="{{ old('name', $user->name) }}" required>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label" for="email">Email</label>
                        <input class="form-control" id="email" name="email" type="email" value="{{ old('email', $user->email) }}" required>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label" for="password">Password <span class="text-muted small">(leave blank to keep current)</span></label>
                        <input class="form-control" id="password" name="password" type="password">
                    </div>

                    <div class="col-md-6">
                        <label class="form-label" for="password_confirmation">Confirm Password</label>
                        <input class="form-control" id="password_confirmation" name="password_confirmation" type="password">
                    </div>

                    <div class="col-md-6">
                        <div class="form-check mt-4">
                            <input class="form-check-input" id="is_admin" name="is_admin" type="checkbox" value="1" {{ old('is_admin', $user->is_admin) ? 'checked' : '' }}>
                            <label class="form-check-label" for="is_admin">
                                Admin User (Can sign in to Control Panel)
                            </label>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-check mt-4">
                            <input class="form-check-input" id="is_instructor" name="is_instructor" type="checkbox" value="1" {{ old('is_instructor', $user->is_instructor) ? 'checked' : '' }}>
                            <label class="form-check-label" for="is_instructor">
                                Instructor (Can manage assigned courses)
                            </label>
                        </div>
                    </div>

                    <div class="col-12 mt-4">
                        <h2 class="h6 mb-2">Assign Roles</h2>
                        <div class="row">
                            @foreach($roles as $role)
                                <div class="col-md-4 mb-2">
                                    <div class="card border p-3 h-100">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" name="roles[]" value="{{ $role->id }}" id="role_{{ $role->id }}" {{ in_array($role->id, old('roles', $user->roles->pluck('id')->toArray())) ? 'checked' : '' }}>
                                            <label class="form-check-label" for="role_{{ $role->id }}">
                                                <span class="fw-semibold d-block">{{ $role->name }}</span>
                                                <span class="text-muted small">{{ $role->description }}</span>
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <div class="col-12 mt-4">
                        <button class="btn btn-primary" type="submit">Update User</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection
