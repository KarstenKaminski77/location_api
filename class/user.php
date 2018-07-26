<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require_once('base.php');

class User extends Base {

  // Set the object properties
  protected $mysqli;
  public $first_name;
  public $last_name;
  public $email;
  public $username;
  public $password;
  public $response;

  // Generate eandom passwords
  public function password(){

    // Possible characters for password
    $chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789!@#$%^&*()_-=+;:,.?";

    // Create a random password
    $this->password = substr(str_shuffle($chars), 0, 10);
  }

  // Send email with login credentials
  public function email(){

    //Load Composer's autoloader
    require '../vendor/autoload.php';

    $mail = new PHPMailer(true);

    try {
        //Server settings
        $mail->SMTPDebug = 0;
        $mail->isSMTP();
        $mail->Host = EMAIL_HOST;
        $mail->SMTPAuth = true;
        $mail->Username = EMAIL_USERNAME;
        $mail->Password = EMAIL_PASSWORD;
        $mail->Port = EMAIL_PORT;

        //Recipients
        $mail->setFrom('test@kwd.co.za', 'Mailer');
        $mail->addAddress($this->email, 'Joe User');
        $mail->addReplyTo('test@kwd.co.za', 'Information');

        $message = '
        <body style="font-family:Arial; font-size:12px; line-height:18px; color:#43525a">
        <div style="font-family:Arial; font-size:12px; line-height:18px; color:#43525a; padding-left:25px"><P>
        <table border="0" cellspacing="10" cellpadding="0" style="color:#43525a; font-family:arial; font-size:12px">
          <tr>
            <td colspan="2">Your login credentials for the Foursquare Flickr API client.</td>
          </tr>
        </table>
        <table border="0" cellspacing="10" cellpadding="0" style="color:#43525a; font-family:arial; font-size:12px">
          <tr>
            <td colspan="2"></td>
          </tr>
          <tr>
            <td width="50"><strong>Username</strong></td>
            <td>'. $this->username .'</td>
          </tr>
          <tr>
            <td><strong>Password</strong></td>
            <td>'. $this->password .'</td>
          </tr>
        </table>
        </p></div></body>';

        //Content
        $mail->isHTML(true);                                  // Set email format to HTML
        $mail->Subject = 'API Login Credentials';
        $mail->Body    = $message;
        $mail->AltBody = 'This is the body in plain text for non-HTML mail clients';

        $mail->send();

        $this->response = '<div class="alert alert-warning" role="alert">';
        $this->response .=  "Your login credentials have been sent to " . $this->email;
        $this->response .= '</div>';

      } catch (Exception $e) {

        // Output the username and password to the browser if the email fails
        $this->response = '<div class="alert alert-warning" role="alert">';
        $this->response .= 'Message could not be sent. Mailer Error: '. $mail->ErrorInfo;
        $this->response .= "Unable to send email to " . $this->email . "<br><br>";
        $this->response .= "<b>Username:</b> " . $this->username . "<br>";
        $this->response .= "<b>Password:</b> " . $this->password;
        $this->response .= '</div>';
    }
  }

  // Create user account.
  public function create(){

    $this->password();

    // Insert query statement
    $sql = "
      INSERT INTO
        user
        (
          first_name,
          last_name,
          email,
          username,
          password,
          created
        )
      VALUES
        (
          '". $this->first_name ."',
          '". $this->last_name ."',
          '". $this->email ."',
          '". $this->username ."',
          '". md5($this->password) ."',
          '". date('Y-m-d H:i:s') ."'
        )";

    // Execute the query
    $query = $this->dbQuery($sql);

    // Send email with username amd password
    $this->email();

    // Return the output to the client
    return $this->response;
  }

  // User Login
  public function login(){

    // Ensure both username and password fields have been entered.
    if(!empty($this->username) && !empty($this->password)){

      // Query the database on the usernme and password
      $sql = "
        SELECT
          u.id,
          u.username,
          u.password,
          u.first_name,
          u.last_name
        FROM
          user u
        WHERE
          u.username = '". $this->username ."'
        AND
          u.password = '". $this->password ."'
      ";
    }

    $res = $this->dbQuery($sql);
    $row = $res->fetch_assoc();

    // If the user exists log them in
    if($this->dbNumRows($res) >= 1){

      // Set the user sessions.
      $_SESSION['user_id'] = $row['id'];
      $_SESSION['first_name'] = $row['first_name'];
      $_SESSION['last_name'] = $row['last_name'];

      return TRUE;

    } else {

      return FALSE;
    }
  }

  // Logout and sestroy the user session
  public function logout(){

    session_destroy();

    header('Location: ' . HOST . PATH .'user/login');
  }
}
?>
