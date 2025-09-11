<?php

use App\Livewire\Backend\Icon;
use App\Livewire\Backend\Menu;
use App\Livewire\Backend\Admin;
use App\Http\Controllers\LandingController;
use Illuminate\Support\Facades\Route;

Route::any('/', [LandingController::class, 'landing'])->name('landing');
Route::any('/login', [LandingController::class, 'login'])->name('login');
Route::any('/logout', [LandingController::class, 'logout'])->name('logout');

Route::get('/akses', \App\Livewire\Backend\Akses::class)->middleware('r');
Route::get('/menu', \App\Livewire\Backend\Menu::class)->middleware('r');
Route::get('/user', \App\Livewire\Backend\User::class)->middleware('r');
Route::get('/role', \App\Livewire\Backend\Role::class)->middleware('r');
Route::get('/admin', \App\Livewire\Backend\Admin::class);
Route::get('/icon', \App\Livewire\Backend\Icon::class);
