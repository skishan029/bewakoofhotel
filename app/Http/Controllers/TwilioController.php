<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Twilio\Rest\Client;
use Twilio\TwiML\VoiceResponse;

class TwilioController extends Controller
{
    public function makeCall(Request $request)
    {
        echo \App\Services\TwilioService::makeCall();
    }
    public function twiml(Request $request)
    {
        $response = new VoiceResponse();

        $response->say("Hello Vijoy, this is your Laravel Twilio call.", [
            "voice" => "alice",
            "language" => "en-IN"
        ]);

        return response($response, 200)->header('Content-Type', 'text/xml');
    }
}
