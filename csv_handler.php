<?php

class CsvHandler {

  public function createNewFile() {

    $year = date("Y");
    $month = date("m");
    $day = date("d");
    $hour = date("H");                      
    $minute = date("i");  
    $seconds = date("s");

    $fileExtension = '.csv';

    $fullDateTime = $year . '_' . $month . '_' . $day . '__' . $hour . '_' . $minute . '_' . $seconds;

    $fileName = $fullDateTime . $fileExtension;

    $fp = fopen($fileName, 'w');

    fclose($fp);

    return $fileName;
  }

  public function populateCSVColumns(Array $row, String $fileName) {
    $csv_file = fopen($fileName, 'w');
    
      foreach ($row as $row_content) {
          fputcsv($csv_file, $row_content);
      }
      fclose($csv_file);

    }
}