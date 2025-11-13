<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Video;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class VideoController extends Controller
{
    public function __construct()
    {
        // Only authenticated users can create/edit/delete
        $this->middleware('auth')->except(['index', 'show']);
    }

    /**
     * Display a listing of videos.
     */
    public function index(Request $request)
    {
        $query = Video::with('user')->latest();

        if ($request->search) {
            $search = $request->search;
            $query->whereHas('user', fn($q) => $q->where('name', 'like', "%$search%"))
                  ->orWhere('title', 'like', "%$search%");
        }

        $videos = $query->get();

        return view('videos.index', compact('videos'));
    }

    /**
     * Show the form for creating a new video.
     */
    public function create()
    {
        return view('videos.create');
    }

    /**
     * Store a newly uploaded video.
     */
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'video' => 'required|mimes:mp4|max:51200', // max 50MB
        ]);

        $filePath = $request->file('video')->store('videos', 'public');

        Video::create([
            'title' => $request->title,
            'description' => $request->description,
            'file_path' => $filePath,
            'user_id' => Auth::id(),
        ]);

        return redirect()->route('videos.index')->with('success', 'Video uploaded successfully!');
    }

    /**
     * Display the specified video.
     */
    public function show(Video $video)
    {
        $video->load('comments.user'); // Load comments with user
        return view('videos.show', compact('video'));
    }

    /**
     * Show the form for editing the specified video.
     */
    public function edit(Video $video)
    {
        $this->authorize('update', $video);
        return view('videos.edit', compact('video'));
    }

    /**
     * Update the specified video.
     */
    public function update(Request $request, Video $video)
    {
        $this->authorize('update', $video);

        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        $video->update($request->only(['title', 'description']));

        return redirect()->route('videos.index')->with('success', 'Video updated successfully!');
    }

    /**
     * Remove the specified video.
     */
    public function destroy(Video $video)
    {
        $this->authorize('delete', $video);

        // Delete video file from storage
        if (Storage::disk('public')->exists($video->file_path)) {
            Storage::disk('public')->delete($video->file_path);
        }

        $video->delete();

        return redirect()->route('videos.index')->with('success', 'Video deleted successfully!');
    }
}
