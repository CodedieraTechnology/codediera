@extends('admin.layout')

@section('title', 'Edit Service')

@section('content')
    <h1 class="h3 mb-3">Edit Service</h1>

    <form method="post" action="{{ route('admin.services.update', $service) }}" class="card card-body" enctype="multipart/form-data">
        @method('PUT')
        @include('admin.services.form', ['service' => $service])
        <div class="d-flex gap-2">
            <button class="btn btn-primary" type="submit">Save</button>
            <a class="btn btn-outline-secondary" href="{{ route('admin.services.index') }}">Cancel</a>
        </div>
    </form>
@endsection
