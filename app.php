<?php

include 'csv_handler.php';
include 'twilio_lookup.php';

// Get the file name from the command line
$phone_numbers_list = $argv[1];

// Get the text file
$file = fopen($phone_numbers_list ,"r");

// Init a new array that will store the numbers
$phone_numbers = array();
while ($line = stream_get_line($file, 65535, "\n"))
{
  $phone_numbers[] = $line;
}
fclose($file);

// Import the dovenv for the Twilio credentials
$dotenv = Dotenv\Dotenv::create(__DIR__);
$dotenv->load();

// Init a new Twilio instance with our environment variables
$twilio = new TwilioLookup($_ENV['SID'], $_ENV['TOKEN']);

// Run our function to validate phone numbers
$valided_phone_numbers = $twilio->lookupPhoneNumbers($phone_numbers);

// Init a new CSV Handler
$csv_handler = new CsvHandler;

// Create a new CSV file
$file_name = $csv_handler->createNewFile();

// Populate it with the validated details
$csv_handler->populateCSVColumns($valided_phone_numbers, $file_name);

?>
