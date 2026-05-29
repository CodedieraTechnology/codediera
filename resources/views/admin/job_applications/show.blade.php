@extends('admin.layout')

@section('title', 'View Job Application')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h1 class="h3 mb-0">Application #{{ $application->id }}</h1>
        <a class="btn btn-outline-secondary" href="{{ route('admin.job-applications.index') }}">Back</a>
    </div>

    <div class="card card-body mb-3">
        <div class="row">
            <div class="col-md-6">
                <div><strong>Name:</strong> {{ $application->full_name }}</div>
                <div><strong>Email:</strong> {{ $application->email }}</div>
                <div><strong>Phone:</strong> {{ $application->phone }}</div>
                <div><strong>Job:</strong> {{ $application->vacancy?->title ?? $application->position }}</div>
            </div>
            <div class="col-md-6">
                <div><strong>Status:</strong> {{ $application->status }}</div>
                <div><strong>Created:</strong> {{ $application->created_at }}</div>
                <div>
                    <strong>CV:</strong>
                    @if($application->cv_path)
                        <a href="{{ route('admin.job-applications.cv', $application) }}">Download</a>
                    @else
                        <span class="text-muted">None</span>
                    @endif
                </div>
            </div>
        </div>
        @if($application->message)
            <hr>
            <div style="white-space: pre-wrap">{{ $application->message }}</div>
        @endif
    </div>

    <form method="post" action="{{ route('admin.job-applications.status', $application) }}" class="card card-body">
        @csrf
        @method('PUT')
        <div class="row align-items-end">
            <div class="col-md-4">
                <label class="form-label" for="status">Status</label>
                <select class="form-select" id="status" name="status">
                    @foreach (['new', 'reviewing', 'shortlisted', 'rejected', 'hired'] as $status)
                        <option value="{{ $status }}" {{ $application->status === $status ? 'selected' : '' }}>{{ $status }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-4">
                <button class="btn btn-primary" type="submit">Update Status</button>
            </div>
        </div>
    </form>
@endsection
