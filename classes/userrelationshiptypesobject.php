<?php

//
// ## BEGIN COPYRIGHT, LICENSE AND WARRANTY NOTICE ##
// SOFTWARE NAME: Tutei Social
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

class UserRelationshipTypesObject extends eZPersistentObject {

    /**
     * Construct, use {@link UserRelationshipTypesObject::create()} to create new objects.
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
        'name' => array(
            'name' => 'name',
            'datatype' => 'string',
            'default' => null,
            'required' => true),
        'plural_name' => array(
            'name' => 'plural_name',
            'datatype' => 'string',
            'default' => null,
            'required' => true),
        'is_oneway' => array(
            'name' => 'is_oneway',
            'datatype' => 'integer',
            'default' => 0,
            'required' => true),
        'is_reciprocal' => array(
            'name' => 'is_reciprocal',
            'datatype' => 'integer',
            'default' => 0,
            'required' => true),
        'requires_approval' => array(
            'name' => 'requires_approval',
            'datatype' => 'integer',
            'default' => 0,
            'required' => true),
        'expires_val' => array(
            'name' => 'expires_val',
            'datatype' => 'integer',
            'default' => 0,
            'required' => true)
    ),
    'keys' => array('rid'),
    "increment_key" => "rid",
    'class_name' => 'UserRelationshipTypesObject',
    // "sort" => array( "rid" => "asc" ),
    'name' => 'user_relationship_types');
        return $def;
    }

    public static function create(array $row = array()) {
        $object = new self($row);
        return $object;
    }

}

?>