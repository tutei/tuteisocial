<?php

$tpl = eZTemplate::factory();

//$tpl->setVariable('code', $code);

$uri = eZURI::instance( eZSys::requestURI() );

$viewParameters = $uri->UserParameters();
$tpl->setVariable( 'view_parameters', $viewParameters );

$Result = array();
$Result['path'] = array(array('url' => false,
        'text' => 'Dapp Social'),
    array('url' => false,
        'text' => 'Approve'));
$Result['content'] = $tpl->fetch("design:dappsocial/approve.tpl");
?>
