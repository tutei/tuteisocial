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



class DappSocialTemplateOperators {

    //  Constructor, does nothing by default.

    function DappSocialTemplateOperators() {
        
    }

    // return an array with the template operator name.

    function operatorList() {
        return array('is_friend', 'is_pending', 'get_elaboration');
    }

    // return true to tell the template engine that the parameter list exists per operator type,
    //        this is needed for operator classes that have multiple operators.

    function namedParameterPerOperator() {
        return true;
    }

    // See eZTemplateOperator::namedParameterList

    function namedParameterList() {
        return array('is_friend' => array(
                'user_1' => array('type' => 'integer',
                    'required' => true),
                'user_2' => array('type' => 'integer',
                    'required' => true)),
            'is_pending' => array(
                'user_1' => array('type' => 'integer',
                    'required' => true),
                'user_2' => array('type' => 'integer',
                    'required' => true)),
            'get_elaboration' => array(
                'user_1' => array('type' => 'integer',
                    'required' => true),
                'user_2' => array('type' => 'integer',
                    'required' => true)));
    }

    // Executes the PHP function for the operator cleanup and modifies \a $operatorValue.

    function modify($tpl, $operatorName, $operatorParameters, $rootNamespace, $currentNamespace, &$operatorValue, $namedParameters, $placement) {

        switch ($operatorName) {
            case 'is_friend':

                $id1 = $namedParameters['user_1'];
                $id2 = $namedParameters['user_2'];

                $cond = array('requester_id' => $id1, 'requestee_id' => $id2, 'approved' => 1);
                $checkObj = eZPersistentObject::fetchObject(UserRelationshipsObject::definition(), null, $cond);

                if ($checkObj instanceof UserRelationshipsObject)
                    $operatorValue = true;
                else
                    $operatorValue=false;
                break;
            case 'is_pending':
                $id1 = $namedParameters['user_1'];
                $id2 = $namedParameters['user_2'];

                $cond = array('requester_id' => $id1, 'requestee_id' => $id2, 'approved' => 0);
                $checkObj = eZPersistentObject::fetchObject(UserRelationshipsObject::definition(), null, $cond);

                if ($checkObj instanceof UserRelationshipsObject)
                    $operatorValue = true;
                else
                    $operatorValue=false;
                break;

            case 'get_elaboration':
                
                $id1 = $namedParameters['user_1'];
                $id2 = $namedParameters['user_2'];

                $cond = array('requester_id' => $id1, 'requestee_id' => $id2, 'approved' => 0);
                $checkObj = eZPersistentObject::fetchObject(UserRelationshipsObject::definition(), null, $cond);

                if ($checkObj instanceof UserRelationshipsObject) {
                    
                    $elab = eZPersistentObject::fetchObject(UserRelationshipElaborationsObject::definition(), null, array('rid' => $checkObj->attribute('rid')));
                    
                    if ($elab instanceof UserRelationshipElaborationsObject)
                        $operatorValue = $elab->attribute('elaboration');
                    else
                        $operatorValue='';
                }
                else
                    $operatorValue='';
                break;
        }
    }

}

?>