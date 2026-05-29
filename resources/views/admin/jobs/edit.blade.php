@extends('admin.layout')

@section('title', 'Edit Job')

@section('content')
    <h1 class="h3 mb-3">Edit Job</h1>

    <form method="post" action="{{ route('admin.jobs.update', $job) }}" class="card card-body">
        @method('PUT')
        @include('admin.jobs.form', ['job' => $job])
        <div class="d-flex gap-2">
            <button class="btn btn-primary" type="submit">Save</button>
            <a class="btn btn-outline-secondary" href="{{ route('admin.jobs.index') }}">Cancel</a>
        </div>
    </form>
@endsection

