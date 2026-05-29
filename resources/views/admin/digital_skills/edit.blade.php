@extends('admin.layout')

@section('title', 'Edit Digital Skill')

@section('content')
    <h1 class="h3 mb-3">Edit Digital Skill</h1>

    <form method="post" action="{{ route('admin.digital-skills.update', $item) }}" class="card card-body" enctype="multipart/form-data">
        @method('PUT')
        @include('admin.digital_skills.form', ['item' => $item, 'instructors' => $instructors ?? collect()])
        <div class="d-flex gap-2">
            <button class="btn btn-primary" type="submit">Save</button>
            <a class="btn btn-outline-secondary" href="{{ route('admin.digital-skills.index') }}">Cancel</a>
        </div>
    </form>
@endsection
