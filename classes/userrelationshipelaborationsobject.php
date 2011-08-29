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

class UserRelationshipElaborationsObject extends eZPersistentObject {

    /**
     * Construct, use {@link UserRelationshipElaborationsObject::create()} to create new objects.
     * 
     * @param array $row
     */
    protected function __construct($row) {
        parent::eZPersistentObject($row);
    }

    public static function definition() {
        static $def = array('fields' => array(
        'rid' => array(
            'name' => 'rid',
            'datatype' => 'integer',
            'default' => 0,
            'required' => true),
        'elaboration' => array(
            'name' => 'elaboration',
            'datatype' => 'text',
            'default' => '',
            'required' => false)
    ),
    'keys' => array('rid'),
    'class_name' => 'UserRelationshipElaborationsObject',
    // "sort" => array( "rid" => "asc" ),
    'name' => 'user_relationship_elaborations');
        return $def;
    }

    public static function create(array $row = array()) {
        $object = new self($row);
        return $object;
    }

}

?>