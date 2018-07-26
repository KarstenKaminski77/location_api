<?php

require_once('base.php');
require_once('flickr.php');

class Foursquare extends Base {

  // Set the object properties
  private $city;
  private $long;
  private $lat;
  private $host;
  private $category;
  private $city_id;
  private $user_id;
  private $user_location_id;

  protected $laert_class;
  protected $alert_message;
  protected $endpoint;

  // Getter & setter methods
  public function setHost($host){

    $this->host = $host;
  }

  public function setCity($city){

    if(!empty($city)){

      $this->city = $city;
    }
  }

  public function setCategory($category){

    $this->category = $category;
  }

  public function request(){

    $this->rewriteUrl();

    // Create the end point and set it's parameters
    $endpoint = $this->host . 'near='. $this->city;
    $endpoint .= '&categoryId=' . $this->category;
    $endpoint .= '&client_id=' . CLIENT_ID;
    $endpoint .= '&client_secret=' . CLIENT_SECRET;
    $endpoint .= '&v=20160510';
    $this->endpoint = $endpoint;

    $this->saveCity();
    $this->saveUserCity();

    // HTTP get Request
    return $this->curl();
  }

  private function showDetail($value){

    if(isset($value)){

      $output = $value . '<br>';

      return $output;
    }
  }

  private function showThumbnail(){

    $endpoint  = 'https://api.foursquare.com/v2/venues/';
    $endpoint .= $this->landmark_id .'/';
    $endpoint .= 'photos?client_id='. CLIENT_ID .'&';
    $endpoint .= 'client_secret='. CLIENT_SECRET;
    $endpoint .= '&v='. date('Ymd');
    $this->endpoint = $endpoint;

    return $this->curl();
  }

  public function rewriteUrl(){

    if(isset($_GET['city'])){

      $this->city = urlencode($_GET['city']);

      header('Location: ' . HOST . PATH . $this->city);
    }
  }

  public function saveCity(){

    // Check if the location is in the database
    $this->city_id = $this->checkDuplicate('location','id',"location = '". $this->city ."'", 'id');

    // Save the location
    if($this->city_id == 0 && isset($this->city)){

      $sql = "
        INSERT INTO
          location
            (
              location,
              created
              )
            VALUES
              (
                '". $this->city ."',
                '". date('Y-m-d') ."'
                )
      ";

      $res = $this->dbQuery($sql);

      $this->city_id = $this->lastId('location', 'location', $this->city);
    }
  }

  public function saveUserCity(){

    // Check if the user has previously searched for the location
    $args = "location_id = '". $this->city_id ."' AND user_id = ". $_SESSION['user_id'] ."";

    $user_city_id = $this->checkDuplicate('user_location','id',$args, 'id');

    // Insert a record of the search
    if($user_city_id == 0 && isset($this->city)){

      $sql = "
        INSERT INTO
          user_location
            (
              location_id,
              user_id,
              created
              )
            VALUES
              (
                ". $this->city_id .",
                ". $_SESSION['user_id'] .",
                '". date('Y-m-d') ."'
                )
      ";

      $res = $this->dbQuery($sql);
    }
  }

  public function getRecent(){

    $location_id = $this->lastId('image');
    $landmark = '';
    $i = 0;
    $return = '';

    if($location_id >= 1){

      $sql = "
      SELECT
        img.landmark,
        img.title,
        img.url,
        img.location_id
      FROM
        image img
      WHERE
        img.landmark IN (
          SELECT
            *
          FROM
            (
              SELECT
                imgs.landmark
              FROM
                image imgs
              GROUP BY
                imgs.location_id
              ORDER BY
                RAND()
              LIMIT 2
            ) AS location_landmark
        )
      ";

      $res = $this->dbQuery($sql);
      while($row = $res->fetch_assoc()){

        $i++;

        if($landmark != $row['landmark']){

          $return .= '<div class="col-xs-12">';
          $return .= '<div class="row"><h5>'. $row['landmark'] .'</h5></div>';
          $return .= '</div>';
          $return .= '<div class="row">';

          $landmark = $row['landmark'];
        }

        $class = 'img-margin-left';

        if($i % 2 == 0 || $i % 3 == 0 || $i % 4 == 0 || $i % 5 == 0){

          $class = 'img-margin';

        }

        if($i % 6 == 0){

          $class = 'img-margin-right';

        }

        $return .= '<div class="col-xs-6 col-sm-4 col-md-4 col-lg-2 col-xl-2">';
        $return .= '<img src="'. $row['url'] .'" title="'. $row['title'] .'" class="img-thumbnail '. $class .'">';
        $return .= '</div>';

        if($i % 6 == 0){

          $return .= '</div>';
        }
      }

    } else {

      $return .= '<div class="row">';
      $return .= '<div class="col-xs-12">No recent images found</div>';
      $return .= '</div>';
    }

    return $return;
  }

  public function htmlOutput(){

    if(isset($_SESSION['user_id'])){

      $foursquare = $this->request();

      $output = '<div class="row">';
      $output .= '<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 col-xl-12">';

      // If no errors
      if($foursquare->meta->code == 200){

        $output .= '<h3>Landmarks For: <i>' . ucwords(urldecode($this->city)) . '</i></h3>';

        // Loop through the object
        foreach($foursquare->response->venues as $landmark) {
          $this->landmark_id = $landmark->id;
          echo '<pre>', var_dump($this->showThumbnail()), '</pre>';

          // Name of the venue
          $output .= '<h4>' . $landmark->name . '</h4>';

          $output .= $this->showDetail(@$landmark->location->address);
          $output .= str_replace('<br>', '', $this->showDetail(@$landmark->location->lat) . ' ' . $this->showDetail(@$landmark->location->lng));

          // Link to view images of the venue
          $output .= '<a href="flickr/request/'. $this->city_id .'/'. $this->city .'/'. urlencode($landmark->name) .'" class="view">View Images</a>';
        }

      } else {

        if(isset($this->city)){

          // Output message to the browser
          $this->alert_class = 'warning';
          $this->alert_message = '<b>No results found for:</b> ' . urldecode($this->city);

          $output .= $this->alert();
        }
      }

      // Close col & row divs
      $output .= '</div>';
      $output .= '</div>';

      return $output;
    }
  }
}

?>
