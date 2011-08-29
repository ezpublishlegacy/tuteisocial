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
		
		//$nodeType = $event->attribute('data_text1');
		//$doNew = $event->attribute('data_int1');
		//$doUpdates = $event->attribute('data_int2');

        if ($versionID == 1 && $userObj->attribute('class_identifier')=='user') {

            $ini = eZINI::instance('dappsocial.ini');

            foreach ($ini->variableArray("UserExtras", "UserNodes") as $info) {
                $node = new ezpObject($info[0], $userObj->mainNodeID(), $parameters['user_id'], 2);
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