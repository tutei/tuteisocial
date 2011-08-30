<?php


$eZTemplateOperatorArray = array();


$eZTemplateOperatorArray[] = array('script' => 'extension/tuteisocial/autoloads/tuteisocialtemplateoperators.php',
    'class' => 'TuteiSocialTemplateOperators',
    'operator_names' => array('is_friend', 'is_pending', 'get_elaboration'));
	
$eZTemplateOperatorArray[] = array( 'script' => 'extension/tuteisocial/autoloads/qavotetemplateoperators.php',
                                    'class' => 'qaVoteTemplateOperators',
                                    'operator_names' => array( 'fetch_qavote_data',
                                                               'fetch_qavote_stats',
                                                               'fetch_by_qavote'
) );

?>
