<?php

session_start();


$client = new apiClient();
// Visit https://code.google.com/apis/console to generate your
// oauth2_client_id, oauth2_client_secret, and to register your oauth2_redirect_uri.
$ini = eZINI::instance('googleapi.ini');

$client->setClientId($ini->variable("APIAccess", "ClientId"));
$client->setClientSecret($ini->variable("APIAccess", "ClientSecret"));
$ini = eZINI::instance('site.ini');
$client->setRedirectUri('http://'.$ini->variable("SiteSettings", "SiteURL").'/tuteisocial/glogin/');
$client->setApplicationName("OAuth2_Example_App");

$buzz = new apiBuzzService($client);

if (isset($_SESSION['access_token'])) {
  $client->setAccessToken($_SESSION['access_token']);
} else {
  $client->setAccessToken($client->authenticate());
}
$_SESSION['access_token'] = $client->getAccessToken();

?>
<!doctype html>
<html>
<head>
  <title>OAuth2 Sample</title>

  <link rel='stylesheet' href='css/style.css' />
</head>
<body>
<div id='container'>
  <div id='top'>
    <div id='identity'>
    <?php if ($client->getAccessToken()) {

      $me = $buzz->people->get('@me');	
      
      $ident = '<img alt="photo" src="%s"> <a href="%s">%s</a>';
      printf($ident, $me['thumbnailUrl'], $me['profileUrl'], $me['displayName']);

    }?>
    </div>
    <h1>Google APIs Client Library for PHP: OAuth2 Sample</h1>
  </div>
  <div id='main'>
<?php if ($client->getAccessToken()) {

  $activities = $buzz->activities->listActivities('@consumption', '@me');
  foreach ($activities['items'] as $activity) {
    $actor = $activity['actor'];
    echo <<<HTML
<div id='person'>
  <div><p id='name'><a href='{$actor['profileUrl']}'>{$actor['name']}</a></p></div>
  <p id='post'>{$activity['object']['content']}</p>
</div>
HTML;

  }

}
?>

<?php $_SESSION['access_token'] = $client->getAccessToken(); ?>
