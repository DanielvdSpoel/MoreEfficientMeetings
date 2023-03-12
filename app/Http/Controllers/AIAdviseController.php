<?php

namespace App\Http\Controllers;

use App\Http\Integrations\OpenAI\Requests\ShouldThisBeAnEmailRequest;
use App\Http\Integrations\OpenAI\Requests\SuggestAttendeesRequest;
use App\Http\Requests\ShouldThisBeAMeetingRequest;
use App\Models\User;
use Illuminate\Http\Request;

class AIAdviseController extends Controller
{
    public function shouldThisBeAMeeting(Request $request)
    {
        $title = $request->input('title') ?? "No title provided";
        $description = $request->input('description') ?? "No description provided";

        $aiRequest = new ShouldThisBeAnEmailRequest($title, $description);
        $aiRequest->body()->add("model", "text-davinci-003");
        $response = $aiRequest->send();
        return $response->json();
    }

    public function getSuggestedAttendees(Request $request) {

        $title = $request->input('title') ?? "No title provided";
        $description = $request->input('description') ?? "No description provided";
        $people = User::with('departments', 'skills')->get();
        $aiRequest = new SuggestAttendeesRequest($title, $description, $people);

//        dd($aiRequest->send()->json());
        return $aiRequest->send()->json();
    }
}
