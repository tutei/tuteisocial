
<?php

set_time_limit(0);
require 'autoload.php';


$cli = eZCLI::instance();
$script = eZScript::instance(array('description' => ( "Role.\n\n"),
            'use-modules' => true,
            'use-extensions' => true));

$script->startup();
$script->initialize();

//$role=eZRole::fetch(5);
// Let's get the role we want change by name
$role = eZRole::fetchByName('TuteiMember');
if (!$role instanceof eZRole) {
    $role = eZRole::create('TuteiMember');
    $role->store();

// Now let's add some policies, we pass the module, the function
// and the limitation array
    $class = eZContentClass::fetchByIdentifier('blog_post');
    $parentClass = eZContentClass::fetchByIdentifier('blog');
    $role->appendPolicy("content", "create", array(
        "Class" => array($class->ID),
        "ParentClass" => array($parentClass->ID),
        "ParentOwner" => array(1)
        
    ));
    
    $class = eZContentClass::fetchByIdentifier('panda3d');
    $parentClass = eZContentClass::fetchByIdentifier('panda3d_folder');
    $role->appendPolicy("content", "create", array(
        "Class" => array($class->ID),
        "ParentClass" => array($parentClass->ID),
        "ParentOwner" => array(1)
        
    ));
    
    $class = eZContentClass::fetchByIdentifier('shiva3d');
    $parentClass = eZContentClass::fetchByIdentifier('shiva3d_folder');
    $role->appendPolicy("content", "create", array(
        "Class" => array($class->ID),
        "ParentClass" => array($parentClass->ID),
        "ParentOwner" => array(1)
        
    ));
    
    $class = eZContentClass::fetchByIdentifier('event');
    $parentClass = eZContentClass::fetchByIdentifier('event_calendar');
    $role->appendPolicy("content", "create", array(
        "Class" => array($class->ID),
        "ParentClass" => array($parentClass->ID),
        "ParentOwner" => array(1)
        
    ));
    
    $class = eZContentClass::fetchByIdentifier('image');
    $parentClass = eZContentClass::fetchByIdentifier('gallery');
    $role->appendPolicy("content", "create", array(
        "Class" => array($class->ID),
        "ParentClass" => array($parentClass->ID),
        "ParentOwner" => array(1)
        
    ));
    
    $class = eZContentClass::fetchByIdentifier('obj');
    $parentClass = eZContentClass::fetchByIdentifier('obj_folder');
    $role->appendPolicy("content", "create", array(
        "Class" => array($class->ID),
        "ParentClass" => array($parentClass->ID),
        "ParentOwner" => array(1)
        
    ));
    
    $class = eZContentClass::fetchByIdentifier('flash');
    $parentClass = eZContentClass::fetchByIdentifier('flash_folder');
    $role->appendPolicy("content", "create", array(
        "Class" => array($class->ID),
        "ParentClass" => array($parentClass->ID),
        "ParentOwner" => array(1)
        
    ));
    
    $class = eZContentClass::fetchByIdentifier('unity');
    $parentClass = eZContentClass::fetchByIdentifier('unity_folder');
    $role->appendPolicy("content", "create", array(
        "Class" => array($class->ID),
        "ParentClass" => array($parentClass->ID),
        "ParentOwner" => array(1)
        
    ));
    
    $class = array();
    foreach(array('comment' , 'blog_post' , 'flash' , 'image' , 'forum_topic' , 'forum_reply' , 'event' ,
                'unity' , 'obj' , 'shiva3d' , 'panda3d') as $item) {
        $item = eZContentClass::fetchByIdentifier($item);
        $class[]=$item->ID;
    }

    $role->appendPolicy("content", "edit", array(
        "Class" => $class,
        "Owner" => array(1)
        
    ));
    
    $role->appendPolicy("content", "remove", array(
        "Class" => $class,
        "Owner" => array(1)
        
    ));
    $role->appendPolicy("content", "read", array(
        "Section" => array(2)
        
    ));
    $role->appendPolicy("social",'*');
    $role->appendPolicy("ezjscore","call");
    $role->appendPolicy("ezoe","editor");
    
    $role->assignToUser(11);
    
    $role = eZRole::fetchByName('Anonymous');
    $role->appendPolicy("social","glogin");
    
    
}

$script->shutdown();
?>

