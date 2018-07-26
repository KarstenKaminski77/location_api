<?php

// Get the router and configuration settings
require_once('../config/config.php');
require_once('../router.php');
require_once('../class/' . $class . '.php');

?>
<!DOCTYPE html>
<html>

<head>
  <meta charset="UTF-8">
  <title>Landmarks API</title>

  <meta name="viewport" content="width=device-width, initial-scale=1">

  <!-- Inlude jQuery -->
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
  <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>

  <!-- Include Tipped -->
  <script type="text/javascript" src="<?php echo JS . 'tipped/tipped.js'; ?>"></script>
  <link rel="stylesheet" type="text/css" href="<?php echo CSS . 'tipped/tipped.css'; ?>"/>

  <!-- Include Bootstrap Files -->
  <link href="//netdna.bootstrapcdn.com/bootstrap/3.1.0/css/bootstrap.min.css" rel="stylesheet" id="bootstrap-css">
  <script src="//netdna.bootstrapcdn.com/bootstrap/3.1.0/js/bootstrap.min.js"></script>

  <!-- Include Googlr Fonts -->
  <link href="https://fonts.googleapis.com/css?family=Lato:100,400" rel="stylesheet">

  <!-- Include CSS Files -->
  <link rel="stylesheet" href="<?php echo CSS . 'layout.css'; ?>">
  <link rel="stylesheet" href="<?php echo CSS . 'navbar.css'; ?>">

</head>

<body>

  <!-- Navbar -->
  <nav class="navbar navbar-default">
    <div class="container">
      <div class="navbar-header">
        <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
          <span class="sr-only">Toggle navigation</span>
          <span class="icon-bar"></span>
          <span class="icon-bar"></span>
          <span class="icon-bar"></span>
        </button>
        <a class="navbar-brand" href="#"><b>LANDMARKS</b> <span class="navbar-brand-blue">API</span></a>
      </div>
      <div id="navbar" class="navbar-collapse collapse">
        <ul class="nav navbar-nav navbar-right">
          <li><a href="<?php echo HOST . PATH; ?>">HOME</a></li>

          <?php if(isset($_SESSION['user_id'])){ ?>
            <li><a href="<?php echo HOST . PATH . 'user/logout'; ?>">MY LOCATIONS</a></li>
            <li><a href="<?php echo HOST . PATH . 'user/logout'; ?>">LOGOUT</a></li>
          <?php } else { ?>
            <li><a href="<?php echo HOST . PATH . 'user/login'; ?>">LOGIN</a></li>
          <?php } ?>

        </ul>
      </div>
    </div>
  </nav>
  <!-- End Navbar -->

  <!-- Main Body -->
  <div class="container">
    <?php include(TEMPLATES . $class . '/' . $method . '.php'); ?>
  </div>
  <!-- End Main Body -->

  <!-- Footer -->
  <footer>
    <div class="navbar navbar-default navbar-fixed-bottom footer">
    <div class="container">
      <div class="row">
        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 col-xl-12">
          <p class="navbar-text text-center">© 2018 - FOURSQUARE API Client By Karsten Kaminski
          </p>
        </div>
      </div>
    </div>
  </footer>
  <!-- End Footer -->

</body>

</html>
