<?php

if(isset($_POST['login'])){

  $user = new User();

  // get the raw post data
  $data = json_decode(file_get_contents("php://input"));

  // Set the user properties
  $user->username = $user->sanitise($_POST['username']);
  $user->password = $user->sanitise(md5($_POST['password']));

  // create the user
  if($user->login()){

    header('Location: '. HOST . PATH);

  } else {

    echo '<div class="container">';
    echo '<div class="row">';
    echo '<div class="alert alert-danger" role="alert">';
    echo 'Login failed, please try again.';
    echo '</div>';
    echo '</div>';
    echo '</div>';
  }
}

?>

<!-- User login Form -->
<div class="container jumbotron search-jumbotron">
  <div class="row">
    <div class="col-xs-12">
      <h4 class="no-margin">User Login</h4>
    </div>
  </div>
  <form method="post">
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
