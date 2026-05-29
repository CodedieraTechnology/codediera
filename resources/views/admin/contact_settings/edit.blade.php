@extends('admin.layout')

@section('title', 'Contact Settings')

@section('content')
    <h1 class="h3 mb-3">Contact Settings</h1>

    <form method="post" action="{{ route('admin.contact-settings.update') }}" class="card card-body">
        @csrf
        @method('PUT')
        <div class="mb-3">
            <label class="form-label" for="heading">Heading</label>
            <input class="form-control" id="heading" name="heading" type="text" value="{{ old('heading', $settings->heading) }}">
        </div>
        <div class="mb-3">
            <label class="form-label" for="body">Body</label>
            <textarea class="form-control" id="body" name="body" rows="4">{{ old('body', $settings->body) }}</textarea>
        </div>
        <div class="row">
            <div class="col-md-6 mb-3">
                <label class="form-label" for="address">Address</label>
                <input class="form-control" id="address" name="address" type="text" value="{{ old('address', $settings->address) }}">
            </div>
            <div class="col-md-3 mb-3">
                <label class="form-label" for="phone">Phone</label>
                <input class="form-control" id="phone" name="phone" type="text" value="{{ old('phone', $settings->phone) }}">
            </div>
            <div class="col-md-3 mb-3">
                <label class="form-label" for="email">Email</label>
                <input class="form-control" id="email" name="email" type="email" value="{{ old('email', $settings->email) }}">
            </div>
        </div>
        <div class="mb-3">
            <label class="form-label" for="map_embed_url">Map Embed URL (optional)</label>
            <textarea class="form-control" id="map_embed_url" name="map_embed_url" rows="3">{{ old('map_embed_url', $settings->map_embed_url) }}</textarea>
        </div>
        <button class="btn btn-primary" type="submit">Save</button>
    </form>
@endsection

