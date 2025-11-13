<?php

use App\Http\Controllers\VideoController;
use App\Http\Controllers\CommentController;

Route::get('/', [VideoController::class,'index'])->name('videos.index');
Route::resource('videos', VideoController::class)->except(['index']);
Route::post('comments',[CommentController::class,'store'])->name('comments.store');

require __DIR__.'/auth.php'; // login/register

