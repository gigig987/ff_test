<?php
include('simple_html_dom.php');

// Create DOM from URL or file
class Scraping {
    
    public function scrap_zoopla($id){
        
        if (isset($id)){
            $html = file_get_html('http://www.zoopla.co.uk/to-rent/details/'.$id);
    
           // $visits =  $html->find('#listings-agent br+ strong', 6)->plaintext;
           $string = $html->find('#listings-agent .sidebar',1)->plaintext;
            $html->clear();
            if (preg_match('/(?<=days: )[\d,.]+(?= )/', $string, $m)){
                $visits = $m[0];
                
            }
            
            return $visits;
          
        }
    }
    
    public function scrapingZoopla($id, $date)
    
    {
       $firstlisted = strtotime($date);
       $now = time(); 
       
       $datediff = $now - $firstlisted;
      
       $datediffdays = floor($datediff/(60*60*24)) -1;
       
       if ($datediffdays > 0){
       $visits =  intval(str_replace(",","", Scraping::scrap_zoopla($id)));
        $days = ($datediffdays <= 30? $datediffdays : 30);
       $dailyaverage = round($visits / $days, 4);
       
       
       return $dailyaverage;
       }
       
       
       
    } // end scrapingZoopla
    
    
}
?>