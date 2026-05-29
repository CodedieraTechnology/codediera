@extends('admin.layout')

@section('title', 'Add Project')

@section('content')
    <h1 class="h3 mb-3">Add Project</h1>

    <form method="post" action="{{ route('admin.projects.store') }}" enctype="multipart/form-data" class="card card-body">
        @include('admin.projects.form')
        <div class="d-flex gap-2">
            <button class="btn btn-primary" type="submit">Save</button>
            <a class="btn btn-outline-secondary" href="{{ route('admin.projects.index') }}">Cancel</a>
        </div>
    </form>
@endsection

