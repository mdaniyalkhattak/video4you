@extends('layouts.app')
@section('content')
<h2>Upload Video</h2>

<form method="POST" action="{{ route('videos.store') }}" enctype="multipart/form-data">
    @csrf
    <div class="mb-3">
        <label for="title" class="form-label">Title</label>
        <input type="text" name="title" class="form-control" required>
    </div>

    <div class="mb-3">
        <label for="description" class="form-label">Description</label>
        <textarea name="description" class="form-control"></textarea>
    </div>

    <div class="mb-3">
        <label for="video" class="form-label">Video File (mp4)</label>
        <input type="file" name="video" class="form-control" accept="video/mp4" required>
    </div>

    <button class="btn btn-success">Upload</button>
</form>
@endsection
