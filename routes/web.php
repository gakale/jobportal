<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\JobApplicationController;

Route::get('/', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

Route::get('job-offer', [App\Http\Controllers\HomeController::class, 'list'])->name('job-offer');
Route::get('show/{id}', [App\Http\Controllers\HomeController::class, 'show'])->name('show');

Route::post('/apply/{job}', [JobApplicationController::class, 'apply'])->name('apply');
