<?php

 //Define a flag indicating if we have places or not
 $wehaveplaces = 0;

 //if both vars GET are setted then go ahead
 if(isset($_GET['place1']) && isset($_GET['place2'])) { 

   //if both vars are empty namely if variable place1 send as GET 
   //is empty and variable place2 are empty
   //then flag is zero and display the warning
   if($_GET['place1'] == '' && $_GET['place2'] == '') {

      //flag indicating that we haven't places
      $wehaveplaces = 0;

      //giving an warning in front page
      $warning = '<p class="warning">You need to provides the first and second place.</p>';         

   //otherwise just one variable sent as GET are empty then display the warning 
   } else if($_GET['place1'] == '') {

      //flag indicating that we haven't places
      $wehaveplaces = 0;

      //giving an warning in front page
      $warning = '<p class="warning">You need to provides the first place.</p>';    

    //else if just one variable sent as GET are empty 
   //namely second variable then display the warning 
   } else if($_GET['place2'] == '') {

       //flag indicating that we haven't places
       $wehaveplaces = 0;

       //giving an warning in front page
       $warning = '<p class="warning">You need to provides the second place.</p>';    
  
    //othewise are setting both places 
   //with values the go ahead
   } else {

    //grab place1
    $placeone = $_GET['place1'];

    //grab place2
    $placetwo = $_GET['place2'];

    //define api key
    $apikey = "ABQIAAAAijZqBZcz-rowoXZC1tt9iRT2yXp_ZAY8_ufC3CFXhHIE1NvwkxQQBCaF1R_k1GBJV5uDLhAKaTePyQ";

    //set up endpoint YQL service
    $endpoint = 'http://query.yahooapis.com/v1/public/yql?q=';

    //set up YQL statement
    $yql = 'use "http://thinkphp.ro/apps/php-hacks/distance/geo.distance.xml" as geo.distance; select * from geo.distance where place1="'.$placeone.'" and place2="'.$placetwo.'"';

    //set up url for REST GET
    $url = $endpoint. urlencode($yql). '&format=json';

    //grab the data
    $output = get($url);

    //decode json data into string
    $json = json_decode($output);
 
    //if we have the results and the places are valids then go ahead
    if($json->query->results->distance && is_array($json->query->results->distance->place)) {

       //we have 2 places
       $wehaveplaces = 1; 
       //get miles
       $miles = $json->query->results->distance->miles;
       //get kilometers
       $km = $json->query->results->distance->kilometers;

       //template for display the distance in front page
       $template = "<span>{place1}</span> and <span>{place2}</span> are {miles} miles ({km} km) apart.";
       $result = str_replace('{place1}',$placeone,$template);  
       $result = str_replace('{place2}',$placetwo,$result);
       $result = str_replace('{miles}',$miles,$result);
       $result = str_replace('{km}',$km,stripslashes($result));

       //get the places array
       $places = $json->query->results->distance->place;

       //set up an array to store the desired data
       $out = array();

         //get first component of the result array
         $place1 = $places[0];         
         $out['place1']['woeid'] = $place1->woeid;
         $out['place1']['name'] = $place1->name;
         $out['place1']['type'] = $place1->placeTypeName->content;
         $out['place1']['country'] = $place1->country->content;
         $out['place1']['admin1'] = $place1->admin1->content;
         $out['place1']['admin1type'] = $place1->admin1->type;
         $out['place1']['admin2'] = $place1->admin2->content;
         $out['place1']['admin2type'] = $place1->admin2->type;
         $out['place1']['locality1'] = $place1->locality1->content;   
         $out['place1']['locality1type'] = $place1->locality1->type;   
         $out['place1']['locality2'] = $place1->locality2->content;   
         $out['place1']['locality2type'] = $place1->locality2->type;   

         //get second compoment of the result array
         $place2 = $places[1]; 
         $out['place2']['woeid'] = $place2->woeid;
         $out['place2']['name'] = $place2->name;
         $out['place2']['type'] = $place2->placeTypeName->content;
         $out['place2']['country'] = $place2->country->content;
         $out['place2']['admin1'] = $place2->admin1->content;
         $out['place2']['admin1type'] = $place2->admin1->type;
         $out['place2']['admin2'] = $place2->admin2->content;
         $out['place2']['admin2type'] = $place2->admin2->type;
         $out['place2']['locality1'] = $place2->locality1->content;   
         $out['place2']['locality1type'] = $place2->locality1->type;   
         $out['place2']['locality2'] = $place2->locality2->content;   
         $out['place2']['locality2type'] = $place2->locality2->type;   

         $lat1 = $place1->centroid->latitude;
         $lon1 = $place1->centroid->longitude;
         $lat2 = $place2->centroid->latitude;
         $lon2 = $place2->centroid->longitude;

         //grab boundingNBox for place1
         $bound1 = $place1->boundingBox;
         foreach($bound1 as $a=>$b) {
             foreach($b as $x=>$y){
                 $out['place1']['boundingBox'][$a][$x] = $y;
             }             
         }

         //grab boundingNBox for place2
         $bound2 = $place2->boundingBox;
             foreach($bound2 as $a=>$b) {
                 foreach($b as $x=>$y){
                     $out['place2']['boundingBox'][$a][$x] = $y;
                 }             
             }

         //define src map direction 
         $direction = "http://maps.google.com/maps/api/staticmap?sensor=false&size=240x200&maptype=roadmap&markers=color:blue|label:2|".$lat2.",".$lon2."&markers=color:red|label:1|".$lat1.",".$lon1."&key=".$apikey."&path=color:0x0000ff|weight:5|".$lat1.",".$lon1."|".$lat2.",".$lon2;
         //define img direction
         $mapdirection = "<img src='$direction' alt='$placeone to $placetwo'>";
         //define src first place
         $src1 = "http://maps.google.com/maps/api/staticmap?sensor=false&size=240x200&maptype=roadmap&markers=color:red|label:1|".$lat1.",".$lon1."&key=".$apikey."&visible=".$lat1.",".$lon1."|".$out['place1']['boundingBox']['southWest']['latitude'].",".$out['place1']['boundingBox']['southWest']['longitude']."|".$out['place1']['boundingBox']['northEast']['latitude'].",".$out['place1']['boundingBox']['northEast']['longitude'];
         //define src second place
         $src2 = "http://maps.google.com/maps/api/staticmap?sensor=false&size=240x200&maptype=roadmap&markers=color:blue|label:2|".$lat2.",".$lon2."&key=".$apikey."&visible=".$lat2.",".$lon2."|".$out['place2']['boundingBox']['southWest']['latitude'].",".$out['place2']['boundingBox']['southWest']['longitude']."|".$out['place2']['boundingBox']['northEast']['latitude'].",".$out['place2']['boundingBox']['northEast']['longitude'];
         //define img for first place
         $map1 = "<img src='$src1' alt='$placeone'>";
         //define img for second place
         $map2 = "<img src='$src2' alt='$placetwo'>";

    //otherwise we haven't both places
    } else {

     //flag indicating that we haven't places
     $wehaveplaces = 0;

     //giving an warning in front page
     $warning = '<p class="warning">One of your location couldn\'t be found.</p>';

    }//end if-else

  }//end ifelse

}//end if isset $_GET['place1'],$_GET['place2']

 /* Utilities */
 //using cURL for grab the data
 //@param (String) $url
 //@return (String) return the desired data
 function get($url) {
    $ch = curl_init();
    curl_setopt($ch,CURLOPT_URL,$url);
    curl_setopt($ch,CURLOPT_RETURNTRANSFER,1);
    curl_setopt($ch,CURLOPT_CONNECTTIMEOUT,2);
    $data = curl_exec($ch);
    curl_close($ch);
    if(empty($data)) {return 'Error retrieving.Server timeout.';}
              else 
                     {return $data;}
 }
 /*
  Description: this function display the info for a place. It is passed an array containing infos about place.
  @param (Array) $place 
  @return (Object) markup <UL> containing formated infos about place 
 */
 function showInfo($place) {
      $out = "<ul>";
      $out .= "<li><strong>".$place['name']."</strong> (".$place['type'].")</li>";
      $out .= "<li>Country: ".$place['country']."</li>";
      $out .= "<li>WOEID: ".$place['woeid']."</li>";

      if($place['admin1'] || $place['admin2']) {
         $out .= "<li>Administrative:</li>";
      }
      if($place['admin1type']) {
         $out .= "<ul><li>".$place['admin1']." (".$place['admin1type'].")</li>";  
      }
      if($place['admin2type']) {
         $out .= "<li>".$place['admin2']." (".$place['admin2type'].")</li></ul>";  
      } else {
         $out .= "</ul>";
      }
      if($place['locality1'] || $place['locality2']) {
      $out .= "<li>Localities:</li>";
      }
      if($place['locality1type']) {
         $out .= "<ul><li>".$place['locality1']." (".$place['locality1type'].")</li>";  
      }
      if($place['locality2type']) {
         $out .= "<li>".$place['locality2']." (".$place['locality2type'].")</li></ul>";  
      } else {
         $out .= "</ul>";
      }   
      $out .= "</ul>";
   echo$out;
 }
?>
