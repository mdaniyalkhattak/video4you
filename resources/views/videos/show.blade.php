@extends('layouts.app')
@section('content')
<h2>{{ $video->title }}</h2>

<video controls class="w-100 mb-3">
    <source src="{{ asset('storage/'.$video->file_path) }}" type="video/mp4">
</video>

<p>{{ $video->description }}</p>
<p>Uploaded by: {{ $video->user->name }}</p>

<h4>Comments</h4>
@foreach($video->comments as $comment)
    <div class="border p-2 mb-1">
        <strong>{{ $comment->user->name }}:</strong> {{ $comment->comment }}
    </div>
@endforeach

@auth
<form method="POST" action="{{ route('comments.store') }}">
    @csrf
    <input type="hidden" name="video_id" value="{{ $video->id }}">
    <div class="mb-3">
        <textarea class="form-control" name="comment" placeholder="Add comment" required></textarea>
    </div>
    <button class="btn btn-primary">Comment</button>
</form>
@endauth
@endsection
