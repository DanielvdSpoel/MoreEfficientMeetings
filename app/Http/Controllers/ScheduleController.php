<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ScheduleController extends Controller
{
    public function __invoke(Request $request)
    {
        return inertia('Schedule', [
            'user' => $request->user(),
        ]);
    }
}
