<?php

use App\Http\Controllers\TrainerController;

Route::get('/trainer/login', [TrainerController::class, 'showLoginForm'])->name('trainer.login');
Route::post('/trainer/login', [TrainerController::class, 'login']);

// ...existing code...
