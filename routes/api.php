<?php

use App\Http\Controllers\TaskController;
use Illuminate\Support\Facades\Route;

// Fontos: a tesztben actingAs-t használsz, így kell az auth middleware!
Route::middleware('auth:sanctum')->group(function () {
    Route::post('/tasks', [TaskController::class, 'store']);
    // Majd ide jöhet a többi: get, put, delete
});