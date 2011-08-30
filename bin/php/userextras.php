<?php

function hasSubNode($nodeID, $name) {
    $parent = eZContentObjectTreeNode::fetch($nodeID);

    if(count($parent->childrenByName($name))!=0) return true;
    else return false;
}

set_time_limit(0);
require 'autoload.php';


$cli = eZCLI::instance();
$script = eZScript::instance(array('description' => ( "eZPersistentObject tutorial.\n\n"),
            'use-modules' => true,
            'use-extensions' => true));

$script->startup();
$script->initialize();

$nodes = eZUser::fetchContentList();
foreach ($nodes as $usernode) {
    /*
      $dataMap = & $object->attribute('data_map');
      $userSetting = eZUserSetting::fetch($object->attribute('id'));
     * 
     */
    if ($usernode['id'] != 10) {

        $userObj = eZContentObject::fetch($usernode['id']);
        $cli->output($userObj->attribute('name'));

        $ini = eZINI::instance('tuteisocial.ini');

        foreach ($ini->variableArray("UserExtras", "UserNodes") as $info) {
            if (!hasSubNode($userObj->mainNodeID(), $info[2])) {
                $node = new ezpObject($info[0], $userObj->mainNodeID(), $usernode['id'], 2);
                $node->__set($info[1], $info[2]);
                $node->publish();
            }
        }
    }
}
$script->shutdown();
?>