<?php
require_once(dirname(dirname(__FILE__)) . '/classes/class.php');
$Main = new Main;

$list = ($Main->OneBedroomOnly(true));

 $output=array();
 $i = 0;
 
         foreach ($list as $a){

               $id = intval($a->listing_id);
               $lat = number_format((float)$a->latitude, 6);
               $long = number_format((float)$a->longitude, 6);
               $price = intval($a->price);
               $address = $a->displayable_address;
               $first_published_date = $a->first_published_date;
             
               $single = array('ID' => $id,'latitude' => $lat,'longitude' => $long,'price' => $price, 'address' => (string) $address,
                               'firstPublishedDate' => (string) $first_published_date);
               $output[$i] = $single;
               $i++;
              }
              
           echo json_encode( $output);
          


?>