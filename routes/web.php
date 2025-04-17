<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProjectController;
use Illuminate\Support\Facades\Auth;

// Halaman welcome
Route::get('/', function () {
    return view('welcome');
});

// Autentikasi
Auth::routes();

// After login, redirect to HomeController@index
Route::get('/home', [HomeController::class, 'index'])->name('home');

// Dashboard (harus login)
Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware('auth')->name('dashboard');

// Resource routes untuk ProjectController
// index & show = publik
// create/store/edit/update/destroy = membutuhkan auth (karena middleware di controller)
Route::resource('projects', ProjectController::class);
