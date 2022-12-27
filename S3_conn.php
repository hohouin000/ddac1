<?php
require 'aws/aws-autoloader.php';

$s3Client = new Aws\S3\S3Client([
	'region'  => 'us-east-1',
	'version' => 'latest',
    'scheme' => 'http',
	'credentials' => [
	    'key'    => "ASIAXOBIIX4WTDEWL57P",
	    'secret' => "n2zp7fFzv6GujLoRYE2UwBwWq2rFryMvydAjzYrl",
        'token'=>"FwoGZXIvYXdzEOL//////////wEaDPqVAuJ21Q3D9KgP8yLJAUiPZTXyHeSYk7+0ZOo9XBMx1EadXHTqDKuw355ZuouUChO0CInkNYDz6rk2CUNY2XQk/LhsrfYURPkESZNi5Bu7VDjLj4RzP550a/HWwv/M4Eya+aZIYCz7iB1fTyAYswPaoTtYo3TsnuG2KKma6cFqPsoysL29C4qnyob7742dg0htB5lqa4Vi4r9PqY6j6Kd2hfNlr4/dGjwOCueffxWEbkqT+1dvTs9aF7UanP05IyasoAvTDl6KOckF/KXWAsDG8A1KloZHDSjXj6edBjItVbtA6cViboysUDZ3rL86X7w3oxFyvs/b4HaPBAbmSffiXSi1Ngwu0uFZVg/j"
	]
]);

$bucket = 'ddac-pastry-tp053060';

