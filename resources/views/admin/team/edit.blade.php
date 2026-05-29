@extends('admin.layout')

@section('title', 'Edit Team Member')

@section('content')
    <h1 class="h3 mb-3">Edit Team Member</h1>

    <form method="post" action="{{ route('admin.team.update', $member) }}" enctype="multipart/form-data" class="card card-body">
        @method('PUT')
        @include('admin.team.form', ['member' => $member])
        <div class="d-flex gap-2">
            <button class="btn btn-primary" type="submit">Save</button>
            <a class="btn btn-outline-secondary" href="{{ route('admin.team.index') }}">Cancel</a>
        </div>
    </form>
@endsection

