<?php

use Illuminate\Support\Facades\Route;

// Redirigir la página principal directamente a nuestra API de usuarios
Route::get('/', function () {
    return redirect('/api/users');
});
