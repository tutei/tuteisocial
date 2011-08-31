<?php

class CreateUserExtrasType extends eZWorkflowEventType {
    const WORKFLOW_TYPE_STRING = "createuserextras";

    function CreateUserExtrasType() {
        $this->eZWorkflowEventType(CreateUserExtrasType::WORKFLOW_TYPE_STRING, "CreateUserExtras");
        /* definir os trigger possiveis para o workflow aqui */
        $this->setTriggerTypes(array('content' => array('publish' => array('after'))));
    }

    function execute($process, $event) {
        /* aqui vai o código do workflow */
		
        $parameters = $process->attribute( 'parameter_list' );
		
		
	$versionID =& $parameters['version'];
        $userObj = eZContentObject::fetch( $parameters['object_id'] );
		

        if ($versionID == 1 && $userObj->attribute('class_identifier')=='user') {

            $ini = eZINI::instance('tuteisocial.ini');

            foreach ($ini->variableArray("UserExtras", "UserNodes") as $info) {
                $node = new ezpObject($info[0], $userObj->mainNodeID(), $userObj->attribute('id'), 2);
                $node->__set($info[1], $info[2]);
                $node->publish();
            }
        }



        /* verificar quais tipos de status existentes em:
          kernel/classes/ezworkflowtype.php
         */
        return eZWorkflowType::STATUS_ACCEPTED;
    }

}

eZWorkflowEventType::registerEventType(CreateUserExtrasType::WORKFLOW_TYPE_STRING, "CreateUserExtrasType");
?>