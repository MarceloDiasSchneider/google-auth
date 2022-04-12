<?php

ob_start();
session_start();

require __DIR__ . "/vendor/autoload.php";

use League\OAuth2\Client\Provider\Google;

echo "<h1>Google OAuth</h1>";

if (empty($_SESSION['user_data'])) {
  /**
   * GOOGLE has the Google's Api Credentials
   */
  $google = new Google(GOOGLE);
  $authUrl = $google->getAuthorizationUrl();

  isset($_GET["error"]) ? $error = $_GET["error"] : $error = null;
  isset($_GET["code"]) ? $code = $_GET["code"] : $code = null;

  if ($error) {
    echo "<p>We need your authorization to continue</p>";
  }

  if ($code) {

    $token = $google->getAccessToken("authorization_code", [
      "code" => $code
    ]);
    echo "<pre>";
    print_r($token);
    echo "</pre>";

    $user_data = $google->getResourceOwner($token);

    echo "<pre>";
    print_r($user_data);
    echo "</pre>";

    $_SESSION["user_data"] = serialize($user_data);
    header("Location: " . GOOGLE['redirectUri']);
    exit;
  }
  echo "<p>You are not authenticated</p>";

  echo "<a href=" . $authUrl . ">Google Auth</a>";
} else {
  $user_data = unserialize($_SESSION['user_data']);
  echo "<img src='" . $user_data->getAvatar() . "' alt='Avatar'>";
  echo "<p>Ben vindo " . $user_data->getFirstName() . "</p>";

  echo "<pre>";
  print_r($user_data);
  echo "</pre>";

  echo "<a href='./logout.php'>Logout</a>";

}

ob_end_flush();
