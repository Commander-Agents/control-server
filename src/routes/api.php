<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Agents\AgentController;

// Route::get('/user', function (Request $request) {
//     return $request->user();
// })->middleware('auth:sanctum');


Route::post('/enroll', [AgentController::class, 'enroll']);
Route::post('/keepalive', [AgentController::class, 'keepAlive']);
Route::post('/commandAcknowledged', [AgentController::class, 'commandAcknowledged']);
Route::post('/commandOutput', [AgentController::class, 'commandOutput']);

// Route::get('/test', [AgentController::class, 'test']); // TODO : Remove this route, debug only