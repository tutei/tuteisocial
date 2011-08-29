<?php

class UserExpType extends eZDataType {
    const DATA_TYPE_STRING = "userexp";

    /* !
      Construtor
     */

    function UserExpType() {
        $this->eZDataType(self::DATA_TYPE_STRING, ezpI18n::tr('extension/userexp/datatype', 'User Exp', 'Datatype name'), array('serialize_supported' => true));
    }

    function initializeObjectAttribute($contentObjectAttribute, $currentVersion, $originalContentObjectAttribute) {


        if ($originalContentObjectAttribute->attribute("data_int") != NULL) {
            $dataInt = $originalContentObjectAttribute->attribute("data_int");
            $contentObjectAttribute->setAttribute("data_int", $dataInt);
        } else {

            $contentObjectAttribute->setAttribute("data_int", 0);
        }

        if ($originalContentObjectAttribute->attribute("data_float") != NULL) {
            $dataInt = $originalContentObjectAttribute->attribute("data_float");
            $contentObjectAttribute->setAttribute("data_float", $dataInt);
        } else {
            $contentObjectAttribute->setAttribute("data_float", 1);
        }
    }

    /* !
      Validates input on content object level
      \return eZInputValidator::STATE_ACCEPTED or eZInputValidator::STATE_INVALID if
      the values are accepted or not
     */

    function validateObjectAttributeHTTPInput($http, $base, $contentObjectAttribute) {
        return eZInputValidator::STATE_ACCEPTED;
    }

    /* !
      Fetches all variables from the object
      \return true if fetching of class attributes are successfull, false if not
     */

    function fetchObjectAttributeHTTPInput($http, $base, $contentObjectAttribute) {
        return true;
    }

    /* !
      Returns the content.
     */

    function objectAttributeContent($contentObjectAttribute) {
        $experience = $contentObjectAttribute->attribute("data_int");
        $level = $contentObjectAttribute->attribute("data_float");

        $prevLevel = ($level!=1)?($level - 2) * 20 + (($level - 1) * 7) + ($level - 1) * ($level - 1) * ($level - 1) - ($level - 1) * ($level - 1):0;
        $nextLevel = ($level - 1) * 20 + ($level * 7) + $level * $level * $level - $level * $level;

        $completed = ($experience - $prevLevel) / ($nextLevel - $prevLevel);


        return array('exp' => $experience, 'level' => $level, 'next' => $nextLevel, 'prev' => $prevLevel, 'completed' => $completed);
    }

    /* !
      Returns the meta data used for storing search indeces.
     */

    function metaData($contentObjectAttribute) {
        return $contentObjectAttribute->attribute("data_int");
    }

    function toString($contentObjectAttribute) {
        return "<p>Level: " . $contentObjectAttribute->attribute('data_float') . ". Exp: " . $contentObjectAttribute->attribute('data_int') . "</p>";
    }

    function fromString($contentObjectAttribute, $string) {
        /* string est� no formato exp|objectID */
        $expData = explode("|", $string);

        $exp = $expData[0];
        $objectID = $expData[1];
        
        

        
        $userID = $contentObjectAttribute->attribute("contentobject_id");

        /* incrementa o level na categoria e depois o level global */

        /* busca se existe dados sobre a categoria, se n�o existe, cria */
        $userExpObj = UserExpObject::fetchByObjectId($objectID, $userID);
        
        if ($userExpObj == null)
            $userExpObj = UserExpObject::create(array('user_id' => $userID,
                        'contentobject_id' => $objectID));
        /* treina na categoria */
        if ((int) $exp >= 0)
            UserExpObject::train($userExpObj, $exp);
        else
            UserExpObject::untrain($userExpObj, $exp);

        /* treina global */
        if ((int) $exp >= 0)
            UserExpType::train($contentObjectAttribute, $exp);
        else
            UserExpType::untrain($contentObjectAttribute, $exp);

        /* armazena o log da exp */
        $userExpDataObj = UserExpDataObject::create(array('user_id' => $userID,
                    'contentobject_id' => $objectID,
                    'experience' => $exp));
        /* armazena tudo */
        $userExpDataObj->store();
        $userExpObj->store();
        $contentObjectAttribute->store();

        /* retorna o total de exp */
        return $contentObjectAttribute->attribute('data_int');
    }

    /* !
      Returns the value as it will be shown if this attribute is used in the object name pattern.
     */

    function title($contentObjectAttribute, $name = null) {
        return $contentObjectAttribute->attribute("data_int");
    }

    function sortKey($contentObjectAttribute) {
        return $contentObjectAttribute->attribute('data_int');
    }

    function sortKeyType() {
        return 'int';
    }

    function hasObjectAttributeContent($contentObjectAttribute) {
        return $contentObjectAttribute->attribute('data_int') !== null;
    }

    /* !
      \return true if the datatype can be indexed
     */

    function isIndexable() {
        return true;
    }

    static function train($contentObjectAttribute, $exp) {

        $experience = $contentObjectAttribute->attribute("data_int");
        $level = $contentObjectAttribute->attribute("data_float");

        //$contentObjectAttribute->setAttribute( "data_int", 0 );



        $experience+=$exp;
        $contentObjectAttribute->setAttribute("data_int", $experience);


        $nextLevel = ($level - 1) * 20 + ($level * 7) + $level * $level * $level - $level * $level;

        if ($experience >= $nextLevel) {
            // Increase level
            $level++;
            $contentObjectAttribute->setAttribute("data_float", $level);
            // Do something]
            //Calls recursive and train again if has more levels to up
            UserExpType::train($contentObjectAttribute, 0);
        }
    }

    static function untrain($contentObjectAttribute, $exp) {

        $experience = $contentObjectAttribute->attribute("data_int");
        $level = $contentObjectAttribute->attribute("data_float");

        //$contentObjectAttribute->setAttribute( "data_int", 0 );



        $experience+=$exp;
        if ($experience < 0)
            $experience = 0;
        $contentObjectAttribute->setAttribute("data_int", $experience);

        $prevLevel = ($level - 2) * 20 + (($level - 1) * 7) + ($level - 1) * ($level - 1) * ($level - 1) - ($level - 1) * ($level - 1);

        if ($experience < $prevLevel and $experience >= 0) {
            $level--;
            $contentObjectAttribute->setAttribute("data_float", $level);
            //attack+=4;
            //agility+=2;
            //maxMP++;
            //maxHP+=4;
            //hp=maxHP;
            UserExpType::untrain($contentObjectAttribute, 0);
        }
    }

}

eZDataType::register(UserExpType::DATA_TYPE_STRING, "UserExpType");
?>
