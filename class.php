<?php

include("config.php");
include('scraping.php');

Class Main
{

    public $apikey = Conf::ZOOPLA_API;
    
    public function __construct()
    {
       
    }

    private function getlist_zoopla()
    {
     if(isset($_GET['lat']))
        {

    $lat = $_GET['lat'];
    $lon = $_GET['lon'];
    $xml = 'http://api.zoopla.co.uk/api/v1/property_listings.xml?';

    $xml.= 'listing_status=rent';
    $xml.= '&maximum_beds=1';
    $xml.= '&maximum_price=250';
    $xml.= '&api_key='.$this->apikey;
    $xml.= '&latitude='.$lat.'&longitude='.$lon.'&radius=3';
    //$xml.= '&area=hackney';
    $xml.= '&keyword=-shared';
    $xml.= '&page_size=3';
    
    $obj = simplexml_load_file($xml);


    if($obj && isset($obj->listing))
    {  
    return $obj->listing;
    
    }
    
   
   
        }  
    }//end getlist_zoopla
    
     private function cleanlist(){
        
        $list = Main::getlist_zoopla();
        $str = "";
        $matches = array();
         
        $patterns = array(
        "description coming shortly" // 
       
        ); 
     $regex = '/(' .implode('|', $patterns) .')/';
        foreach ($list as $single_flat){
             
              $str = $single_flat->description;

              if(!preg_match($regex, strtolower($str))){
               $matches[] = $single_flat;
              }
             
        }
 
       return $matches;  
    } //end cleanlist
    
      public function OneBedroomOnly($bool=boolean){
        
        $list = Main::cleanlist();
        $matches = array();
        $str="";
        
        $pattern1 =
        "/\b([2-9]|(two|three|four|five|six|seven|eight|nine))\b\s\b(bedroom(?=s| |$)|room(?=s| |$)|double bedroom(?=s| |$))\b/"; //number of rooms
        
        $pattern2 =
        "/flatshare/"; //shared

        foreach ($list as $single_flat){
            
              $str = $single_flat->description;

              if( preg_match($pattern1, strtolower($str)) || preg_match($pattern2, strtolower($str))){
                //unset($list[$single_flat]);
                $matches[] = $single_flat;
                }
            
            }
       
        if ($bool){
       $matches = array_diff($list, $matches);
        }
        return $matches;
    } //end OneBedroomOnly
    
    
    
}



?>