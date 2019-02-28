<?php

class CsvHandler {

  public function createNewFile() {

    // Use date information for the file name so that it 
    // is both unique and sortable
    $year = date("Y");
    $month = date("m");
    $day = date("d");
    $hour = date("H");                      
    $minute = date("i");  
    $seconds = date("s");

    // compose the full string
    $full_date_time = $year . '_' . $month . '_' . $day . '__' . $hour . '_' . $minute . '_' . $seconds;

    $file_extension = '.csv';

    $file_name = $full_date_time . $file_extension;

    // create the CSV file
    $file = fopen("outputs/".$file_name, 'w');

    fclose($file);

    // return the newly created file's name
    return $file_name;
  }

  public function populateCSVColumns(Array $row, String $file_name) {
    // get the CSV file and insert records into it
    $csv_file = fopen("outputs/".$file_name, 'w');
    
      foreach ($row as $row_content) {
          fputcsv($csv_file, $row_content);
      }
      fclose($csv_file);

    }
}