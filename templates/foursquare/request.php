  <?php

  // Instantiate the object
  $foursquare = new Foursquare();

  if(!isset($_SESSION['user_id'])){

    // Output message to the browser
    echo '<div class="container">';
    echo '<div class="row">';
    echo '<div class="alert alert-warning" role="alert">';
    echo 'Please login to search for landmarks';
    echo '</div>';
    echo '</div>';
    echo '</div>';

    ?>

    <!-- User login Form -->
    <div class="container jumbotron search-jumbotron">
      <div class="row">
        <div class="col-xs-12">
          <h4 class="no-margin">User Login</h4>
        </div>
      </div>
      <form method="post" action="<?php echo HOST . PATH .'user/login'; ?>">
        <div class="row">
          <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6 col-xl-6">
            <input type="text" name="username" class="form-control" placeholder="Username" required>
          </div>
          <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6 col-xl-6">
            <input type="text" name="password" class="form-control" placeholder="Email address" required>
          </div>
        </div>
        <div class="row">
          <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6 col-xl-6">
            <a href="<?php echo HOST . PATH . 'user/create'; ?>" class="btn btn-success">Register an accoutnt</a>
          </div>
          <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6 col-xl-6">
            <input type="submit" name="login" class="btn btn-blue pull-right no-margin" value="LOGIN">
          </div>
        </div>
      </form>
    </div>
    <!-- End User login Form -->

  <?php

  } else {

   ?>
  <!-- Search Form -->
  <div class="jumbotron search-jumbotron">
    <div class="row">
      <div class="col-xs-12">
        <h4 class="no-margin">Enter a city to find landmarks.</h4>
      </div>
    </div>
    <div class="row">
      <form method="get" enctype="application/x-www-form-urlencoded">
        <div class="col-xs-12">
          <div class="input-group add-on">
            <input type="text" class="form-control" placeholder="Search" name="city">
            <div class="input-group-btn">
              <button name="search" class="btn btn-blue" type="submit"><i class="glyphicon glyphicon-search"></i> SEARCH</button>
            </div>
          </div>
        </div>
      </form>
    </div>
  </div>
  <!-- End Search Form -->

  <?php

  }

  // Set the search properties
  $foursquare->setHost('https://api.foursquare.com/v2/venues/search?');
  $foursquare->setCity($foursquare->sanitise($search));
  $foursquare->setCategory('4bf58dd8d48988d12d941735');

  echo $foursquare->htmlOutput();

?>

<!-- Recent Searches -->
<div class="row">
  <div class="col-xs-12">
    <h4>Recently Found</h4>
  </div>
  <div class="col-xs-12">
    <?php echo $foursquare->getRecent(); ?>
  </div>
</div>
<!-- Recent Searches -->
