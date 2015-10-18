<?php
include('scraping.php');
require_once(dirname(dirname(__FILE__)) . '/conf/config.php');

// instanciate a new DAL
$dal      = new DAL();
$Scraping = new Scraping;


if (isset($_POST["firstPublishedDate"]) && isset($_POST["ID"])) {
    
    $id          = $_POST["ID"];
    $date        = $_POST["firstPublishedDate"];
    $storedindex = $dal->get_index_visits_by_id($id);
    
    
    if (isset($storedindex) && $storedindex != false) {
        foreach($storedindex as $a){
            echo $a->index;
        }
        //echo $storedindex->index;
       
    } else {
        $value = $Scraping->scrapingZoopla($id, $date);
        
        $dal->insert_new_index_today($id, $value);
        echo $value;
    
    }
}
?>