<?php

// just a quick test
// user php extension/tuteisocial/bin/php/test.php

set_time_limit(0);
require 'autoload.php';


$cli = eZCLI::instance();
$script = eZScript::instance(array('description' => ( "eZPersistentObject tutorial.\n\n"),
            'use-modules' => true,
            'use-extensions' => true));

$script->startup();
$script->initialize();

$user = eZUser::currentUser();
connect_buddy($cli, 10, 14);
//connect_buddy($cli, 14, 10);

//$contentObject = eZContentObject::fetch($userID);


function connect_buddy($cli, $userID, $rID) {
    $contentObject = eZContentObject::fetch($userID);



    $attribs = $contentObject->contentObjectAttributes();
    $loopLength = count($attribs);

    for ($i = 0; $i < $loopLength; $i++) {


        switch ($attribs[$i]->attribute("data_type_string")) {

            case 'relationship':

                $cli->output($attribs[$i]->content());
                $res = $attribs[$i]->fromString("request|" . $rID);
                $cli->output($res);


                break;
        }
    }
}

// Code Goes Here
/*
  $simpleObj = UserRelationshipsObject::create( array( 'requestee_id' => 1,
  'requester_id' => 3,
  'rtid' => 2
  ));
  $simpleObj->store();



  $simpleObj = UserRelationshipElaborationsObject::create( array( 'rid' => 1,
  'elaboration' => 'hey'
  ));
  $simpleObj->store();


  $simpleObj = UserRelationshipElaborationsObject::create( array( 'rid' => 2
  ));
  $simpleObj->store();


  $simpleObj = UserRelationshipTypesObject::create(array('name' => 'test', 'plural_name' => 'tests'
  ));
  $simpleObj->store();
 * 
 */

$script->shutdown();
?>