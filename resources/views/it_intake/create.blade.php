@extends('layouts.public')

@section('title', 'IT Intake Form')

@section('content')
    <div class="container py-4">
        <div class="mb-3">
            <div class="d-flex align-items-center gap-2">
                <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" fill="currentColor" class="text-primary" viewBox="0 0 16 16" aria-hidden="true">
                    <path d="M9.293 0H4.5A1.5 1.5 0 0 0 3 1.5v13A1.5 1.5 0 0 0 4.5 16h7a1.5 1.5 0 0 0 1.5-1.5V4.707a1 1 0 0 0-.293-.707L10 .293A1 1 0 0 0 9.293 0zM10 1.5 11.5 3H10a.5.5 0 0 1-.5-.5V1.5z"/>
                    <path d="M4.5 9a.5.5 0 0 1 .5-.5h6a.5.5 0 0 1 0 1H5a.5.5 0 0 1-.5-.5zm0 2a.5.5 0 0 1 .5-.5h6a.5.5 0 0 1 0 1H5a.5.5 0 0 1-.5-.5z"/>
                </svg>
                <h1 class="h3 section-title mb-1">IT Intake Form</h1>
            </div>
            <div class="text-muted">Fill this form and submit for coordinator review. Our team will notify you upon approval.</div>
        </div>

        <div class="row g-3">
            <div class="col-12 col-lg-7">
                <form method="post" action="{{ route('it-intake.submit') }}" class="card card-body">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label" for="student_name">Name of Student</label>
                        <div class="input-group">
                            <span class="input-group-text">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="text-primary" viewBox="0 0 16 16" aria-hidden="true">
                                    <path d="M8 8a3 3 0 1 0 0-6 3 3 0 0 0 0 6z"/>
                                    <path d="M14 14s-1-4-6-4-6 4-6 4 1 2 6 2 6-2 6-2z"/>
                                </svg>
                            </span>
                            <input class="form-control @error('student_name') is-invalid @enderror" id="student_name" name="student_name" type="text" value="{{ old('student_name') }}" placeholder="e.g. John Doe" required>
                        </div>
                        @error('student_name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label" for="email">Email</label>
                        <div class="input-group">
                            <span class="input-group-text">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="text-primary" viewBox="0 0 16 16" aria-hidden="true">
                                    <path d="M0 4a2 2 0 0 1 2-2h12a2 2 0 0 1 2 2v.217L8 8.383.002 4.217A2 2 0 0 1 0 4z"/>
                                    <path d="M0 4.697v7.104l5.803-3.558L0 4.697z"/>
                                    <path d="M6.761 8.83 0 12.97A2 2 0 0 0 2 14h12a2 2 0 0 0 2-1.03L9.239 8.83 8 9.586 6.761 8.83z"/>
                                    <path d="M16 4.697v7.104l-5.803-3.558L16 4.697z"/>
                                </svg>
                            </span>
                            <input class="form-control @error('email') is-invalid @enderror" id="email" name="email" type="email" value="{{ old('email') }}" placeholder="e.g. student@example.com" required>
                        </div>
                        @error('email')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label" for="phone_number">Phone Number</label>
                        <div class="input-group">
                            <span class="input-group-text">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="text-primary" viewBox="0 0 16 16" aria-hidden="true">
                                    <path d="M3.654 1.328a.678.678 0 0 1 .737-.168l2.522 1.01c.329.132.493.502.375.842l-.805 2.324a.678.678 0 0 1-.588.458l-1.11.093a11.745 11.745 0 0 0 5.516 5.516l.093-1.11a.678.678 0 0 1 .458-.588l2.324-.805c.34-.118.71.046.842.375l1.01 2.522a.678.678 0 0 1-.168.737l-1.272 1.272c-.327.327-.798.45-1.247.324-2.445-.68-4.823-2.477-6.95-4.604C3.104 8.11 1.307 5.732.627 3.287.5 2.838.623 2.367.95 2.04L2.222.768z"/>
                                </svg>
                            </span>
                            <input class="form-control @error('phone_number') is-invalid @enderror" id="phone_number" name="phone_number" type="text" value="{{ old('phone_number') }}" placeholder="e.g. 08012345678">
                        </div>
                        @error('phone_number')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label" for="matriculation_number">Matriculation Number</label>
                        <div class="input-group">
                            <span class="input-group-text">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="text-primary" viewBox="0 0 16 16" aria-hidden="true">
                                    <path d="M8 0a4 4 0 0 1 4 4v1h1a1 1 0 0 1 1 1v7a3 3 0 0 1-3 3H5a3 3 0 0 1-3-3V6a1 1 0 0 1 1-1h1V4a4 4 0 0 1 4-4zm3 5V4a3 3 0 0 0-6 0v1h6z"/>
                                </svg>
                            </span>
                            <input class="form-control @error('matriculation_number') is-invalid @enderror" id="matriculation_number" name="matriculation_number" type="text" value="{{ old('matriculation_number') }}" placeholder="e.g. CSC/2019/1234" required>
                        </div>
                        @error('matriculation_number')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label" for="institution">Institution</label>
                            <input class="form-control" id="institution" type="text" value="IMSU" readonly>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label" for="department">Department</label>
                            <input class="form-control" id="department" type="text" value="Computer Science" readonly>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label" for="level">Level</label>
                            <input class="form-control" id="level" type="text" value="400 Level" readonly>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label" for="place_of_it">Place of Industrial Training (I.T)</label>
                        <input class="form-control" id="place_of_it" type="text" value="Codediera Technologies LTD" readonly>
                    </div>

                    <div class="mb-3">
                        <label class="form-label" for="specialization">Nature of Training / Area of Specialization</label>
                        <div class="input-group">
                            <span class="input-group-text">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="text-primary" viewBox="0 0 16 16" aria-hidden="true">
                                    <path d="M9.5 2a.5.5 0 0 1 .5.5V5h2.5a.5.5 0 0 1 .5.5v1a.5.5 0 0 1-.5.5H10v2.5a.5.5 0 0 1-.5.5h-1a.5.5 0 0 1-.5-.5V7H5.5a.5.5 0 0 1-.5-.5v-1a.5.5 0 0 1 .5-.5H8V2.5a.5.5 0 0 1 .5-.5h1z"/>
                                    <path d="M4 0h8a2 2 0 0 1 2 2v12a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V2a2 2 0 0 1 2-2zm8 1H4a1 1 0 0 0-1 1v12a1 1 0 0 0 1 1h8a1 1 0 0 0 1-1V2a1 1 0 0 0-1-1z"/>
                                </svg>
                            </span>
                            <select class="form-select @error('specialization') is-invalid @enderror" id="specialization" name="specialization" required>
                            <option value="" selected disabled>Select an option</option>
                            @foreach (['Web Design', 'Mobile Development', 'Digital Marketing', 'UI/UX Design', 'Frontend Development', 'Backend Development', 'Graphics Design', 'Data Analysis'] as $opt)
                                <option value="{{ $opt }}" {{ old('specialization') === $opt ? 'selected' : '' }}>{{ $opt }}</option>
                            @endforeach
                        </select>
                        </div>
                        @error('specialization')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <div class="text-muted small mt-1">Example: Web Design, Mobile Development, Digital Marketing, and more.</div>
                    </div>

                    <div class="d-flex gap-2">
                        <button class="btn btn-primary" type="submit">Submit</button>
                        <a class="btn btn-outline-secondary" href="{{ route('home') }}">Back</a>
                    </div>
                </form>
            </div>
            <div class="col-12 col-lg-5">
                <div class="card card-body">
                    <div class="d-flex align-items-center gap-2 mb-2">
                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="currentColor" class="text-primary" viewBox="0 0 16 16" aria-hidden="true">
                            <path d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0zM6.97 11.03a.75.75 0 0 0 1.08.02l3.992-3.99a.75.75 0 1 0-1.06-1.061L7.5 9.439 5.53 7.47a.75.75 0 0 0-1.06 1.06l2.5 2.5z"/>
                        </svg>
                        <div class="h5 mb-0">Approval</div>
                    </div>
                    <div class="text-muted">
                        Admin will decide upon confirmation of payment. Contact your course rep. Our team will notify you upon approval.
                    </div>
                    <hr>
                    <div class="small text-muted">
                        Approval Status: Approved / Not Approved (Coordinator)
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
