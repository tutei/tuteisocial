<?php

$FunctionList = array();

$FunctionList['list'] = array('name' => 'list',
    'operation_types' => array('read'),
    'call_method' => array('include_file' => 'extension/dappsocial/modules/social/dappsocialfunctioncollection.php',
        'class' => 'DappSocialFunctionCollection',
        'method' => 'fetchList'),
    'parameter_type' => 'standard',
    'parameters' => array(
        array(
            'name' => 'user_id',
            'type' => 'integer',
            'required' => true),
        array(
            'name' => 'offset',
            'type' => 'integer',
            'required' => false,
            'default' => 0),
        array(
            'name' => 'limit',
            'type' => 'integer',
            'required' => false,
            'default' => 30)
        ));

$FunctionList['requests'] = array('name' => 'requests',
    'operation_types' => array('read'),
    'call_method' => array('include_file' => 'extension/dappsocial/modules/social/dappsocialfunctioncollection.php',
        'class' => 'DappSocialFunctionCollection',
        'method' => 'fetchRequests'),
    'parameter_type' => 'standard',
    'parameters' => array(
        array(
            'name' => 'user_id',
            'type' => 'integer',
            'required' => true),
        array(
            'name' => 'offset',
            'type' => 'integer',
            'required' => false,
            'default' => 0),
        array(
            'name' => 'limit',
            'type' => 'integer',
            'required' => false,
            'default' => 30)
        ));
?>
