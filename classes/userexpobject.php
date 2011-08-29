<?php


class UserExpObject extends eZPersistentObject
{

     /**
     * Construct, use {@link UserExpObject::create()} to create new objects.
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
					'level' => array(
                      'name' => 'level',
                      'datatype' => 'integer',
                      'default' => 1,
                      'required' => true ),
					'experience' => array(
                      'name' => 'experience',
                      'datatype' => 'integer',
                      'default' => 0,
                      'required' => true ),
                  ),
                  'keys' => array( 'contentobject_id', 'user_id' ),
                  'class_name' => 'UserExpObject',
                  'name' => 'userexp' );
        return $def;
    }

    /**
     * Remove rating data by content object id and optionally attribute id.
     * 
     * @param int $contentobjectID
     * @param int $userId
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


    static function fetchByObjectId( $contentobjectID, $userId = null )
    {
        $cond = array( 'contentobject_id' => $contentobjectID );
        if ( $userId !== null )
        {
            $cond['user_id'] = $userId;
        }
        $return = eZPersistentObject::fetchObject( self::definition(), null, $cond );
        return $return;
    }


    static function create( $row = array() )
    {

        if ( !isset( $row['user_id'] ) )
        {
            $row['user_id'] = eZUser::currentUserID();
        }

        if ( !isset( $row['contentobject_id'] ) )
        {
            eZDebug::writeError( 'Missing \'contentobject_id\' parameter!', __METHOD__ );
        }
	
        $object = new self( $row );
        return $object;
    }
	
	
	
	static function train($contentObjectAttribute, $exp){

        $experience=$contentObjectAttribute->attribute( "experience" );
        $level=$contentObjectAttribute->attribute( "level" );
        
        //$contentObjectAttribute->setAttribute( "data_int", 0 );
        
        
        
        $experience+=$exp;
        $contentObjectAttribute->setAttribute( "experience", $experience );
        
        
        $nextLevel = ($level-1)*20 + ($level*7) + $level*$level*$level - $level*$level;

        if( $experience>=$nextLevel ){
            // Increase level
            $level++;
            $contentObjectAttribute->setAttribute( "level", $level );
            // Do something]
            
            
            //Calls recursive and train again if has more levels to up
            UserExpObject::train($contentObjectAttribute,0);
        }


    }
    
    static function untrain($contentObjectAttribute, $exp){
    
        $experience=$contentObjectAttribute->attribute( "experience" );
        $level=$contentObjectAttribute->attribute( "level" );
        
        //$contentObjectAttribute->setAttribute( "data_int", 0 );
        
        
        
        $experience+=$exp;
        if($experience<0)$experience=0;
        $contentObjectAttribute->setAttribute( "experience", $experience );
        
        $prevLevel = ($level-2)*20 + (($level-1)*7) + ($level-1)*($level-1)*($level-1) - ($level-1)*($level-1);

        if( $experience<$prevLevel and $experience >= 0 ){
            $level--;
            $contentObjectAttribute->setAttribute( "level", $level );
            //attack+=4;
            //agility+=2;
            //maxMP++;
            //maxHP+=4;
            //hp=maxHP;
            UserExpObject::untrain($contentObjectAttribute,0);
        }


    }
}

?>