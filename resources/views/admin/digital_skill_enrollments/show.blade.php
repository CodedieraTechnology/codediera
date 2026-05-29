@extends('admin.layout')

@section('title', 'Enrollment #'.$enrollment->id)

@section('content')
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center gap-2 mb-3">
        <div>
            <h1 class="h3 mb-1">Enrollment #{{ $enrollment->id }}</h1>
            <div class="text-muted">{{ $enrollment->item?->title ?: 'Digital Skill' }}</div>
        </div>
        <div class="d-flex gap-2">
            <a class="btn btn-outline-secondary" href="{{ route('admin.digital-skill-enrollments.index') }}">Back</a>
        </div>
    </div>

    <div class="row g-3">
        <div class="col-12 col-lg-7">
            <div class="card">
                <div class="card-body">
                    <div class="fw-semibold mb-2">User details</div>
                    <div class="row g-2 small">
                        <div class="col-12 col-md-6"><span class="text-muted">Name:</span> {{ $enrollment->name }}</div>
                        <div class="col-12 col-md-6"><span class="text-muted">Email:</span> {{ $enrollment->email }}</div>
                        <div class="col-12 col-md-6"><span class="text-muted">Phone:</span> {{ $enrollment->phone ?: '—' }}</div>
                        <div class="col-12 col-md-6"><span class="text-muted">Created:</span> {{ $enrollment->created_at?->format('Y-m-d H:i') }}</div>
                    </div>
                    @if($enrollment->message)
                        <hr>
                        <div class="fw-semibold mb-2">Message</div>
                        <div>{{ $enrollment->message }}</div>
                    @endif
                </div>
            </div>
        </div>

        <div class="col-12 col-lg-5">
            <div class="card">
                <div class="card-body">
                    <div class="fw-semibold mb-2">Status</div>
                    <form method="post" action="{{ route('admin.digital-skill-enrollments.status', $enrollment) }}" class="d-flex gap-2">
                        @csrf
                        @method('PUT')
                        <select class="form-select" name="status">
                            @foreach(['new', 'contacted', 'enrolled', 'closed'] as $st)
                                <option value="{{ $st }}" @selected($enrollment->status === $st)>{{ $st }}</option>
                            @endforeach
                        </select>
                        <button class="btn btn-primary" type="submit">Save</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

