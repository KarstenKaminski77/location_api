<?php

require_once('base.php');

class Flickr extends Base {

  // Set the object properties
  private $host;
  private $tag;
  private $per_page;
  private $city_id;
  private $photo_id;
  private $city;
  private $landmark;
  private $title;
  private $tags;
  private $date_taken;
  private $description;
  private $url;

  protected $endpoint;

  // Set the API host
  public function setHost($host){

    $this->host = $host;
  }

  // Set number of photos
  public function setPerPage($per_page){

    $this->per_page = $per_page;
  }

  // Set extras
  public function setExtras($extras){

    $this->extras = $extras;
  }

  // Set the city id
  public function setCityId($city_id){

    $this->city_id = $city_id;
  }

  // Get the city id
  public function getCityId(){

    return $this->city_id;
  }

  // Set the city
  public function setCity($city){

    $this->city = $city;
  }

  // Set the landmark
  public function setLandmark($landmark){

    $this->landmark = addslashes($landmark);
  }

  private function request(){

    // Create the end point
    $endpoint = $this->host;
    $endpoint .= '&api_key=' . FLICKR_API_KEY;
    $endpoint .= '&tags=' . $this->landmark;
    $endpoint .= '&extras=' . $this->extras;
    $endpoint .= '&per_page=' . $this->per_page;
    $endpoint .= '&format=json';
    $endpoint .= '&nojsoncallback=1';

    $this->endpoint = $endpoint;

    // HTTP get Request
    return $this->curl($endpoint);
  }

  private function getThumbnail(){

    // Get the object properties
    $farm = $this->photo->farm;
    $server = $this->photo->server;
    $secret = $this->photo->secret;
    $this->photo_id = $this->photo->id;
    $land_mark = $this->landmark;
    $this->title = addslashes($this->photo->title);
    $this->date_taken = $this->photo->datetaken;
    $this->tags = addslashes($this->photo->tags);
    $this->description = addslashes($this->photo->description->_content);
    $this->url = 'http://farm'.$farm.'.staticflickr.com/'.$server.'/'.$this->photo_id.'_'.$secret.'_q.jpg';

    $this->savePhoto();

    return '<img title="'. $this->title .'" class="img-thumbnail" src="'. $this->url .'" />';
  }

  private function showMetadata($key, $value){

    if(!empty($value)){

      $output = '<b>'. $key .'</b></br>';
      $output .= stripslashes($value) . '<br><br>';

      return $output;
    }
  }

  private function savePhoto(){

    if($this->checkDuplicate('image','id','image_id = ' . $this->photo_id) == 0){

      $sql = "
        INSERT INTO
          image
            (
              location_id,
              image_id,
              landmark,
              title,
              tags,
              date_taken,
              description,
              url
            )
          VALUES
            (
              ". $this->city_id .",
              ". $this->photo_id .",
              '". addslashes(ucwords(urldecode($this->landmark))) ."',
              '". $this->title ."',
              '". $this->tags ."',
              '". $this->date_taken ."',
              '". $this->description ."',
              '". $this->url ."'
            )
      ";

      $res = $this->dbQuery($sql);
    }
  }

  public function htmlOutput(){

    $this->photo_array = $this->request()->photos->photo;

    if($this->request()->photos->total > 0){

      $output = '<div class="row row-img">';
      $output .= '<h3>Photos For: <i><b>' . ucwords(urldecode($this->city)) . ' > ' . ucwords(urldecode($this->landmark)) . '</b></i>';
      $output .= '</div>';

      foreach($this->photo_array as $this->photo){

        $output .= '<div class="row row-img">';

        // Display photo
        $output .= '<div class="col-xs-12 col-sm-3 col-md-3 col-lg-3 col-xl-3 text-center">';
        $output .= $this->getThumbnail();
        $output .= '</div>';

        // Display photo metadata
        $output .= '<div class="col-xs-12 col-sm-9 col-md-9 col-lg-9 col-xl-9">';
        $output .= $this->showMetadata('Title', $this->title);
        $output .= $this->showMetadata('Tags', $this->tags);
        $output .= $this->showMetadata('Date Taken', $this->date_taken);
        $output .= $this->showMetadata('Description', $this->description);
        $output .= '</div>';

        // Close div class row
        $output .= '</div>';
      }

    } else {

      $output = '<div class="alert alert-warning" role="alert">';
      $output .= '<b>No photos found for:</b> ' . ucfirst(urldecode($this->city)) . ' > ' . ucfirst(urldecode($this->landmark));
      $output .= '</div>';
    }

    return $output;
  }
}

?>
