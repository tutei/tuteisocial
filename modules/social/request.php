<?php

$tpl = eZTemplate::factory();

//$tpl->setVariable('code', $code);

$uri = eZURI::instance( eZSys::requestURI() );

$viewParameters = $uri->UserParameters();
$tpl->setVariable( 'view_parameters', $viewParameters );

$Result = array();
$Result['path'] = array(array('url' => false,
        'text' => 'Tutei Social'),
    array('url' => false,
        'text' => 'Request'));
$Result['content'] = $tpl->fetch("design:tuteisocial/request.tpl");
?>
