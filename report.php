<?php

require_once("class.wReporter.php");

// $myReporter = new wReporter("Sydney", $api_key);

$api_key = "bd077ac6cb0d40fb";

$country = $_GET["country"];
$city = $_GET["city"];
$report_type = $_GET["type"];

try
{
   $wReporter = new wReporter($api_key, $country, $city);

   if($report_type == "full")
   {
      $wReporter->disp_FullReport();
   }
   if($report_type == "curr")
   {
      $wReporter->disp_Observation();
   }
   if($report_type == "fore")
   {
      $wReporter->disp_Forecast();
   }
}
catch (Exception $e)
{
   // app_error('Error: ' . $e->getMessage());
   echo 'Error: ' . $e->getMessage();
}

?>