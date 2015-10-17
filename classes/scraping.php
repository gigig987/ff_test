<?php
include('simple_html_dom.php');

// Create DOM from URL or file
class Scraping {
    
    public function scrap_zoopla($id){
        
        if (isset($id)){
            $html = file_get_html('http://www.zoopla.co.uk/to-rent/details/'.$id);
    
            $visits =  $html->find('#listings-agent br+ strong', 6)->plaintext;
           
            $html->clear();            
            return (int)$visits;
          
        }
    }
    
    public function scrapingZoopla($id, $date)
    
    {
       $firstlisted = strtotime($date);
       $now = time(); 
       
       $datediff = $now - $firstlisted;
      
       $datediffdays = floor($datediff/(60*60*24)) -1;
       
       if ($datediffdays > 0){
       $visits = Scraping::scrap_zoopla($id);
       $dailyaverage = round($visits / $datediffdays, 4);
       
       
       return $dailyaverage;
       }
       
       
       
    } // end scrapingZoopla
    
    
}
?>