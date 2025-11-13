@extends('layouts.app')

@section('content')
<h2>Videos</h2>

<form method="GET" class="mb-3">
    <input type="text" name="search" class="form-control" placeholder="Search by title or uploader" value="{{ request('search') }}">
</form>

@auth
<a href="{{ route('videos.create') }}" class="btn btn-primary mb-3">Upload New Video</a>
@endauth

@if($videos->isEmpty())
    <p class="text-center mt-3">No videos found. Upload one!</p>
@endif

<div class="row">
@foreach($videos as $video)
    <div class="col-md-4 mb-4">
        <div class="card">
            <video controls class="card-img-top">
                <source src="{{ asset('storage/' . $video->file_path) }}" type="video/mp4">
            </video>
            <div class="card-body">
                <h5>{{ $video->title }}</h5>
                <p>{{ $video->description }}</p>
                <p><small>Uploaded by: {{ $video->user->name }}</small></p>
                <a href="{{ route('videos.show', $video->id) }}" class="btn btn-outline-primary btn-sm">View</a>
                @can('update', $video)
                    <a href="{{ route('videos.edit', $video->id) }}" class="btn btn-outline-warning btn-sm">Edit</a>
                @endcan
                @can('delete', $video)
                    <form method="POST" action="{{ route('videos.destroy', $video->id) }}" class="d-inline">
                        @csrf
                        @method('DELETE')
                        <button class="btn btn-outline-danger btn-sm">Delete</button>
                    </form>
                @endcan
            </div>
        </div>
    </div>
@endforeach
</div>
@endsection
