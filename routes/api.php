<?php

use App\Http\Controllers\Api\ProjectController;
use Illuminate\Support\Facades\Route;

Route::get('/projects', [ProjectController::class, 'index']);
Route::post('/projects', [ProjectController::class, 'store']);
Route::get('/projects/{project:slug}', [ProjectController::class, 'show']);
Route::put('/projects/{project:slug}', [ProjectController::class, 'update']);
Route::delete('/projects/{project:slug}', [ProjectController::class, 'destroy']);
