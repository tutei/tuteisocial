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

class UserRelationshipsObject extends eZPersistentObject {

    /**
     * Construct, use {@link UserRelationshipsObject::create()} to create new objects.
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
        'requester_id' => array(
            'name' => 'requester_id',
            'datatype' => 'integer',
            'default' => 0,
            'required' => true),
        'requestee_id' => array(
            'name' => 'requestee_id',
            'datatype' => 'integer',
            'default' => 0,
            'required' => true),
        'rtid' => array(
            'name' => 'rtid',
            'datatype' => 'integer',
            'default' => 1,
            'required' => true,
            'foreign_class' => 'UserRelationshipTypesObject',
            'foreign_attribute' => 'rid',
            'multiplicity' => '1..*'),
        'approved' => array(
            'name' => 'approved',
            'datatype' => 'integer',
            'default' => 0,
            'required' => true),
        'created_at' => array(
            'name' => 'created_at',
            'datatype' => 'integer',
            'default' => 0,
            'required' => true),
        'updated_at' => array(
            'name' => 'updated_at',
            'datatype' => 'integer',
            'default' => 0,
            'required' => true),
        'flags' => array(
            'name' => 'flags',
            'datatype' => 'integer',
            'default' => 0,
            'required' => true)
    ),
    'keys' => array('requester_id', 'requestee_id', 'rtid'),
    "increment_key" => "rid",
    'class_name' => 'UserRelationshipsObject',
    // "sort" => array( "rid" => "asc" ),
    'name' => 'user_relationships');
        return $def;
    }

    public static function create(array $row = array()) {
        $object = new self($row);
        return $object;
    }

}

?>