<?php

//
// ## BEGIN COPYRIGHT, LICENSE AND WARRANTY NOTICE ##
// SOFTWARE NAME: Dapp Social
// SOFTWARE RELEASE: 0.0.1
// COPYRIGHT NOTICE: Copyright (C) 2011 Thiago Campos Viana
// SOFTWARE LICENSE: GNU General Public License v2.0
// NOTICE: >
//   This program is free software; you can redistribute it and/or
//   modify it under the terms of version 2.0  of the GNU General
//   Public License as published by the Free Software Foundation.
//
//   This program is distributed in the hope that it will be useful,
//   but WITHOUT ANY WARRANTY; without even the implied warranty of
//   MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
//   GNU General Public License for more details.
//
//   You should have received a copy of version 2.0 of the GNU General
//   Public License along with this program; if not, write to the Free
//   Software Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston,
//   MA 02110-1301, USA.
//
//
// ## END COPYRIGHT, LICENSE AND WARRANTY NOTICE ##
//

/* Actions: approve, delete, request */

class RelationshipType extends eZDataType {
    const DATA_TYPE_STRING = "relationship";

    //  Construtor

    function RelationshipType() {
        $this->eZDataType(self::DATA_TYPE_STRING, ezpI18n::tr('extension/dappsocial/datatype', 'Relationship', 'Datatype name'), array('serialize_supported' => true));
    }

    function initializeObjectAttribute($contentObjectAttribute, $currentVersion, $originalContentObjectAttribute) {

        /* data_int stores the total user relationships */
        if ($originalContentObjectAttribute->attribute("data_int") != NULL) {
            $dataInt = $originalContentObjectAttribute->attribute("data_int");
            $contentObjectAttribute->setAttribute("data_int", $dataInt);
        } else {

            $contentObjectAttribute->setAttribute("data_int", 0);
        }
    }

    // Validates input on content object level
    // return eZInputValidator::STATE_ACCEPTED or eZInputValidator::STATE_INVALID if
    //         the values are accepted or not

    function validateObjectAttributeHTTPInput($http, $base, $contentObjectAttribute) {
        return eZInputValidator::STATE_ACCEPTED;
    }

    // Fetches all variables from the object
    // return true if fetching of class attributes are successfull, false if not

    function fetchObjectAttributeHTTPInput($http, $base, $contentObjectAttribute) {
        return true;
    }

    // Returns the content, the total user relationships.

    function objectAttributeContent($contentObjectAttribute) {

        //return array('total' => $contentObjectAttribute->attribute("data_int"));

        return $contentObjectAttribute->attribute("data_int");
    }

    // Returns the meta data used for storing search indeces.

    function metaData($contentObjectAttribute) {
        return $contentObjectAttribute->attribute("data_int");
    }

    function toString($contentObjectAttribute) {
        return $contentObjectAttribute->attribute('data_int');
    }

    function fromString($contentObjectAttribute, $string) {
        // string est� no formato exp|objectID
        $actionData = explode("|", $string);

        $action = $actionData[0];

        switch ($action) {
            case 'approve':
                RelationshipType::approve($contentObjectAttribute, $actionData[1]);
                break;
            case 'request':
                RelationshipType::request($contentObjectAttribute, $actionData[1]);
                break;
            case 'delete':
                RelationshipType::delete($contentObjectAttribute, $actionData[1]);
                break;
        }

        $contentObjectAttribute->store();
        // retorna o total de exp
        return $contentObjectAttribute->attribute('data_int');
    }

    function hasObjectAttributeContent($contentObjectAttribute) {
        return $contentObjectAttribute->attribute('data_int') !== null;
    }

    // return true if the datatype can be indexed

    function isIndexable() {
        return false;
    }

    static function approve($contentObjectAttribute, $rid) {
        $userID = $contentObjectAttribute->attribute("contentobject_id");
        // check if there's an invite
        $cond = array('requester_id' => $rid, 'requestee_id' => $userID, 'approved' => 0);
        $checkObj = eZPersistentObject::fetchObject(UserRelationshipsObject::definition(), null, $cond);

        if (!$checkObj instanceof UserRelationshipsObject)
            return false;

        RelationshipType::removeElaboration($checkObj->attribute('rid'));

        $checkObj->setAttribute('approved', 1);
        $checkObj->setAttribute('updated_at', time());
        $checkObj->store();

        $newObj = UserRelationshipsObject::create(array('requester_id' => $userID,
                    'requestee_id' => $rid, 'created_at' => time(),
                    'updated_at' => time(), 'approved' => 1
                ));
        $newObj->store();

        RelationshipType::setCounter($rid, 1);
        RelationshipType::setCounter($userID, 1);
        return true;
    }

    static function request($contentObjectAttribute, $requesteeID, $elaboration = '') {
        // Check if user is not creating a request for himself
        $userID = $contentObjectAttribute->attribute("contentobject_id");
        if ($userID == $requesteeID)
            return false;

        // check if the requesteeID is valid
        $requesteeUserObj = eZUser::fetch($requesteeID);
        if (!$requesteeUserObj instanceof eZUser)
            return false;


        // Check if the user has been invited by requestee
        $cond = array('requester_id' => $requesteeID, 'requestee_id' => $userID);
        $checkObj = eZPersistentObject::fetchObject(UserRelationshipsObject::definition(), null, $cond);

        if ($checkObj instanceof UserRelationshipsObject)
            return RelationshipType::approve($contentObjectAttribute, $requesteeID);


        // TODO update elaboration
        // Check if the user already created this invite
        $cond = array('requester_id' => $userID, 'requestee_id' => $requesteeID);
        $checkObj = eZPersistentObject::fetchObject(UserRelationshipsObject::definition(), null, $cond);
        if ($checkObj instanceof UserRelationshipsObject)
            return false;

        // Creates a new request
        $newObj = UserRelationshipsObject::create(array('requester_id' => $userID, 'requestee_id' => $requesteeID,
                    'created_at' => time(), 'updated_at' => time()
                ));
        $newObj->store();



        if ($elaboration != '') {
            $ini = eZINI::instance('dappsocial.ini');

            // Set correct site timezone
            $length = $ini->variable("RequestSettings", "ElaborationMaxLength");
            $elaboration = (strlen($elaboration) < $length) ? $elaboration : mb_substr($elaboration, 0, $length);
            UserRelationshipElaborationsObject::create(array('rid' => $newObj->attribute('rid'), 'elaboration' => $elaboration))->store();

            $tpl = eZTemplate::factory();
            $ini = eZINI::instance('site.ini');
            
            $receiverUser = eZUser::fetch($userID);
            $senderUser = eZUser::fetch($requesteeID);
            
            $tpl->setVariable('receiver', $receiverUser);
            $tpl->setVariable('sender', $senderUser);
            $tpl->setVariable('user_message', $elaboration);
            $result = $tpl->fetch("design:dappsocial/friendshipmail.tpl");


            $mail = new eZMail();
            $mail->setReceiver($receiverUser->attribute('email'));
            $mail->setSender($ini->variable("MailSettings", "AdminEmail"));
            $mail->setSubject($tpl->variable('subject'));
            $mail->setBody($tpl->variable('message'));
            $mail->setContentType('text/html');

            $mailResult = eZMailTransport::send($mail);
        }

        return true;
    }

    static function delete($contentObjectAttribute, $rid, $reason = null) {

        $userID = $contentObjectAttribute->attribute("contentobject_id");

        $cond = array('requester_id' => $userID, 'requestee_id' => $rid);
        $checkObj = eZPersistentObject::fetchObject(UserRelationshipsObject::definition(), null, $cond);
        if ($checkObj instanceof UserRelationshipsObject) {
            if ($checkObj->attribute('approved') == 1)
                RelationshipType::setCounter($userID, -1);
            RelationshipType::removeElaboration($checkObj->attribute('rid'));
            $checkObj->remove();
        }

        $cond = array('requester_id' => $rid, 'requestee_id' => $userID);
        $checkObj = eZPersistentObject::fetchObject(UserRelationshipsObject::definition(), null, $cond);
        if ($checkObj instanceof UserRelationshipsObject) {
            if ($checkObj->attribute('approved') == 1)
                RelationshipType::setCounter($rid, -1);
            RelationshipType::removeElaboration($checkObj->attribute('rid'));
            $checkObj->remove();
        }

        return true;
    }

    static function removeElaboration($rid) {

        $elab = eZPersistentObject::fetchObject(UserRelationshipElaborationsObject::definition(), null, array('rid' => $rid));

        if ($elab instanceof UserRelationshipElaborationsObject)
            $elab->remove();
    }

    static function setCounter($rid, $value) {

        $contentObject = eZContentObject::fetch($rid);

        $attribs = $contentObject->contentObjectAttributes();
        $loopLength = count($attribs);

        for ($i = 0; $i < $loopLength; $i++) {


            switch ($attribs[$i]->attribute("data_type_string")) {

                case 'relationship':

                    $attribs[$i]->setAttribute("data_int", $value + $attribs[$i]->attribute("data_int"));
                    $attribs[$i]->store();

                    break;
            }
        }
    }

    static function getRelationshipAttribute($rid) {

        $contentObject = eZContentObject::fetch($rid);

        $attribs = $contentObject->contentObjectAttributes();
        $loopLength = count($attribs);

        for ($i = 0; $i < $loopLength; $i++) {


            switch ($attribs[$i]->attribute("data_type_string")) {

                case 'relationship':

                    return $attribs[$i];


                    break;
            }
        }
    }

}

eZDataType::register(RelationshipType::DATA_TYPE_STRING, "RelationshipType");
?>
