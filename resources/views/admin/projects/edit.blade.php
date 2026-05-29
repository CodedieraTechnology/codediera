@extends('admin.layout')

@section('title', 'Edit Project')

@section('content')
    <h1 class="h3 mb-3">Edit Project</h1>

    <form method="post" action="{{ route('admin.projects.update', $project) }}" enctype="multipart/form-data" class="card card-body">
        @method('PUT')
        @include('admin.projects.form', ['project' => $project])
        <div class="d-flex gap-2">
            <button class="btn btn-primary" type="submit">Save</button>
            <a class="btn btn-outline-secondary" href="{{ route('admin.projects.index') }}">Cancel</a>
        </div>
    </form>
@endsection

