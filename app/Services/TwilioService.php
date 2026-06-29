<?php
namespace App\Services;

use Twilio\Rest\Client;
use Twilio\TwiML\VoiceResponse;

class TwilioService
{
	public static function makeCall($to_number = null)
    {
        $sid = config('twilio.sid');
        $token = config('twilio.token');
        $twilio_number = config('twilio.from_number');

        $to = $to_number ?? config('twilio.to_number'); // verified number (trial)

        $client = new Client($sid, $token);
   
        try {
        	$call = $client->calls->create(
	            $to,
	            $twilio_number,
	            [
	                "url" => "http://demo.twilio.com/docs/voice.xml", //route('twiml') // webhook
	            ]
	        );

	        return $call->sid;
        } catch (\Exception $e) {
        	return null;
        }
    }
}