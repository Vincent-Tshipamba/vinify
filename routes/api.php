<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TextAnalysisController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::get('/analysis/{analysis}/status', [TextAnalysisController::class, 'getAnalysisStatus']);