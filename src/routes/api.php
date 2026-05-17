<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\TaskController;
use App\Http\Controllers\Api\FocusSessionController;

// Public routes
Route::post('/login', [AuthController::class, 'login']);

// Protected routes
Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/me', [AuthController::class, 'me']);
    
    Route::apiResource('tasks', TaskController::class);
    Route::post('/tasks/{id}/complete', [TaskController::class, 'complete']);
    
    Route::post('/focus/start', [FocusSessionController::class, 'start']);
    Route::post('/focus/{id}/stop', [FocusSessionController::class, 'stop']);
    Route::post('/focus/{id}/complete', [FocusSessionController::class, 'complete']);
    Route::get('/focus/history', [FocusSessionController::class, 'history']);
    Route::get('/focus/stats', [FocusSessionController::class, 'stats']);
    
    Route::get('/notifications/unread', function () {
        return response()->json(DB::table('notifications')->where('user_id', auth()->id())->whereNull('read_at')->orderBy('created_at', 'desc')->limit(10)->get());
    });
    
    Route::post('/notifications/{id}/read', function ($id) {
        DB::table('notifications')->where('id', $id)->where('user_id', auth()->id())->update(['read_at' => now()]);
        return response()->json(['success' => true]);
    });
    
    Route::post('/notifications/read-all', function () {
        DB::table('notifications')->where('user_id', auth()->id())->whereNull('read_at')->update(['read_at' => now()]);
        return response()->json(['success' => true]);
    });
});