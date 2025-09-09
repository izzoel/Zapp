<?php

use App\Livewire\Backend\Icon;
use App\Livewire\Backend\Menu;
use App\Livewire\Backend\Admin;
use App\Http\Controllers\LandingController;
use Illuminate\Support\Facades\Route;

Route::any('/', [LandingController::class, 'landing'])->name('landing');
Route::any('/login', [LandingController::class, 'login'])->name('login');

// Route::middleware('auth', 'role:1')->group(function () {
Route::get('/menu', \App\Livewire\Backend\Menu::class);
Route::get('/akses', \App\Livewire\Backend\Akses::class);
Route::get('/user', \App\Livewire\Backend\User::class);
Route::get('/role', \App\Livewire\Backend\Role::class);
Route::get('/admin', Admin::class);
Route::get('/icon', Icon::class);
// });
