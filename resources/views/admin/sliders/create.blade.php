@extends('admin.layout')

@section('title', 'Add Slider Item')

@section('content')
    <h1 class="h3 mb-3">Add Slider Item</h1>

    <form method="post" action="{{ route('admin.sliders.store') }}" enctype="multipart/form-data" class="card card-body">
        @include('admin.sliders.form')
        <div class="d-flex gap-2">
            <button class="btn btn-primary" type="submit">Save</button>
            <a class="btn btn-outline-secondary" href="{{ route('admin.sliders.index') }}">Cancel</a>
        </div>
    </form>
@endsection

