<?php

use Illuminate\Support\Facades\Route;
use Bambamboole\LaravelCms\Http\Controllers\ProfileController;
use Bambamboole\LaravelCms\Http\Controllers\MenusController;
use Bambamboole\LaravelCms\Http\Controllers\PagesController;
use Bambamboole\LaravelCms\Http\Controllers\PostsController;
use Bambamboole\LaravelCms\Http\Controllers\UsersController;

Route::get('/posts', [PostsController::class, 'index'])->name('posts.index');

Route::get('/pages', [PagesController::class, 'index'])->name('pages.index');

Route::get('/users', [UsersController::class, 'index'])->name('users.index');
Route::get('/users/create', [UsersController::class, 'create'])->name('users.create');
Route::get('/users/{user}', [UsersController::class, 'show'])->name('users.show');
Route::get('/users/{user}/edit', [UsersController::class, 'edit'])->name('users.edit');

Route::get('/menus', [MenusController::class, 'index'])->name('menus.index');

Route::get('/profile', [ProfileController::class, 'show'])->name('profile.show');
Route::get('/profile/edit', [ProfileController::class, 'edit'])->name('profile.edit');
