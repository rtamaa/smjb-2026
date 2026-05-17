<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProfileController extends Controller
{
    public function showSetName()
    {
        return view('profile.set-name');
    }
    
    public function setName(Request $request)
    {
        $request->validate([
            'display_name' => 'required|string|max:255',
        ]);
        
        Auth::user()->update(['display_name' => $request->display_name]);
        
        if (Auth::user()->hasRole('admin')) {
            return redirect('/admin');
        }
        
        return redirect('/dashboard');
    }
}