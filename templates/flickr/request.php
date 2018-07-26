<?php

  //header('Content-Type: application/json');

  $flickr = new Flickr();

  $flickr->setHost('https://api.flickr.com/services/rest/?method=flickr.photos.search');
  $flickr->setCityId($city_id);
  $flickr->setCity($city);
  $flickr->setLandmark($landmark);
  $flickr->setExtras('description,date_taken,owner_name,tags');
  $flickr->setPerPage('6');

  echo $flickr->htmlOutput();

?>
