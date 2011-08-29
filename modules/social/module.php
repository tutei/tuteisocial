<?php
$Module = array(
        "name" => 'social'
);

$ViewList = array();
$ViewList['request'] = array(
        'functions' => array( 'request' ),
        'script'  => 'request.php'
);

$ViewList['approve'] = array(
        'functions' => array( 'approve' ),
        'script'  => 'approve.php'
);

$ViewList['delete'] = array(
        'functions' => array( 'delete' ),
        'script'  => 'delete.php'
);

$FunctionList[ 'request' ] = array();
$FunctionList[ 'approve' ] = array();
$FunctionList[ 'delete' ] = array();


?>