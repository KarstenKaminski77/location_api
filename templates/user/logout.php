<?php

$user = new User();

$user->logout();

echo '<div class="alert alert-warning" role="alert">';
echo 'You have successfully logged out of your account.';
echo '</div>';

?>
