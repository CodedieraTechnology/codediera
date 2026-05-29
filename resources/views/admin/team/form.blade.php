@csrf
<div class="mb-3">
    <label class="form-label" for="name">Name</label>
    <input class="form-control" id="name" name="name" type="text" value="{{ old('name', $member->name ?? '') }}" required>
</div>
<div class="mb-3">
    <label class="form-label" for="role">Role</label>
    <input class="form-control" id="role" name="role" type="text" value="{{ old('role', $member->role ?? '') }}">
</div>
<div class="mb-3">
    <label class="form-label" for="bio">Bio</label>
    <textarea class="form-control" id="bio" name="bio" rows="4">{{ old('bio', $member->bio ?? '') }}</textarea>
</div>
<div class="mb-3">
    <label class="form-label" for="photo">Photo</label>
    <input class="form-control" id="photo" name="photo" type="file" accept="image/*">
    @if(!empty($member?->photo_path))
        <div class="mt-2 d-flex align-items-center gap-3">
            <img src="{{ asset('storage/'.$member->photo_path) }}" alt="" style="height:64px;border-radius:32px">
            <div class="form-check">
                <input class="form-check-input" id="remove_photo" name="remove_photo" type="checkbox" value="1">
                <label class="form-check-label" for="remove_photo">Remove photo</label>
            </div>
        </div>
    @endif
</div>
<div class="row">
    <div class="col-md-4 mb-3">
        <label class="form-label" for="social_facebook">Facebook URL</label>
        <input class="form-control" id="social_facebook" name="social_facebook" type="text" value="{{ old('social_facebook', ($member->social_links['facebook'] ?? '')) }}">
    </div>
    <div class="col-md-4 mb-3">
        <label class="form-label" for="social_twitter">Twitter/X URL</label>
        <input class="form-control" id="social_twitter" name="social_twitter" type="text" value="{{ old('social_twitter', ($member->social_links['twitter'] ?? '')) }}">
    </div>
    <div class="col-md-4 mb-3">
        <label class="form-label" for="social_linkedin">LinkedIn URL</label>
        <input class="form-control" id="social_linkedin" name="social_linkedin" type="text" value="{{ old('social_linkedin', ($member->social_links['linkedin'] ?? '')) }}">
    </div>
</div>
<div class="row">
    <div class="col-md-3 mb-3">
        <label class="form-label" for="sort_order">Sort Order</label>
        <input class="form-control" id="sort_order" name="sort_order" type="number" min="0" value="{{ old('sort_order', $member->sort_order ?? 0) }}">
    </div>
    <div class="col-md-3 mb-3 d-flex align-items-end">
        <div class="form-check">
            <input class="form-check-input" id="is_active" name="is_active" type="checkbox" value="1" {{ old('is_active', ($member->is_active ?? true) ? 1 : 0) ? 'checked' : '' }}>
            <label class="form-check-label" for="is_active">Active</label>
        </div>
    </div>
</div>

