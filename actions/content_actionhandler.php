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

function dappsocial_ContentActionHandler(&$module, &$http, &$objectID) {
    
    $redirectURI = $http->hasPostVariable( 'redirectAfterSelectionURI' ) ?
                            $http->postVariable( 'redirectAfterSelectionURI' ) :
                            $http->sessionVariable( 'LastAccessesURI' );

    if ($http->hasPostVariable("SocialRequest")) {

        $elaboration=$http->hasPostVariable("Elaboration")?$http->postVariable("Elaboration"):'';

        if ($http->postVariable("ContentObjectID") == eZINI::instance()->variable('UserSettings', 'AnonymousUserID'))
            $module->redirectTo('/social/request/(error)/anonymous');

        else if (RelationshipType::request(RelationshipType::getRelationshipAttribute(eZUser::currentUser()->attribute('contentobject_id')),$http->postVariable("ContentObjectID"),$elaboration))
            $module->redirectTo($redirectURI);
        else
            $module->redirectTo('/social/request/(error)/other');
    } else if ($http->hasPostVariable("SocialApprove")) {

        if (RelationshipType::approve(RelationshipType::getRelationshipAttribute(eZUser::currentUser()->attribute('contentobject_id')), $http->postVariable("ContentObjectID")))
            $module->redirectTo($redirectURI);
        else
            $module->redirectTo('/social/approve/(error)/other');
    } else if ($http->hasPostVariable("SocialDelete")) {
        if (RelationshipType::delete(RelationshipType::getRelationshipAttribute(eZUser::currentUser()->attribute('contentobject_id')), $http->postVariable("ContentObjectID")))
            $module->redirectTo($redirectURI);
        else
            $module->redirectTo('/social/delete/(error)/other');
    }


    return true;
}

?>
