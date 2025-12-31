<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;


class DashboardController extends Controller
{
    //
    
    // DashboardController.php
    public function chef()
    {
        $user = auth()->user();

        return view('dashboard.chef', compact('user'));
    }

    public function admin()
    {
        return view('dashboard.admin');
    }
    public function supervieur()
    {
        return view('dashboard.superviseur');                

    }
    public function contribiteur()
    {
        return view('dashboard.contributeur');                
    }
}