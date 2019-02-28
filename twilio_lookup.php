<?php

require __DIR__ . '/vendor/autoload.php';

use Twilio\Rest\Client;

class TwilioLookup {

  protected $twilio;
  private $sid;
  private $token;

  public function __construct($sid, $token)
  {
    // These are coming from our env file
    $this->sid = $sid;
    $this->token = $token;

    $this->twilio = new Client($this->sid, $this->token);
  }

  public function lookupPhoneNumbers(Array $phone_numbers) {

    // Get the headers
    $valided_phone_numbers = array(
      array('phone number', 'carrier', 'status')
    );

    // Counter that's used entirely for UX in the progress bar
    $counter = 0;

    // loop through the phone numbers
    foreach ($phone_numbers as $phone_number) {

      // calculate how complete we are so that we can display the user
      // some feedback on the process.
      $percentage_done = ($counter / sizeof($phone_numbers)) * 100;
      $loading_string = str_repeat('#', $percentage_done);
      echo($loading_string);
      echo(" $percentage_done%\r");

      // Getting around the Excel trying to be smart by 
      // taking large numbers like phone numbers and converting them 
      // to things like 1.23457E+19
      $csv_phone_number = '="'.$phone_number.'"'; 

      try {
        // Attempt to validate a number with the Twilio validator 
        $lookup_result = $this->twilio->lookups->v1->phoneNumbers($phone_number)
        ->fetch(array(
          "type" => "carrier",
          "countryCode" => "GB"
        ));

        $number_is_uk_based = $lookup_result->countryCode == "GB";
        $number_is_mobile = $lookup_result->carrier['type'] == "mobile";

        if ($number_is_uk_based && $number_is_mobile) {
          // If all good, add to our array with the valid status
          $valid_phone_number = array($csv_phone_number, $lookup_result->carrier['name'], 'valid');
          $valided_phone_numbers[] = $valid_phone_number;
        } else {
          // If all good but not a UK number, add to our array with the invalid status
          $invalid_phone_number = array($csv_phone_number, NULL, 'invalid');
          $valided_phone_numbers[] = $invalid_phone_number;
        }

      } catch(Exception $e){
          // If stripe errors on the number, add to our array with the invalid status
        $invalid_phone_number = array($csv_phone_number, NULL, 'invalid');
        $valided_phone_numbers[] = $invalid_phone_number;
      }
        
      $counter++;
    }

    // return the phone numbers array
    return $valided_phone_numbers;

  }
  
  
}