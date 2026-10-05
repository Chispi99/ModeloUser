<?php

use App\Http\Controllers\Api\UserController;
use Illuminate\Support\Facades\Route;

Route::prefix('users')->group(function () {
    // GET /api/users - Paginated list of first 10 users
    Route::get('/', [UserController::class, 'index'])->name('users.index');

    // POST /api/users - Create a new user
    Route::post('/', [UserController::class, 'store'])->name('users.store');

    // POST /api/users/login - Login with email and password
    Route::post('/login', [UserController::class, 'login'])->name('users.login');

    // PATCH /api/users/update-name - Update username (name) using email and password
    Route::patch('/update-name', [UserController::class, 'updateName'])->name('users.update-name');

    // PATCH /api/users/update-email - Update email using email and password
    Route::patch('/update-email', [UserController::class, 'updateEmail'])->name('users.update-email');

    // PATCH /api/users/update-password - Update password using email and password
    Route::patch('/update-password', [UserController::class, 'updatePassword'])->name('users.update-password');

    // DELETE /api/users - Delete a user using email and password
    Route::delete('/', [UserController::class, 'destroy'])->name('users.destroy');
});
