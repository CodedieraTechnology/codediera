@extends('admin.layout')

@section('title', 'Edit CTA')

@section('content')
    <h1 class="h3 mb-3">Edit CTA: <code>{{ $cta->slug }}</code></h1>

    <form method="post" action="{{ route('admin.ctas.update', $cta) }}" class="card card-body">
        @csrf
        @method('PUT')
        <div class="mb-3">
            <label class="form-label" for="heading">Heading</label>
            <input class="form-control" id="heading" name="heading" type="text" value="{{ old('heading', $cta->heading) }}">
        </div>
        <div class="mb-3">
            <label class="form-label" for="body">Body</label>
            <textarea class="form-control" id="body" name="body" rows="4">{{ old('body', $cta->body) }}</textarea>
        </div>
        <div class="row">
            <div class="col-md-6 mb-3">
                <label class="form-label" for="button_text">Button Text</label>
                <input class="form-control" id="button_text" name="button_text" type="text" value="{{ old('button_text', $cta->button_text) }}">
            </div>
            <div class="col-md-6 mb-3">
                <label class="form-label" for="button_url">Button URL</label>
                <input class="form-control" id="button_url" name="button_url" type="text" value="{{ old('button_url', $cta->button_url) }}">
            </div>
        </div>
        <div class="mb-3 form-check">
            <input class="form-check-input" id="is_active" name="is_active" type="checkbox" value="1" {{ old('is_active', $cta->is_active ? 1 : 0) ? 'checked' : '' }}>
            <label class="form-check-label" for="is_active">Active</label>
        </div>
        <div class="d-flex gap-2">
            <button class="btn btn-primary" type="submit">Save</button>
            <a class="btn btn-outline-secondary" href="{{ route('admin.ctas.index') }}">Back</a>
        </div>
    </form>
@endsection

