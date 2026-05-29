@extends('admin.layout')

@section('title', 'Digital Skill Enrollments')

@section('content')
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center gap-2 mb-3">
        <div>
            <h1 class="h3 mb-1">Digital Skill Enrollments</h1>
            <div class="text-muted">Users who enrolled on skills</div>
        </div>
    </div>

    <div class="card mb-3">
        <div class="card-body">
            <form method="get" class="row g-2 align-items-end">
                <div class="col-12 col-lg-6">
                    <label class="form-label" for="q">Search</label>
                    <input class="form-control" id="q" name="q" value="{{ request('q') }}" placeholder="Name, email, skill">
                </div>
                <div class="col-12 col-sm-6 col-lg-3">
                    <label class="form-label" for="status">Status</label>
                    <select class="form-select" id="status" name="status">
                        <option value="">All</option>
                        @foreach(['new' => 'New', 'contacted' => 'Contacted', 'enrolled' => 'Enrolled', 'closed' => 'Closed'] as $k => $v)
                            <option value="{{ $k }}" @selected(request('status') === $k)>{{ $v }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-12 col-sm-6 col-lg-3 d-flex gap-2">
                    <button class="btn btn-primary w-100" type="submit">Filter</button>
                    <a class="btn btn-outline-secondary w-100" href="{{ route('admin.digital-skill-enrollments.index') }}">Reset</a>
                </div>
            </form>
        </div>
    </div>

    <div class="card">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-striped align-middle mb-0">
                    <thead>
                    <tr>
                        <th>ID</th>
                        <th>Skill</th>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Status</th>
                        <th>Created</th>
                        <th class="text-end">Action</th>
                    </tr>
                    </thead>
                    <tbody>
                    @forelse($enrollments as $e)
                        <tr>
                            <td>{{ $e->id }}</td>
                            <td>{{ $e->item?->title ?: '—' }}</td>
                            <td>{{ $e->name }}</td>
                            <td>{{ $e->email }}</td>
                            <td><span class="badge text-bg-secondary">{{ $e->status }}</span></td>
                            <td class="text-muted small">{{ $e->created_at?->format('Y-m-d H:i') }}</td>
                            <td class="text-end">
                                <a class="btn btn-sm btn-outline-primary" href="{{ route('admin.digital-skill-enrollments.show', $e) }}">View</a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center text-muted py-4">No enrollments yet.</td>
                        </tr>
                    @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        @if(method_exists($enrollments, 'links'))
            <div class="card-body border-top">
                {{ $enrollments->links() }}
            </div>
        @endif
    </div>
@endsection

