@extends('admin.layout')

@section('title', 'View Contact Message')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h1 class="h3 mb-0">Message #{{ $message->id }}</h1>
        <a class="btn btn-outline-secondary" href="{{ route('admin.contact-messages.index') }}">Back</a>
    </div>

    <div class="card card-body mb-3">
        <div class="row">
            <div class="col-md-6">
                <div><strong>Name:</strong> {{ $message->name }}</div>
                <div><strong>Email:</strong> {{ $message->email }}</div>
                <div><strong>Subject:</strong> {{ $message->subject }}</div>
            </div>
            <div class="col-md-6">
                <div><strong>Status:</strong> {{ $message->status }}</div>
                <div><strong>Created:</strong> {{ $message->created_at }}</div>
            </div>
        </div>
        <hr>
        <div style="white-space: pre-wrap">{{ $message->message }}</div>
    </div>

    <form method="post" action="{{ route('admin.contact-messages.status', $message) }}" class="card card-body mb-3">
        @csrf
        @method('PUT')
        <div class="row align-items-end">
            <div class="col-md-4">
                <label class="form-label" for="status">Status</label>
                <select class="form-select" id="status" name="status">
                    @foreach (['new', 'read', 'archived'] as $status)
                        <option value="{{ $status }}" {{ $message->status === $status ? 'selected' : '' }}>{{ $status }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-4">
                <button class="btn btn-primary" type="submit">Update Status</button>
            </div>
        </div>
    </form>
@endsection

