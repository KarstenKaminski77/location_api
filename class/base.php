<?php

  session_start();

  class Base {

    // Set the object properties
    protected $mysqli;
    private $host = DB_HOST;
    private $user = DB_USER;
    private $password = DB_PWD;
    private $database = DB_DATABASE;

    protected $laert_class;
    protected $alert_message;
    protected $endpoint;

    public $last_id;

    public function __construct(){

      $this->mysqli = FALSE;

      // Connect to the database
      $this->mysqli = new mysqli($this->host, $this->user, $this->password, $this->database);

      if ($this->mysqli->connect_error) {

        // Throw an error if no connection
        die('Connection Error: ' . $this->mysqli->connect_error);
      }
    }

    // Expose query()
    public function dbQuery($sql){

      $query = $this->mysqli->query($sql);

      // Check for SQL errors
      if(mysqli_error($this->mysqli)){

        die('Error: ' . $this->mysqli->error);

      } else {

        return $query;
      }
    }

    // Expose num_rows
    public function dbNumRows($query){

      $numrows = $query->num_rows;

      return $numrows;
    }

    // Sanitise user input
    public function sanitise($string){

      if(is_array($string)){

        return array_map(array($this->mysqli, 'real_escape_string'), $string);

      } else {

        return $this->mysqli->real_escape_string($string);
      }
    }

    protected function curl(){

      // initiate curl
      $ch = curl_init();

      // Set the cURL Options
      curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
      curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
      curl_setopt($ch, CURLOPT_URL, $this->endpoint);

      // Execute the cURL session
      $result = curl_exec($ch);

      // Close the cURL session
      curl_close($ch);

      // Return the object
      return json_decode($result);
    }

    // Get the id of the last inserted record
    public function lastId($tbl, $col = NULL, $value = NULL){

      $where = '';

      if(isset($col) && isset($value)){

        $where = "
          WHERE
            $col = '". $value ."'
        ";
      }

      $sql = "
        SELECT
          *
        FROM
          $tbl
        $where
        ORDER BY
          id DESC
        LIMIT 1
      ";

      $res = $this->dbQuery($sql);
      $row = $res->fetch_assoc();

      $this->last_id = $row['id'];

      return $this->last_id;
    }

    // Check if location is saved in the database
    public function checkDuplicate($tbl, $col, $args, $property = NULL){

      // Query the database for duplicates
      $sql = "
        SELECT
          $col
        FROM
          $tbl
        WHERE
          $args
      ";

      $res = $this->dbQuery($sql);

      if($property == NULL){

        return $this->dbNumRows($res);

      } else {

        $row = $res->fetch_assoc();

        return $row[$property];
      }
    }

    public function alert(){

      // Output message to the browser
      $return = '<div class="alert alert-'. $this->alert_class .'" role="alert">';
      $return .= $this->alert_message;
      $return .= '</div>';

      return $return;
    }
  }
  ?>
