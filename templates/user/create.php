<?php

if(isset($_POST['register'])){

  $user = new User();

  // get the raw post data
  $data = json_decode(file_get_contents("php://input"));

  // Set the user properties
  $user->first_name = $user->sanitise($_POST['first_name']);
  $user->last_name = $user->sanitise($_POST['last_name']);
  $user->email = $user->sanitise($_POST['email']);
  $user->username = $user->sanitise($_POST['username']);
  $user->created = date('Y-m-d H:i:s');

  // create the user
  echo $user->create();
}
?>

<!-- User Registration Form -->
<div class="container jumbotron search-jumbotron">
  <div class="row">
    <div class="col-xs-12">
      <h4 class="no-margin">Register Your Account.</h4>
    </div>
  </div>
  <form method="post">
    <div class="row">
      <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6 col-xl-6">
        <input type="text" name="first_name" class="form-control" placeholder="Your first name.">
      </div>
      <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6 col-xl-6">
        <input type="text" name="last_name" class="form-control" placeholder="Your last name.">
      </div>
    </div>
    <div class="row">
      <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6 col-xl-6">
        <input type="text" name="username" class="form-control" placeholder="Username">
      </div>
      <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6 col-xl-6">
        <input type="text" name="email" class="form-control" placeholder="Email address">
      </div>
    </div>
    <div class="row">
      <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 col-xl-12">
        <input type="submit" name="register" class="btn btn-blue no-margin pull-right" value="REGISTER">
      </div>
    </div>
  </form>
</div>
<!-- End User Registration Form -->
