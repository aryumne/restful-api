<?php

use App\Http\Controllers\Auth\AuthenticationController;
use App\Http\Controllers\EpresenceController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('/login', [AuthenticationController::class, 'login']);
Route::post('/logout', [AuthenticationController::class, 'logout'])->middleware('auth:sanctum');
Route::get('/epresence', [EpresenceController::class, 'index'])->middleware('auth:sanctum', 'ability:create-epresence');
Route::patch('/epresence/{id}', [EpresenceController::class, 'update'])->middleware('auth:sanctum', 'ability:approve-epresence');
Route::post('/epresence', [EpresenceController::class, 'store'])->middleware('auth:sanctum', 'ability:create-epresence');
