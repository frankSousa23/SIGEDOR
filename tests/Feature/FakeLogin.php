<?php

namespace Tests\Feature;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;

class FakeLogin extends Component
{
    public $email;
    public $password;

    public function authenticate()
    {
        if (Auth::attempt(['email' => $this->email, 'password' => $this->password])) {
            session()->regenerate();
            return redirect()->intended('/dashboard');
        }

        $this->addError('email', trans('auth.failed'));
    }

    public function render()
    {
        return view('auth.login');
    }
}
