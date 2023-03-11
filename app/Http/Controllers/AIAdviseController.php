<?php

namespace App\Http\Controllers;

use App\Http\Integrations\OpenAI\Requests\ShouldThisBeAnEmailRequest;
use App\Http\Requests\ShouldThisBeAMeetingRequest;
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
}
