<?php

//session_destroy();exit;

function genRandomString() {
    $length = 10;
    $characters = '0123456789abcdefghijklmnopqrstuvwxyz!@#$';
    $string = '';

    for ($p = 0; $p < $length; $p++) {
        $string .= $characters[mt_rand(0, strlen($characters))];
    }

    return $string;
}

function loadImageCURL($source, $save_to) {
    $ch = curl_init($source);
    $fp = fopen($save_to, "wb");

// set URL and other appropriate options
    $options = array(CURLOPT_FILE => $fp,
        CURLOPT_HEADER => 0,
        CURLOPT_FOLLOWLOCATION => 1,
        CURLOPT_TIMEOUT => 60); // 1 minute timeout (should be enough)

    curl_setopt_array($ch, $options);

    curl_exec($ch);
    curl_close($ch);
    fclose($fp);
}

$Module = $Params['Module'];

session_start();

$ini = eZINI::instance('googleapi.ini');
$client = new apiClient();
$client->setApplicationName("GoogleLogin");
$client->setClientId($ini->variable("APIAccess", "ClientId"));
$client->setClientSecret($ini->variable("APIAccess", "ClientSecret"));
//$client->setDeveloperKey($ini->variable("APIAccess", "APIKey"));

$ini = eZINI::instance('site.ini');
$client->setRedirectUri(('http://' . $ini->variable("SiteSettings", "SiteURL") . '/social/glogin/'));

//$client->setScopes(array('https://www.googleapis.com/auth/plus.me'));
$plus = new apiBuzzService($client);

if (isset($_GET['code'])) {

    $client->authenticate();

    $_SESSION['access_token'] = $client->getAccessToken();
}



if (isset($_SESSION['access_token'])) {
    $client->setAccessToken($_SESSION['access_token']);
}



if ($client->getAccessToken()) {

    unset($_SESSION['access_token']);
    try {
        $me = $plus->people->get('@me');
    } catch (Exception $e) {
        echo 'Error: not public profile';
        eZExecution::cleanExit();
    }
    $email = null;
    foreach ($me['emails'] as $m) {
        echo $m["primary"];
        if ($m["primary"])
            $email = $m["value"];
    }


    $user = eZUser::fetchByEmail($email);

    if ($user instanceof eZUser) {

        eZUser::setCurrentlyLoggedInUser($user, $user->attribute('contentobject_id'));
        $Module->redirectTo('/');
    } else {

        $user = new ezpObject('user', 12, 14, 2);
        $name = explode(' ', $me["displayName"]);
        $first_name = $name[0];
        $last_name = implode(' ', array_slice($input, 0, 1));

        $username = $email;
        $password = genRandomString();

        $image = 'var/cache/content/' . $password;
        loadImageCURL($me['thumbnailUrl'], $image);

        $user->__set("first_name", $first_name);
        $user->__set("last_name", $last_name);
        $user->__set("user_account", "$username|$username|" . md5("$username\n$pass") . "|2");
        $user->__set("image", $image);

        $user->publish();

        unlink($image);
        $user = eZUser::fetchByEmail($email);
        eZUser::setCurrentlyLoggedInUser($user, $user->attribute('contentobject_id'));
        $Module->redirectTo('/');
    }
} else {
    $http = eZHTTPTool::instance();
    $http->redirect($client->createAuthUrl());
}
?>
