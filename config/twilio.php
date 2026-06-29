<?php

return [
	'sid'			=> env('TWILIO_SID'),
	'token'			=> env('TWILIO_AUTH_TOKEN'),
	'from_number'	=> env('TWILIO_NUMBER'),
	'to_number'		=> env('TWILIO_TO_NUMBER')
];