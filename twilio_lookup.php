<?php

require __DIR__ . '/vendor/autoload.php';

use Twilio\Rest\Client;

class TwilioLookup {

  protected $twilio;
  private $sid;
  private $token;

  public function __construct($sid, $token)
  {
    $this->sid = $sid;
    $this->token = $token;

    $this->twilio = new Client($this->sid, $this->token);
  }

  public function lookupPhoneNumbers(Array $phone_numbers) {

    $valided_phone_numbers = array(
      array('phone number', 'carrier', 'status')
    );

    $counter = 0;

    foreach ($phone_numbers as $phone_number) {

      $percentageDone = ($counter / sizeof($phone_numbers)) * 100;

      $loading_string = str_repeat('#', $percentageDone);
      echo($loading_string);
      echo(" processing...\r");

      $csv_phone_number = '="'.$phone_number.'"'; 

      try {
        $lookup_result = $this->twilio->lookups->v1->phoneNumbers($phone_number)
        ->fetch(array(
          "type" => "carrier",
          "countryCode" => "GB"
        ));

        $number_is_uk_based = $lookup_result->countryCode == "GB";
        $number_is_mobile = $lookup_result->carrier['type'] == "mobile";

        if ($number_is_uk_based && $number_is_mobile) {
          $valid_phone_number = array($csv_phone_number, $lookup_result->carrier['name'], 'valid');
          $valided_phone_numbers[] = $valid_phone_number;
        } else {
          $invalid_phone_number = array($csv_phone_number, NULL, 'invalid');
          $valided_phone_numbers[] = $invalid_phone_number;
        }

      } catch(Exception $e){
        $invalid_phone_number = array($csv_phone_number, NULL, 'invalid');
        $valided_phone_numbers[] = $invalid_phone_number;
      }
        
      $counter++;
    }
    return $valided_phone_numbers;

  }
  
  
}