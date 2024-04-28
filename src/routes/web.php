<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\UserController;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\WorkInProgressController;
use App\Http\Controllers\SetController;

Route::get('/', function () {
    return view('welcome');
});
Route::get('/DBTest', [WorkInProgressController::class, 'index']);

Route::post('/DBTest', [SetController::class, 'prepareFile']);

Route::get('/show', [SetController::class, 'prepareFile']);


/*Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::get('/showRead' , [WorkInProgressController::class, 'convertFile']);

Route::get('/showRead' , [UserController::class, 'index']);*/

require __DIR__.'/auth.php';
