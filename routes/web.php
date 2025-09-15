<?php

use App\Livewire\Backend\Akses;
use App\Livewire\Backend\Menu;
use App\Livewire\Backend\User;
use App\Livewire\Backend\Role;
use App\Livewire\Backend\Icon;
use App\Livewire\Backend\Admin;
use App\Http\Controllers\LandingController;
use Illuminate\Support\Facades\Route;

Route::any('/', [LandingController::class, 'landing'])->name('landing');
Route::any('/login', [LandingController::class, 'login'])->name('login');
Route::any('/logout', [LandingController::class, 'logout'])->name('logout');

Route::get('/akses', Akses::class)->middleware('r');
Route::get('/menu', Menu::class)->middleware('r');
Route::get('/user', User::class)->middleware('r');
Route::get('/role', Role::class)->middleware('r');
Route::get('/admin', Admin::class);
Route::get('/icon', Icon::class);
