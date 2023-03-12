<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreMeetingRequest;
use App\Models\Availability;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class MeetingController extends Controller
{
    public function index()
    {
        return inertia('Meeting/Index', [
            'meetings' => auth()->user()->organizedMeetings()
                ->where('date', '>=', Carbon::now())
                ->with('attendees')->get(),
        ]);
    }

    public function create()
    {
        return inertia('Meeting/Create', [
            'attendees' => User::with('skills')->get(),
        ]);
    }

    public function store(StoreMeetingRequest $request)
    {
        $data = $request->validated();

        $attendees = $data['attendees'];

        $meeting = $request->user()->organizedMeetings()->create($data);
        $meeting->attendees()->sync($attendees);

        return redirect()->route('meetings.index')->with('success', 'Meeting created successfully');
    }

    public function availabilityCheck(Request $request)
    {
        $ids = explode(',', $request->input('attendees'));
        $ids[] = $request->user()->id;

        $date = Carbon::createFromFormat('d-m-Y', $request->input('date'))->format('Y-m-d');

        $availableSlots = collect();

        Availability::whereIn('user_id', $ids)
            ->whereBetween('date', [Carbon::now(), $date])
            ->each(function (Availability $item) use ($ids, $availableSlots) {
                $availableUsers = Availability::where('date', $item->date)
                    ->where('start_time', '<=', $item->start_time)
                    ->where('end_time', '>=', $item->end_time)
                    ->whereIn('user_id', $ids)
                    ->pluck('user_id')
                    ->toArray();

                if ($availableSlots->where('date', $item->date)->where('start_time', $item->start_time)->where('end_time', $item->end_time)->count() > 0)
                    return;
                $availableSlots->push([
                    'date' => $item->date,
                    'start_time' => $item->start_time,
                    'end_time' => $item->end_time,
                    'available_users' => $availableUsers,
                ]);
            });

        return response()->json($availableSlots);
    }
}
