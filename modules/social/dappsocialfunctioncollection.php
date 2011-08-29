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

class DappSocialFunctionCollection {

    public static function fetchList($user_id, $offset, $limit) {

        $filter = array('requestee_id');
        $cond = array('requester_id' => $user_id, 'approved' => 1);
        $limitCond = array('offset' => $offset, 'length' => $limit);
        $data = array();
        $res = eZPersistentObject::fetchObjectList(UserRelationshipsObject::definition(), $filter, $cond, null, $limitCond, false);

        foreach ($res as $r) {
            $data[] = $r['requestee_id'];
        }
        return array('result' => $data);
    }


    public static function fetchRequests($user_id, $offset, $limit) {

        $filter = array('requester_id');

        $cond = array('requestee_id' => $user_id, 'approved' => 0);
        $limitCond = array('offset' => $offset, 'length' => $limit);
        $data = array();
        $res = eZPersistentObject::fetchObjectList(UserRelationshipsObject::definition(), $filter, $cond, null, $limitCond, false);

        foreach ($res as $r) {
            $data[] = $r['requester_id'];
        }
        return array('result' => $data);
    }

}

?>
