<?php
include('scraping.php');
$Scraping = new Scraping;


if (isset($_POST["firstPublishedDate"]) && isset($_POST["ID"])) {
    
  $id = $_POST["ID"];
  $date = $_POST["firstPublishedDate"];
echo $Scraping->scrapingZoopla($id,$date);
}
?>