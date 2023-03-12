<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateAvailabilityRequest;
use App\Http\Requests\UpdateProfileRequest;
use App\Models\Department;
use App\Models\Skill;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class ProfileController extends Controller
{
    public function show(Request $request)
    {
        return inertia('Profile', [
            'user' => $request->user()->load('skills', 'departments'),
            'skills' => Skill::all(),
            'departments' => Department::all(),
            'availabilities' => $request->user()->availabilities()->orderBy('date')->get(),
        ]);
    }

    public function update(UpdateProfileRequest $request)
    {
        $data = $request->validated();

        if (isset($data['skills'])) {
            $request->user()->skills()->sync($data['skills']);
            unset($data['skills']);
        }

        if (isset($data['departments'])) {
            $request->user()->departments()->sync($data['departments']);
            unset($data['departments']);
        }

        $request->user()->update($data);

        return redirect()->route('profile')->with('success', 'Profile updated successfully');
    }

    public function addAvailability(CreateAvailabilityRequest $request)
    {
        $data = $request->validated();
        $data['date'] = "2021-01-01";
        //$data['date'] = Carbon::createFromFormat('d-m-Y', $data['date'])->format('Y-m-d');
        $request->user()->availabilities()->create($data);

        return redirect()->route('profile')->with('success', 'Availability added successfully');
    }

    public function deleteAvailability(Request $request, $id)
    {
        $request->user()->availabilities()->findOrFail($id)->delete();

        return redirect()->route('profile')->with('success', 'Availability deleted successfully');
    }
}
