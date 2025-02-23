<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\TasksController;
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

Route::post('auth', [AuthController::class, 'auth']);

Route::get('/tasks/list/{building_id}', [TasksController::class, 'get']);
Route::post('/tasks/store', [TasksController::class, 'store']);
Route::put('/tasks/update', [TasksController::class, 'update']);
Route::middleware('auth:sanctum')->group(function() {
});

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });
