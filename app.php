<?php

include 'csv_handler.php';
include 'twilio_lookup.php';

$phone_numbers_list = $argv[1];
$phone_numbers = array();
$file = fopen($phone_numbers_list ,"r");

while ($line = stream_get_line($file, 65535, "\n"))
{
  $phone_numbers[] = $line;
}
fclose($file);

$dotenv = Dotenv\Dotenv::create(__DIR__);
$dotenv->load();

$twilio = new TwilioLookup($_ENV['SID'], $_ENV['TOKEN']);

$valided_phone_numbers = $twilio->lookupPhoneNumbers($phone_numbers);

$csv_handler = new CsvHandler;
$headers = array( array("Number", "Carrier", "Status") );

$fileName = $csv_handler->createNewFile();
$csv_handler->populateCSVColumns($valided_phone_numbers, $fileName);

?>
