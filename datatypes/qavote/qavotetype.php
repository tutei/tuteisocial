<?php

class qaVoteType extends eZDataType
{
	const DATA_TYPE_STRING = 'qavote';

    /*!
     Construction of the class, note that the second parameter in eZDataType 
     is the actual name showed in the datatype dropdown list.
    */
    function __construct()
    {
        parent::__construct( self::DATA_TYPE_STRING, ezpI18n::tr( 'extension/qavote/datatype', 'Q&A Vote', 'Datatype name' ), array( 'serialize_supported' => true ) );
    }

    /*!
      Validates the input and returns true if the input was
      valid for this datatype.
    */
    function validateObjectAttributeHTTPInput( $http, $base, $contentObjectAttribute )
    {
        return eZInputValidator::STATE_ACCEPTED;
    }

    function deleteStoredObjectAttribute( $contentObjectAttribute, $version = null )
    {
      // Remove all ratings associated with thes objectAttribute;
      if ( $version == null )
      {
          qaVoteObject::removeByObjectId( $contentObjectAttribute->attribute('contentobject_id'), $contentObjectAttribute->attribute('id') );
          qaVoteDataObject::removeByObjectId( $contentObjectAttribute->attribute('contentobject_id'), $contentObjectAttribute->attribute('id') );
      }
    }

    /*!
    */
    function fetchObjectAttributeHTTPInput( $http, $base, $contentObjectAttribute )
    {
        // Use data_int for storing 'disabled' flag
        $contentObjectAttribute->setAttribute( 'data_int', $http->hasPostVariable( $base . '_data_qavote_disabled_' . $contentObjectAttribute->attribute( 'id' ) ) );
        return true;
    }

    /*!
     Store the content. Since the content has been stored in function 
     fetchObjectAttributeHTTPInput(), this function is with empty code.
    */
    function storeObjectAttribute( $contentObjectAttribute )
    {
    }

    /*!
     Returns the meta data used for storing search indices.
    */
    function metaData( $contentObjectAttribute )
    {
        $qaVoteObj = $contentObjectAttribute->attribute( 'content' );
        return ( $qaVoteObj instanceof qaVoteObject ) ? $qaVoteObj->attribute('vote_up') - $qaVoteObj->attribute('vote_down') : '';
    }

    /*!
     Returns the text.
    */
    function title( $contentObjectAttribute, $name = null)
    {
        return $this->metaData( $contentObjectAttribute );
    }

    function isIndexable()
    {
        return true;
    }

    function sortKey( $contentObjectAttribute )
    {
        return $this->metaData( $contentObjectAttribute );
    }
  
    function sortKeyType()
    {
        return 'integer';
    }

    function hasObjectAttributeContent( $contentObjectAttribute )
    {
        $qaVoteObj = $contentObjectAttribute->attribute( 'content' );
        return $qaVoteObj instanceof qaVoteObject ? $qaVoteObj->attribute('vote_count') > 0 : false;
    }

    /*!
     Returns the content.
    */
    function objectAttributeContent( $contentObjectAttribute )
    {
        $objectId = $contentObjectAttribute->attribute('contentobject_id');
        $attributeId = $contentObjectAttribute->attribute('id');
        $qaVoteObj = null;
        if ( $objectId && $attributeId )
        {
            $qaVoteObj = qaVoteObject::fetchByObjectId( $objectId, $attributeId );
    
            // Create empty object if none could be fetched
            if (  !$qaVoteObj instanceof qaVoteObject )
            {
                $qaVoteObj = qaVoteObject::create( array('contentobject_id' => $objectId,
                                                             'contentobject_attribute_id' => $attributeId ) );
            }
        }
        return $qaVoteObj;
    }
}

eZDataType::register( qaVoteType::DATA_TYPE_STRING, 'qaVoteType' );