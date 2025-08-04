<?php

use Illuminate\Support\Facades\Route;
use App\Livewire\PostList;
use App\Livewire\PostForm;
use App\Livewire\Counter;

Route::get('/', function () {
    return view('welcome');
});
Route::get('/posts', PostList::class)->name('posts.index');
Route::get('/posts/create', PostForm::class)->name('posts.create');
Route::get('/posts/{postId}/edit', PostForm::class)->name('posts.edit');

Route::get('/counter',Counter::class)->name('counter');