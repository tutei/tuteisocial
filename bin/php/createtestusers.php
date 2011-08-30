<?php

function createTestUser($first_name, $last_name, $username, $pass ) {

    $user = new ezpObject('user', 12, 14,2);

    $user->__set("first_name", $first_name);
    $user->__set("last_name", $last_name);
    $user->__set("user_account", "$username|$username@thepowerpuffgirls.com|" . md5("$username\n$pass") . "|2");
    $user->__set("image", "extension/tuteisocial/test_images/$username.jpg");
    $user->publish();
}

function connectUser($usernameA, $usernameB){


    $contentObject = eZContentObject::fetch(eZUser::fetchByName($usernameA)->attribute( 'contentobject_id' ));

    $attribs = $contentObject->contentObjectAttributes();
    $loopLength = count($attribs);

    for ($i = 0; $i < $loopLength; $i++) {


        switch ($attribs[$i]->attribute("data_type_string")) {

            case 'relationship':

                $res = $attribs[$i]->fromString("request|" . eZUser::fetchByName($usernameB)->attribute( 'contentobject_id' ));

                break;
        }
    }




}

set_time_limit(0);
require 'autoload.php';


$cli = eZCLI::instance();
$script = eZScript::instance(array('description' => ( "eZPersistentObject tutorial.\n\n"),
            'use-modules' => true,
            'use-extensions' => true));

$script->startup();
$script->initialize();


// start



createTestUser("Blossom", "The Powerpuff Girls", "blossom", "admin");
createTestUser("Bubbles", "The Powerpuff Girls", "bubbles", "admin");
createTestUser("Buttercup", "The Powerpuff Girls", "buttercup", "admin");
createTestUser("Professor", "Utonium", "professor", "admin");
createTestUser("Mayor", "of Townsville", "mayor", "admin");
createTestUser("Ms. Sara", "Bellum", "sara", "admin");
createTestUser("Ms. Keane", "Keane", "keane", "admin");
createTestUser("Narrator", "Narrator", "narrator", "admin");
createTestUser("Talking", "Dog", "dog", "admin");
createTestUser("Mojo", "Jojo", "jojo", "admin");
createTestUser("Fuzzy", "Lumpkins", "fuzzy", "admin");
createTestUser("Him", "Him", "him", "admin");
createTestUser("Princess", "Morbucks", "princess", "admin");
createTestUser("Ace", "The Gangrene Gang", "ace", "admin");
createTestUser("Snake", "The Gangrene Gang", "snake", "admin");
createTestUser("Grubber", "The Gangrene Gang", "grubber", "admin");
createTestUser("Big Billy", "The Gangrene Gang", "billy", "admin");
createTestUser("Lil' Arturo", "The Gangrene Gang", "arturo", "admin");
createTestUser("Bossman", "The Amoeba Boys", "bossman", "admin");
createTestUser("Junior", "The Amoeba Boys", "junior", "admin");
createTestUser("Slim", "The Amoeba Boys", "slim", "admin");
createTestUser("Sedusa", "Sedusa", "sedusa", "admin");
createTestUser("Brick", "The Rowdyruff Boys", "brick", "admin");
createTestUser("Boomer", "The Rowdyruff Boys", "boomer", "admin");
createTestUser("Butch", "The Rowdyruff Boys", "butch", "admin");



connectUser("blossom", "bubbles");
connectUser("bubbles", "blossom");
connectUser("blossom", "buttercup");
connectUser("buttercup", "blossom");
connectUser("blossom", "professor");
connectUser("professor", "blossom");
connectUser("buttercup", "bubbles");
connectUser("bubbles", "buttercup");
connectUser("buttercup", "professor");
connectUser("professor", "buttercup");
connectUser("bubbles", "professor");
connectUser("professor", "bubbles");

connectUser("bubbles", "mayor");
connectUser("mayor", "bubbles");
connectUser("bubbles", "sara");
connectUser("sara", "bubbles");
connectUser("bubbles", "keane");
connectUser("keane", "bubbles");
connectUser("bubbles", "narrator");
connectUser("narrator", "bubbles");
connectUser("bubbles", "dog");
connectUser("dog", "bubbles");
connectUser("bubbles", "jojo");
connectUser("jojo", "bubbles");
connectUser("bubbles", "fuzzy");
connectUser("fuzzy", "bubbles");
connectUser("bubbles", "him");
connectUser("him", "bubbles");
connectUser("bubbles", "princess");
connectUser("princess", "bubbles");
connectUser("bubbles", "ace");
connectUser("ace", "bubbles");
connectUser("bubbles", "snake");
connectUser("snake", "bubbles");
connectUser("bubbles", "grubber");
connectUser("grubber", "bubbles");
connectUser("bubbles", "billy");
connectUser("billy", "bubbles");
connectUser("bubbles", "arturo");
connectUser("arturo", "bubbles");
connectUser("bubbles", "bossman");
connectUser("bossman", "bubbles");
connectUser("bubbles", "junior");
connectUser("junior", "bubbles");
connectUser("bubbles", "slim");
connectUser("slim", "bubbles");
connectUser("bubbles", "sedusa");
connectUser("sedusa", "bubbles");
connectUser("bubbles", "brick");
connectUser("brick", "bubbles");
connectUser("bubbles", "boomer");
connectUser("boomer", "bubbles");
connectUser("bubbles", "butch");
connectUser("butch", "bubbles");


$script->shutdown();
?>
