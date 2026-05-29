@extends('admin.layout')

@section('title', 'IT Intake')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h1 class="h3 mb-0">IT Intake #{{ $intake->id }}</h1>
        <a class="btn btn-outline-secondary" href="{{ route('admin.it-intakes.index') }}">Back</a>
    </div>

    <div class="card card-body mb-3">
        <div class="row g-2">
            <div class="col-md-6">
                <div><strong>Name of Student:</strong> {{ $intake->student_name }}</div>
                <div><strong>Email:</strong> {{ $intake->email }}</div>
                <div><strong>Phone Number:</strong> {{ $intake->phone_number }}</div>
                <div><strong>Matriculation Number:</strong> {{ $intake->matriculation_number }}</div>
                <div><strong>Institution:</strong> {{ $intake->institution }}</div>
            </div>
            <div class="col-md-6">
                <div><strong>Department:</strong> {{ $intake->department }}</div>
                <div><strong>Level:</strong> {{ $intake->level }}</div>
                <div><strong>Place of I.T:</strong> {{ $intake->place_of_it }}</div>
            </div>
        </div>
    </div>

    <form method="post" action="{{ route('admin.it-intakes.update', $intake) }}" class="card card-body">
        @csrf
        @method('PUT')

        <div class="row">
            <div class="col-md-4 mb-3">
                <label class="form-label" for="approval_status">Approval Status</label>
                <select class="form-select" id="approval_status" name="approval_status">
                    @foreach (['pending', 'approved', 'not_approved'] as $status)
                        <option value="{{ $status }}" {{ $intake->approval_status === $status ? 'selected' : '' }}>{{ strtoupper(str_replace('_', ' ', $status)) }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-8 mb-3">
                <label class="form-label" for="specialization">Nature of Training / Area of Specialization</label>
                <input class="form-control" id="specialization" name="specialization" type="text" value="{{ old('specialization', $intake->specialization) }}" placeholder="e.g. Web Design, Mobile Development, Digital Marketing">
            </div>
        </div>

        <div class="row">
            <div class="col-md-6 mb-3">
                <label class="form-label" for="coordinator_signature">Coordinator Signature</label>
                <input class="form-control" id="coordinator_signature" name="coordinator_signature" type="text" value="{{ old('coordinator_signature', $intake->coordinator_signature) }}">
            </div>
            <div class="col-md-6 mb-3">
                <label class="form-label" for="coordinator_date">Date</label>
                <input class="form-control" id="coordinator_date" name="coordinator_date" type="date" value="{{ old('coordinator_date', ($intake->coordinator_date ?? null) ? $intake->coordinator_date->format('Y-m-d') : '') }}">
            </div>
        </div>

        <button class="btn btn-primary" type="submit">Save</button>
    </form>
@endsection
