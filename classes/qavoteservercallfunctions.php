<?php

/*
 * ezjscServerFunctions for qavote (rating related)
 */

class qaVoteServerCallFunctions extends ezjscServerFunctions {

    /**
     * Rate content object attribute id
     *
     * @param array $args ( 0 => contentobjectattribute_id,  1 => contentobject_version, 2 => rating )
     * @return array
     */
    public static function rate($args) {


        $ret = array('id' => 0, 'rated' => false, 'already_rated' => false, 'stats' => false);
        if (isset($args[0]))
            $ret['id'] = $args[0];

        if (!isset($args[2]) || !is_numeric($args[0]) || !is_numeric($args[1]) || !is_numeric($args[2]) || ($args[2] != 1 && $args[2] != -1))
            return $ret;


        // Provide extra session protection on 4.1 (not possible on 4.0) by expecting user
        // to have an existing session (new session = mostlikely a spammer / hacker trying to manipulate rating)
        if (class_exists('eZSession') && eZSession::userHasSessionCookie() !== true)
            return $ret;



        // Return if parameters are not valid attribute id + version numbers
        $contentobjectAttribute = eZContentObjectAttribute::fetch($ret['id'], $args[1]);



        if (!$contentobjectAttribute instanceof eZContentObjectAttribute)
            return $ret;

        // Return if attribute is not a rating attribute
        if ($contentobjectAttribute->attribute('data_type_string') !== qaVoteType::DATA_TYPE_STRING)
            return $ret;





        // Return if rating has been disabled on current attribute
        if ($contentobjectAttribute->attribute('data_int'))
            return $ret;






        // Return if user does not have access to object
        $contentobject = $contentobjectAttribute->attribute('object');
        if (!$contentobject instanceof eZContentObject || !$contentobject->attribute('can_read'))
            return $ret;


        // TODO
        $rateDataObj = qaVoteDataObject::create(array('contentobject_id' => $contentobjectAttribute->attribute('contentobject_id'),
                    'contentobject_attribute_id' => $ret['id'],
                    'vote_value' => $args[2]
                ));







        $proiorRating = $rateDataObj->userHasRated(true);

        if ($proiorRating === true) {
            $ret['already_rated'] = true;
        }

        if (!$proiorRating) {

            $object = eZContentObject::fetch($rateDataObj->attribute('contentobject_id'));
            $owner = eZContentObject::fetch($object->attribute('owner_id'));


            $attribs = $owner->contentObjectAttributes();
            $loopLength = count($attribs);
            $node = $object->mainNode();


            $mainObject = $contentobjectAttribute->object();
            $ini = eZINI::instance('dappsocial.ini');
            // Set correct site timezone
            $length = $ini->variable("VoteSettings", $mainObject->attribute('class_identifier'));


            $nextID = $mainObject->mainNodeID();
            if ($length < 0) {
                $length*=-1;
                $nextNode = $mainObject->mainNode();
                for ($x = 0; $x < $length; $x++) {
                    $nextNode = $nextNode->fetchParent();
                    $nextID = $nextNode->attribute('node_id');
                }

                for ($i = 0; $i < $loopLength; $i++) {

                    switch ($attribs[$i]->attribute("data_type_string")) {
                        case 'userexp':
                            $attribs[$i]->fromString("$args[2]|$nextID");
                            break;
                    }
                }
            } else if($length >0)$nextID=$length;
            
            $rateDataObj->store();
            $voteObj = $rateDataObj->getVoteObject();
            $voteObj->updateFromRatingData($args[2]);
            $voteObj->store();

            eZContentCacheManager::clearContentCacheIfNeeded($rateDataObj->attribute('contentobject_id'));
            $ret['rated'] = true;
            $ret['stats'] = array(
                'vote_count' => $voteObj->attribute('vote_count'),
                'vote_up' => $voteObj->attribute('vote_up'),
                'vote_down' => $voteObj->attribute('vote_down')
//                'rating_average' => $avgRateObj->attribute('rating_average'),
//                'rounded_average' => $avgRateObj->attribute('rounded_average'),
            );
        }
        return $ret;
    }

    /**
     * Check if user has rated.
     *
     * @param array $args ( 0 => contentobject_id,  1 => contentobjectattribute_id )
     * @return bool|null (null if params are wrong)
     */
    public static function user_has_rated($args) {
        if (!isset($args[1]) || !is_numeric($args[0]) || !is_numeric($args[1]))
            return null;

        $rateDataObj = qaVoteDataObject::create(array('contentobject_id' => $args[0],
                    'contentobject_attribute_id' => $args[1]
                ));

        return $rateDataObj->userHasRated();
    }

    /**
     * Reimp
     */
    public static function getCacheTime($functionName) {
        return time();
    }

}

?>