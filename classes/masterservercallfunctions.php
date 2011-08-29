<?php

class MasterServerCallFunctions extends ezjscServerFunctions {

    public static function query($args) {
        $db = eZDB::instance();
        $sql = "DELETE FROM masterserver WHERE (NOW()-updated) > '30';";

        $db->query($sql);
    
        $gametype = $db->escapeString($args[0]);

        $sql = "SELECT externalIp,externalPort,internalIp,internalPort,useNat,guid,gameType,gameName,connectedPlayers,playerLimit,passwordProtected,comment, ( NOW( ) - updated ) AS updated FROM MasterServer WHERE gameType='".$gametype."';";
        // echo ($query);
        $res = $db->arrayQuery($sql);
        if (count($res)==0) {
            return "error:no games";
        }
        $rows = count ($res);
        $cols = count ($res[0]);
        $show = 0;
        $result='';
        for ($i = 0; $i < $rows; $i ++) {
           $row = $res[$i];
           
           if ($show == 1) {
                $result.= ";";
           } else {
                $show = 1;
           }
           
           if ($row['useNat'] == "1" && $row['externalIp'] == $_SERVER['REMOTE_ADDR']) {
                $result.= $row['internalIp'].",".$row['internalPort'].",0";
           } else {
                $result.= $row['externalIp'].",".$row['externalPort'].",".$row['useNat'];
           }

                $result.= ",".$row['guid'].",".$row['gameType'].",".$row['gameName'].",".$row['connectedPlayers'].
                            ",".$row['playerLimit'].",".$row['passwordProtected'].",".$row['comment'].",".$row['updated'];
           
        }
        return $result;
    
    }
    
    public static function register($args) {
        $db = eZDB::instance();
        
        $gametype = $db->escapeString($args[0]);
        $gamename = $db->escapeString($args[1]);
        $usernat = $db->escapeString($args[2]);
        $connectedPlayers = $db->escapeString($args[3]);
        $playerLimit = $db->escapeString($args[4]);
        $internalIp = $db->escapeString($args[5]);
        $internalPort = $db->escapeString($args[6]);
        $externalIp = $db->escapeString($args[7]);
        $externalPort = $db->escapeString($args[8]);
        $guid = $db->escapeString($args[9]);
        $passwordProtected = $db->escapeString($args[10]);
        $comment = $db->escapeString($args[11]);
        
        $sql = "DELETE FROM MasterServer WHERE gameType='".$gametype."' AND gameName='".$gamename."';";
        $db->query($sql);
        
        
        $sql = "INSERT INTO MasterServer ".
                 "(useNat,gameType,gameName,connectedPlayers,".
                 "playerLimit,internalIp,internalPort,".
             "externalIp,externalPort,guid,passwordProtected,comment,updated) VALUES ".
                 "(".$usernat.",'".$gametype.
                 "','".$gamename."',".$connectedPlayers.
                 ",".$playerLimit.",'".$internalIp.
                 "',".$internalPort.",'".$externalIp.
             "',".$externalPort.",'".$guid."',".
             $passwordProtected.",'".$comment."',NOW());";
        $db->query($sql);

        return "succeeded";

        
    }
    
    public static function unregister($args) {
        $db = eZDB::instance();
        
        $gametype = $db->escapeString($args[0]);
        $gamename = $db->escapeString($args[1]);
    
        $sql = "DELETE FROM MasterServer WHERE gameType='".$gametype."' AND gameName='".$gamename."';";
        $db->query($sql);

        return "succeeded";
        
    }
    
    public static function update($args) {
    
        $db = eZDB::instance();
        
        $gametype = $db->escapeString($args[0]);
        $gamename = $db->escapeString($args[1]);
    
        $sql = "UPDATE MasterServer SET updated = NOW() ".
                 "WHERE gameType='".$gametype."' ".
             "AND gameName='".$gamename."';";
        $db->query($sql);
        return "succeeded";    
    
    }
    
    public static function players($args) {
        $db = eZDB::instance();
        
        $gametype = $db->escapeString($args[0]);
        $gamename = $db->escapeString($args[1]);
        $connectedPlayers = $db->escapeString($args[2]);
    
        $sql = "UPDATE MasterServer ".
                 "SET connectedPlayers=".$connectedPlayers." ".
                 "WHERE gameType='".$gametype."' ".
             "AND gameName='".$gamename."';";
        $db->query($sql);

        return "succeeded";    
    
    }

}

?>