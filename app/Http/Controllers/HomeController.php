<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        if (auth()->user()->hasRole('admin')) {
            return redirect()->route('admin.dashboard');
        } elseif (auth()->user()->hasRole('area_manager')) {
            return redirect()->route('area-manager.dashboard');
        } elseif (auth()->user()->hasRole('teacher')) {
            return redirect()->route('teacher.dashboard');
        }
        
        return redirect()->route('login');
    }
}
