@extends('admin.layout')

@section('title', 'Add Job')

@section('content')
    <h1 class="h3 mb-3">Add Job</h1>

    <form method="post" action="{{ route('admin.jobs.store') }}" class="card card-body">
        @include('admin.jobs.form')
        <div class="d-flex gap-2">
            <button class="btn btn-primary" type="submit">Save</button>
            <a class="btn btn-outline-secondary" href="{{ route('admin.jobs.index') }}">Cancel</a>
        </div>
    </form>
@endsection

