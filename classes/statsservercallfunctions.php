<?php

class StatsServerCallFunctions extends ezjscServerFunctions {

    /**
     * Submit score
     * usage: ezjscore/call/stats::submit::gameid::category::value::type::created::hash
     * @param array $args ( 0 => gameid,  1 => category, 2 => value, 3=>type,4=>time, 5=>hash )
     * @return array
     * 
     */
    public static function submit($args) {

        $db = eZDB::instance();

        $gameid = intval($args[0]);

        $category = $db->escapeString($args[1]);
        $score = intval($args[2]);
        $type = $args[3]; // max,min,add,replace
        $created = intval($args[4]); //unix timestamp
        $hash = $args[5];

        $user = eZUser::currentUser();

        $userid = $user->attribute('contentobject_id');

        $contentObject = eZContentObject::fetch($gameid);

        $attributes = $contentObject->dataMap();

        $secretKey = $attributes['secret']->attribute('content');

        $real_hash = md5($gameid . $userid . $category . $score . $type . $created . $secretKey);
        echo($real_hash);


        if ($real_hash == $hash) {


            $rows = $db->arrayQuery("SELECT score, created FROM gamestats WHERE " .
                            "gameid={$gameid} AND userid={$userid} AND " .
                            "category='{$category}'");

            echo count($rows);
            if (count($rows) > 0 and $rows[0]['score'] == $score)
                return 'error:duplicated';
            else if (count($rows) > 0) {
                switch ($type) {
                    case 'max':
                        if ($score > $rows[0]['score']) {
                            StatsServerCallFunctions::insert($gameid, $userid, $category, $score, $created);
                        } else
                            return 'error:smaller value';
                        break;
                    case 'min':
                        if ($score < $rows[0]['score']) {
                            StatsServerCallFunctions::insert($gameid, $userid, $category, $score, $created);
                        } else
                            return 'error:greater value';
                        break;
                    case 'add':
                        if ($created != $rows[0]['created'])
                            StatsServerCallFunctions::update($gameid, $userid, $category, ($score + $rows[0]['score']), $created);
                        else
                            return 'error:duplicated add';
                        break;
                    case 'replace':

                        StatsServerCallFunctions::update($gameid, $userid, $category, $score, $created);

                        break;
                    default:
                        return 'error:invalid operation';
                }
            } else {

                StatsServerCallFunctions::insert($gameid, $userid, $category, $score, $created);
            }
            return 'success';
        } else {
            return 'error:hash';
        }
    }

    public static function insert($gameid, $userid, $category, $score, $created) {
        $db = eZDB::instance();
        $sql = "INSERT INTO gamestats (gameid, userid, category, score, created ) VALUES " .
                "('{$gameid}','{$userid}','{$category}','{$score}','{$created}')";

        $db->query($sql);
    }

    public static function update($gameid, $userid, $category, $score, $created) {
        $db = eZDB::instance();
        $sql = "UPDATE gamestats SET score='{$score}', created='{$created}' WHERE " .
                "gameid='{$gameid}' AND userid='{$userid}' AND category='{$category}'";

        $db->query($sql);
    }

    /**
     * Retrieve score
     * usage: ezjscore/call/stats::retrieve::gameid::category::type
     * @param array $args ( 0 => gameid,  1 => category, 2=>order, 3=>minimum created, 4=userid )
     * @return array
     * 
     */
    public static function retrieve($args) {
        $db = eZDB::instance();
        $gameid = intval($args[0]);
        $category = $db->escapeString($args[1]);
        $order = isset($args[2]) ? strtoupper($db->escapeString($args[2])) : 'DESC'; // asc,desc
        $created = isset($args[3]) ? intval($args[3]) : 0; //minimum created time
        $userid = isset($args[4]) ? intval($args[4]) : 0; // if we will get only the stats of a user, 0 for all

        $sql = "SELECT gs.score, obj.name FROM gamestats gs JOIN ezcontentobject_name obj ON obj.contentobject_id=gs.userid WHERE gameid='{$gameid}' AND created>='{$created}' " .
                "AND category='{$category}' ORDER by score {$order} LIMIT 10";

        if ($userid != 0)
            $sql = "SELECT gs.score, obj.name FROM gamestats gs JOIN ezcontentobject_name obj ON obj.contentobject_id=gs.userid WHERE gameid='{$gameid}' AND created>='{$created}' " .
                "AND category='{$category}' and userid='{$userid}' ORDER by score {$order} LIMIT 10";


        $rows = $db->arrayQuery($sql);

        $result = '';
        for ($i = 0; $i < count($rows); $i++) {
            $result.=str_replace(array("|", ";"), "", $rows[$i]['name']) . "|" . str_replace(array("|", ";"), "", $rows[$i]['score']) . ";";
        }
        return $result;
    }

}

?>