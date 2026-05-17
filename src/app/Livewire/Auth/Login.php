<?php

namespace App\Livewire\Auth;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;

class Login extends Component
{
    public $email = '';
    public $password = '';
    public $remember = false;
    public $errorMessage = '';

    public function login()
    {
        $this->validate([
            'email' => 'required|email',
            'password' => 'required|min:6',
        ]);

        if (Auth::attempt(['email' => $this->email, 'password' => $this->password], $this->remember)) {
            session()->regenerate();
            $user = Auth::user();

            if (is_null($user->display_name)) {
                return redirect()->route('profile.set-name');
            }

            if ($user->hasRole('admin')) {
                return redirect()->intended('/admin');
            }
            return redirect()->intended('/dashboard');
        }

        $this->errorMessage = 'Email atau password salah.';
        $this->password = '';
    }

    public function render()
    {
        return view('livewire.auth.login')->layout('layouts.guest');
    }
}