<?php
//session_destroy();exit;
session_start();

$ini = eZINI::instance('googleapi.ini');
$client = new apiClient();
$client->setApplicationName("Google+ PHP Starter Application");
$client->setClientId($ini->variable("APIAccess", "ClientId"));
$client->setClientSecret($ini->variable("APIAccess", "ClientSecret"));
//$client->setDeveloperKey($ini->variable("APIAccess", "APIKey"));
echo $ini->variable("APIAccess", "APIKey");
$ini = eZINI::instance('site.ini');
$client->setRedirectUri(('http://' . $ini->variable("SiteSettings", "SiteURL") . '/social/glogin/'));

//$client->setScopes(array('https://www.googleapis.com/auth/plus.me'));
$plus = new apiBuzzService($client);

if (isset($_REQUEST['logout'])) {
    unset($_SESSION['access_token']);
}



if (isset($_GET['code'])) {
    $client->authenticate();
    $_SESSION['access_token'] = $client->getAccessToken();
    //header('Location: http://' . $_SERVER['HTTP_HOST'] . $_SERVER['PHP_SELF']);
}



if (isset($_SESSION['access_token'])) {
    $client->setAccessToken($_SESSION['access_token']);
}



if ($client->getAccessToken()) {

    $me = $plus->people->get('@me');
    var_dump($me);

    /*
    $optParams = array('maxResults' => 100);
    $activities = $plus->activities->listActivities('@me', '@public', $optParams);

    // The access token may have been updated lazily.
     * 
     */
    $_SESSION['access_token'] = $client->getAccessToken();
} else {
    $http = eZHTTPTool::instance();
    $http->redirect($client->createAuthUrl());
}
eZExecution::cleanExit();
?>
