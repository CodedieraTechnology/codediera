@extends('admin.layout')

@section('title', 'IT Intake Form')

@section('content')
    <h1 class="h3 mb-3">IT Intake Form</h1>

    <div class="table-responsive">
        <table class="table table-striped align-middle">
            <thead>
            <tr>
                <th>ID</th>
                <th>Student</th>
                <th>Email</th>
                <th>Matric No.</th>
                <th>Phone</th>
                <th>Status</th>
                <th>Specialization</th>
                <th class="text-end">Actions</th>
            </tr>
            </thead>
            <tbody>
            @forelse($items as $item)
                <tr>
                    <td>{{ $item->id }}</td>
                    <td>{{ $item->student_name }}</td>
                    <td>{{ $item->email }}</td>
                    <td>{{ $item->matriculation_number }}</td>
                    <td>{{ $item->phone_number }}</td>
                    <td>{{ $item->approval_status }}</td>
                    <td>{{ $item->specialization }}</td>
                    <td class="text-end">
                        <a class="btn btn-sm btn-outline-primary" href="{{ route('admin.it-intakes.show', $item) }}">View</a>
                        <form method="post" action="{{ route('admin.it-intakes.destroy', $item) }}" class="d-inline">
                            @csrf
                            @method('DELETE')
                            <button class="btn btn-sm btn-outline-danger" type="submit">Delete</button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="8" class="text-center text-muted">No submissions</td>
                </tr>
            @endforelse
            </tbody>
        </table>
    </div>
@endsection
