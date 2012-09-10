<?php

require_once("class.biNu.php");

class wReporter
{
   private $biNu_app;
   private $observation;
   private $forecast;
   private $url;
   
   public $api_key;
   public $country;
   public $city;
   
   public function disp_Observation()
   {
      $this->gen_Observation();
      $this->gen_BML();
   }
   
   public function disp_Forecast()
   {
      $this->gen_Forecast();
      $this->gen_BML();
   }
   
   public function disp_FullReport()
   {
      $this->gen_Observation();
      $this->gen_Forecast();
      $this->gen_BML();
   }
   
   public function __construct($api_key, $country, $city)
   {
      $this->api_key = $api_key;
      $this->country = $country;
      $this->city = $city;
      
      // $this->url = "http://api.wunderground.com/api/bd077ac6cb0d40fb/conditions/forecast/q/Australia/Sydney.xml";
      $this->url = "http://api.wunderground.com/api/" . $this->api_key . "/conditions/forecast/q/" 
         . $this->country . "/" . $this->city . ".xml"; 
         
      // load the xml from web service
      $this->request_XML();
      
      // create the binu app instance, uses instance vars to generate the page   
      $this->createBinu();
   }
   
   private function request_XML()
   {
      $xml = simplexml_load_file($this->url);
      
      // assign returned xml values
      $this->observation = $xml->current_observation;
      $this->forecast = $xml->forecast->txt_forecast;
   }
   
   private function gen_Observation()
   {
      try
      {
         //$this->biNu_app->add_text("WEATHER - CURRENT CONDITIONS");
         
         $this->biNu_app->add_text($this->observation->display_location->full);
         $this->biNu_app->add_text($this->observation->temperature_string);
         // $this->biNu_app->add_text($this->observation->wind_string);
         $this->biNu_app->add_text("Winds from the " . 
            $this->observation->wind_dir . " at " . $this->observation->wind_kph . " KMH");
         $this->biNu_app->add_text($this->observation->weather . ' Conditions');
         $this->biNu_app->add_text("Humidity " . $this->observation->relative_humidity);
         $this->biNu_app->add_text($this->observation->icon_url);
         $this->biNu_app->add_text($this->observation->display_location->latitude);
         $this->biNu_app->add_text($this->observation->display_location->longitude);
         $this->biNu_app->add_text($this->observation->observation_time);      
      }
      catch (Exception $e)
      {
         app_error('Error: ' . $e->getMessage());
         echo 'Error: ' . $e->getMessage();
      }
   }
   
   private function gen_Forecast()
   {
      //$this->biNu_app->add_text("WEATHER - FORECAST CONDITIONS");
      //$binu_app->add_header($this->city . " Forecast" ,'#483D8B',0,0,'center');
      for($ctr = 0; $ctr < sizeof($this->forecast->forecastdays->forecastday); $ctr++)
      {
         $this->biNu_app->add_text($this->forecast->forecastdays->forecastday[$ctr]->title);
         $this->biNu_app->add_text($this->forecast->forecastdays->forecastday[$ctr]->icon_url);
         $this->biNu_app->add_text($this->forecast->forecastdays->forecastday[$ctr]->fcttext_metric);
         $this->biNu_app->add_text("--------");
      }
   }
   
   private function createBinu()
   {
      $app_config = array 
      (
      'dev_id' => 17153,							                    	// Your DevCentral developer ID goes here
      'app_id' => 4067,								                     // Your DevCentral application ID goes here
      'app_name' => 'Weather Underground AU',				         // Your application name goes here
      'app_home' => 'http://paste-blue.net/wund/index.padl',     	// Publically accessible URI
      'ttl' => 1									   	                  // Your page "time to live" parameter here
      );
      
      try 
      {
         $this->biNu_app = new biNu_app($app_config);
         
         $this->biNu_app->add_header($this->city . " Weather" ,'#483D8B',0,0,'center');
         $this->biNu_app->add_menu_item( '1', 'App Home', 'http://paste-blue.net/wund/index.padl' );
         $this->biNu_app->add_menu_item( '2', 'biNu Home', 'http://apps.binu.net/apps/mybinu/index.php' );
      }
      catch (Exception $e)
      {
         app_error('Error: ' . $e->getMessage());
         echo 'Error: ' . $e->getMessage();
      }
   }
   
   private function gen_BML()
   {
      $this->biNu_app->generate_BML();
   }
}

?>