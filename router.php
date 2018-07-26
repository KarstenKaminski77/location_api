<?php

  $url = trim($_SERVER['REQUEST_URI'], '/');

  // Drop path from url
  // Add 1 to starting point so we don't pick up the forwatrd slash
  $url = substr($url, strlen(PATH));

  $explode_url = explode('/', $url);

  // Make sure the class exists
  if(file_exists('../class/' . strtolower($explode_url[0]) . '.php')){

    // Set the class variable
    $class = strtolower($explode_url[0]);

    if(!empty($explode_url[1])){

      // Set the action / method variable;
      $method = strtolower($explode_url[1]);
    }

    if(!empty($explode_url[2])){

      // Set the parameter variable
      $city_id = strtolower($explode_url[2]);
    }

    if(!empty($explode_url[3])){

      // Set the parameter variable
      $city = strtolower($explode_url[3]);
    }

    if(!empty($explode_url[4])){

      // Set the parameter variable
      $landmark = strtolower($explode_url[4]);
    }

  } else {

    $class = 'foursquare';
    $method = 'request';
    $param = 'Durban';
    $search = $url;
  }

 ?>
