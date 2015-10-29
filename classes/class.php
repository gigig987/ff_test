<?php
require_once(dirname(dirname(__FILE__))  . '/conf/config.php');


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
    $xml.= '&maximum_price=300';
    $xml.= '&order_by=price';
    $xml.= '&api_key='.$this->apikey;
    $xml.= '&latitude='.$lat.'&longitude='.$lon.'&radius=1';
    $xml.= '&keyword=-garage,-parking';
    //$xml.= '&furnished=furnished';
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
        
        /// TO DO remove garage
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
        "/\b([2-9]|(two|three|four|five|six|seven|eight|nine))\b\s\b((bedroom?)(s\b|\b)|(room?)(s\b|\b)|(double bedroom?)(s\b|\b))\b/"; //number of rooms
        
        $pattern2 =
        "/(flatshare|(share?)(d\b|\b))/"; //shared

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
  
     private function render_tube_icon($tube){
        
      $name = preg_replace('/\s?/', '', $tube);
      $name = strtolower($name);
      $url = ROOT_URL . '/static/resources/routeicon-london-tube-'.$name.'-24@2x.png';
   //  $url = '/static/resources/routeicon-uk-london-'.$name.'-24@2x.png';
      
      $html = '<img src="'.$url.'" class="img-tube"/>';
      return $html;

     }
  
  
   private function render_commute_time($transits){
        
       foreach($transits as $transit){
        
      // return $transit['walking'];
    $minutes =  floor((int)$transit['walking'] / 60);
    
    
    
    $output='';
    $output .= 'w: '.$minutes.' mins ';
    if (!empty($transit['tube'])){
        foreach($transit['tube'] as $tube){
        $output.= '| '. $this->render_tube_icon($tube);
         }
     }
      }
      
    return $output;  
    }
    
    public function get_commute_time($list){
        
        if (isset($list->latitude)) {
          $from = $list->latitude.','.$list->longitude;
          }else
          $from = $list->displayable_address;
               $g_apikey = Conf::GMAPS_API;
               
               // ADD FUNCTIONALITY for user to set work address
               $to = "W1U 6JQ";
               
               $base_url = 'https://maps.googleapis.com/maps/api/directions/xml?sensor=false';
               $xml = simplexml_load_file("$base_url&origin=$from&destination=$to&mode=transit&key=$g_apikey");
               $distance = (string)$xml->route->leg->distance->text;
               $duration = (string)$xml->route->leg->duration->text;
               $transits = array();
               $walking = 0;
               $tubes = array();
               $buses = array();
               $trains = array();
               
               foreach($xml->route->leg->step as $step){
                if ($step->travel_mode == 'TRANSIT') {
                    if($step->transit_details->line->vehicle->type == 'BUS'){
                        $buses[] = (string)$step->transit_details->line->short_name;
                    }
                    
                    if($step->transit_details->line->vehicle->type == 'SUBWAY'){
                       $tubes[] = (string)$step->transit_details->line->short_name;
                    }
                    
                    if($step->transit_details->line->vehicle->type == 'COMMUTER_TRAIN'){
                       $trains[] = (string)$step->transit_details->line->short_name;
                    }
                   //  $transits[] = (string)$type.$step->transit_details->line->short_name;
                    }elseif($step->travel_mode == 'WALKING'){
                    
                        $walking +=(int)$step->duration->value;
                    }
                    
                    
                }
                $transits[] = array(
                                    'walking'=>$walking,
               
                                    'tube' => $tubes,
                                    
                                    'bus' => $buses,
                                    'train' => $trains
                                    
                                    );
            $output = $this->render_commute_time($transits);   
            return $output;
        
    }// end get_commute_time()
    
    
    
     public function get_directions($list){
        
        if (isset($list->latitude)) {
          $from = $list->latitude.','.$list->longitude;
          }else
          $from = $list->displayable_address;
               $g_apikey = Conf::GMAPS_API;
               $to = "W1U6JQ";
               $base_url = 'https://maps.googleapis.com/maps/api/directions/xml?sensor=false';
               $xml = simplexml_load_file("$base_url&origin=$from&destination=$to&mode=transit&key=$g_apikey");
               $distance = (string)$xml->route->leg->distance->text;
               $duration = (string)$xml->route->leg->duration->text;
       
            return $xml;
        
    }// end get_commute_time()
    
}



?>