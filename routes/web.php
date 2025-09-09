<?php

use App\Http\Controllers\BmiRecordController;
use App\Http\Controllers\ClientController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    $user = Auth::user();
    return view('welcome')->with('user', $user);
})->name('home');

Route::get('/acerca', function () {
    return view('acerca');
})->name('acerca');

Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', [ClientController::class, 'dashboard'])->name('dashboard');
    Route::resource('clients', ClientController::class)->only(['edit', 'update']);
    Route::get('bmi', [BmiRecordController::class, 'index'])->name('bmi.index');
    Route::post('bmi', [BmiRecordController::class, 'store'])->name('bmi.store');
});

