<?php

use App\Http\Controllers\TaskController;
use Illuminate\Support\Facades\Route;

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/tasks', [TaskController::class, 'index']);      // Összes listázása (Read All)
    Route::get('/tasks/{task}', [TaskController::class, 'show']); // Egy konkrét megtekintése (Read One)
    Route::post('/tasks', [TaskController::class, 'store']);     // Létrehozás (Create)
    Route::put('/tasks/{task}', [TaskController::class, 'update']); // Módosítás (Update)
    Route::delete('/tasks/{task}', [TaskController::class, 'destroy']); // Törlés (Delete)
});