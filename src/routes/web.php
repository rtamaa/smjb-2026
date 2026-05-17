<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\DB;
use App\Livewire\Auth\Login;
use App\Livewire\Student\Dashboard;
use App\Http\Controllers\ProfileController;

Route::middleware('guest')->group(function () {
    Route::get('/login', Login::class)->name('login');
});

Route::middleware(['auth', 'panel.role'])->group(function () {
    Route::get('/dashboard', Dashboard::class)->name('dashboard');
    Route::get('/profile/set-name', [ProfileController::class, 'showSetName'])->name('profile.set-name');
    Route::post('/profile/set-name', [ProfileController::class, 'setName']);
    
    // Web notification routes
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

Route::get('/', function () {
    return redirect('/login');
});

Route::post('/logout', function () {
    auth()->logout();
    request()->session()->invalidate();
    request()->session()->regenerateToken();
    return redirect('/login');
})->name('logout');