<?php

use App\Http\Controllers\ProductionController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::post('/production/materials', [ProductionController::class, 'getMaterials']);
