<?php


class UserExpDataObject extends eZPersistentObject
{

     /**
     * Construct, use {@link UserExpDataObject::create()} to create new objects.
     * 
     * @param array $row
     */
    protected function __construct( $row )
    {
        $this->eZPersistentObject( $row );
    }

    static function definition()
    {
        static $def = array( 'fields' => array(
                    'id' => array(
                      'name' => 'id',
                      'datatype' => 'integer',
                      'default' => 0,
                      'required' => true ),
					'contentobject_id' => array(
                      'name' => 'contentobject_id',
                      'datatype' => 'integer',
                      'default' => 0,
                      'required' => true ),
					'user_id' => array(
                      'name' => 'user_id',
                      'datatype' => 'integer',
                      'default' => 0,
                      'required' => true ),
					'created_at' => array(
                      'name' => 'created_at',
                      'datatype' => 'integer',
                      'default' => 0,
                      'required' => true ),
					'experience' => array(
                      'name' => 'experience',
                      'datatype' => 'integer',
                      'default' => 0,
                      'required' => true ),
                  ),
                  'keys' => array( 'id' ),
                  'increment_key' => 'id',
                  'class_name' => 'UserExpDataObject',
                  'name' => 'userexp_data' );
        return $def;
    }

    /**
     * Remove rating data by content object id and optionally attribute id.
     * 
     * @param int $contentobjectID
     * @param int $contentobjectAttributeId
     */
    static function removeByObjectId( $contentobjectID, $userId = null )
    {
        $cond = array( 'contentobject_id' => $contentobjectID );
        if ( $userId !== null )
        {
            $cond['user_id'] = $userId;
        }
        eZPersistentObject::removeObject( self::definition(), $cond );
    }

    /**
     * Fetch rating by rating id!
     * 
     * @param int $id
     * @return null|UserExpDataObject
     */
    static function fetch( $id )
    {
        $cond = array( 'id' => $id );
        $return = eZPersistentObject::fetchObject( self::definition(), null, $cond );
        return $return;
    }

    /**
     * Fetch rating data by content object id and optionally attribute id!
     * 
     * @param int $contentobjectID
     * @param int $userId
     * @return null|UserExpDataObject
     */
    static function fetchByObjectId( $contentobjectID, $userId = null )
    {
        $cond = array( 'contentobject_id' => $contentobjectID );
        if ( $userId !== null )
        {
            $cond['user_id'] = $userId;
        }
        $return = eZPersistentObject::fetchObjectList( self::definition(), null, $cond );
        return $return;
    }


    static function create( $row = array() )
    {

        if ( !isset( $row['user_id'] ) )
        {
            $row['user_id'] = eZUser::currentUserID();
        }
    
        if ( !isset( $row['created_at'] ) )
        {
            $row['created_at'] = time();
        }

        if ( !isset( $row['contentobject_id'] ) )
        {
            eZDebug::writeError( 'Missing \'contentobject_id\' parameter!', __METHOD__ );
        }
	
        $object = new self( $row );
        return $object;
    }
}

?>