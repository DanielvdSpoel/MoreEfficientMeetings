<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ShowAuthPageController extends Controller
{
    public function register()
    {
        return inertia('Auth/Register');
    }

    public function login()
    {
        return inertia('Auth/Login');
    }
}
