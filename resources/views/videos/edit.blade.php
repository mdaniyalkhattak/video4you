@extends('layouts.app')
@section('content')
<h2>Edit Video</h2>

<form method="POST" action="{{ route('videos.update', $video->id) }}">
    @csrf
    @method('PUT')
    <div class="mb-3">
        <label for="title" class="form-label">Title</label>
        <input type="text" name="title" class="form-control" value="{{ $video->title }}" required>
    </div>

    <div class="mb-3">
        <label for="description" class="form-label">Description</label>
        <textarea name="description" class="form-control">{{ $video->description }}</textarea>
    </div>

    <button class="btn btn-warning">Update</button>
</form>
@endsection
