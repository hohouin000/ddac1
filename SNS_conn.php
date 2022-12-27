<?php
require 'aws/aws-autoloader.php';
use Aws\Sns\SnsClient; 
use Aws\Exception\AwsException;

$SnSclient = new SnsClient([
	'region'  => 'us-east-1',
	'version' => 'latest',
	'credentials' => [
	    'key'    => "ASIAXOBIIX4WTDEWL57P",
	    'secret' => "n2zp7fFzv6GujLoRYE2UwBwWq2rFryMvydAjzYrl",
        'token'=>"FwoGZXIvYXdzEOL//////////wEaDPqVAuJ21Q3D9KgP8yLJAUiPZTXyHeSYk7+0ZOo9XBMx1EadXHTqDKuw355ZuouUChO0CInkNYDz6rk2CUNY2XQk/LhsrfYURPkESZNi5Bu7VDjLj4RzP550a/HWwv/M4Eya+aZIYCz7iB1fTyAYswPaoTtYo3TsnuG2KKma6cFqPsoysL29C4qnyob7742dg0htB5lqa4Vi4r9PqY6j6Kd2hfNlr4/dGjwOCueffxWEbkqT+1dvTs9aF7UanP05IyasoAvTDl6KOckF/KXWAsDG8A1KloZHDSjXj6edBjItVbtA6cViboysUDZ3rL86X7w3oxFyvs/b4HaPBAbmSffiXSi1Ngwu0uFZVg/j"
	]
]);

$protocol = 'email';
//$endpoint = 'wtfoong81@gmail.com';
$topic = 'arn:aws:sns:us-east-1:511185567533:DDACAssignmentPastry';



$message = 'Christmas is near and we have a bunch of chirstmas offers...XD';
$subject = 'pastry promotions';

try {
    $result = $SnSclient->publish([
		'Subject'=>$subject,
        'Message' => $message,
        'TopicArn' => $topic,
    ]);
} catch (AwsException $e) {
    // output error message if fails
    error_log($e->getMessage());
} 


