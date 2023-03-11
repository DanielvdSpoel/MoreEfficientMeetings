<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class MeetingController extends Controller
{
    public function index()
    {
        return inertia('Meeting/Index');
    }
    public function create()
    {
        return inertia('Meeting/Create', [
            'attendees' => User::with('skills')->get(),
        ]);
    }
}
