@extends('admin.layout')

@section('title', 'Edit Role')

@section('content')
    <div class="mb-3">
        <a class="text-decoration-none small d-inline-flex align-items-center gap-1" href="{{ route('admin.roles.index') }}">
            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" fill="currentColor" viewBox="0 0 16 16" aria-hidden="true">
                <path fill-rule="evenodd" d="M15 8a.5.5 0 0 0-.5-.5H2.707l3.147-3.146a.5.5 0 1 0-.708-.708l-4 4a.5.5 0 0 0 0 .708l4 4a.5.5 0 0 0 .708-.708L2.707 8.5H14.5A.5.5 0 0 0 15 8z"/>
            </svg>
            Back to Roles
        </a>
        <h1 class="h3 mb-0 mt-2">Edit Role</h1>
    </div>

    <div class="card">
        <div class="card-body p-4">
            <form method="post" action="{{ route('admin.roles.update', $role) }}">
                @csrf
                @method('PUT')
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label" for="name">Role Name</label>
                        <input class="form-control" id="name" name="name" type="text" value="{{ old('name', $role->name) }}" required {{ $role->slug === 'super_admin' ? 'readonly' : '' }}>
                    </div>

                    <div class="col-md-12">
                        <label class="form-label" for="description">Description</label>
                        <textarea class="form-control" id="description" name="description" rows="3">{{ old('description', $role->description) }}</textarea>
                    </div>

                    <div class="col-12 mt-4">
                        <h2 class="h6 mb-2">Select Permissions</h2>
                        @if($role->slug === 'super_admin')
                            <div class="alert alert-info">
                                The Super Admin role always possesses all system permissions.
                            </div>
                        @else
                            <div class="row">
                                @foreach($permissions as $perm)
                                    <div class="col-md-6 mb-3">
                                        <div class="card border p-3 h-100">
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" name="permissions[]" value="{{ $perm->id }}" id="perm_{{ $perm->id }}" {{ in_array($perm->id, old('permissions', $role->permissions->pluck('id')->toArray())) ? 'checked' : '' }}>
                                                <label class="form-check-label" for="perm_{{ $perm->id }}">
                                                    <span class="fw-semibold d-block">{{ $perm->name }}</span>
                                                    <span class="text-muted small">{{ $perm->description }}</span>
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @endif
                    </div>

                    <div class="col-12 mt-4">
                        <button class="btn btn-primary" type="submit">Update Role</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection
