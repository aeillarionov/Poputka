<?php
    session_set_save_handler('_open',
                             '_close',
                             '_read',
                             '_write',
                             '_destroy',
                             '_clean');
    function _open() {
        
        global $_sessions_db;
        
        if($_sessions_db = mysql_connect('localhost', 'root', 'root')) {
            return mysql_select_db('game', $_sessions_db);
        }
        echo 'Open Fail';
        return FALSE;
    }
    
    function _close() {
        global $_sessions_db;
        
        return mysql_close($_sessions_db);
    }
    
    function _read($id) {
        global $_sessions_db;
        
        $id = mysql_real_escape_string($id);
        
        $sql = "SELECT `data` FROM `sessions` WHERE `id` = \"$id\"";
        
        if($result = mysql_query($sql, $_sessions_db)){
            if(mysql_num_rows($result)){
                $record = mysql_fetch_assoc($result);
                
                return $record['data'];
            }
        }
       
        return '';
    }
    
    function _write($id, $data) {
        global $_sessions_db;
        
        $access = time();
        
        $id = mysql_real_escape_string($id);
        $access = mysql_real_escape_string($access);
        $data = mysql_real_escape_string($data);
        
        $sql = "REPLACE INTO `sessions` VALUES (\"$id\",\"$access\",\"$data\")";
        
        return mysql_query($sql,$_sessions_db);
    }
    
    function _destroy($id){
        global $_sessions_db;
        
        $id = mysql_real_escape_string($id);
        
        $sql = "DELETE FROM `game`.`sessions` WHERE `sessions`.`id`=\"$id\"";
        
        return mysql_query($sql, $_sessions_db);
    }
    
    function _clean($max){
        global $_sessions_db;
        
        $old = time() - $max - 2592000;
        $old = mysql_real_escape_string($old);
        
        /*$sql = "SELECT `id`,`access`,`data` FROM `sessions` WHERE `access`<\"$old\"";
        
        if($result = mysql_query($sql, $_sessions_db)){
        	if(mysql_num_rows($result)){
                $record = mysql_fetch_assoc($result);
                if(!file_exists("log.txt"))$fd = fopen("log.txt","w") or die("Can't create file");
                else $fd = fopen("log.txt","a");
                $str = $record['id']."| Last access: ".date("r", $record['access'])."; Remove time: ". date("r") ."; Max lifetime: ". $max ."; Expire time: ". date("r", $old) ."; data: ".$record['data']."\n";
                fwrite($fd, $str);
                fclose($fd);
            }
        }*/
        
        $sql = "DELETE FROM `sessions` WHERE `access`<\"$old\"";
        
        return mysql_query($sql,$_sessions_db);
    }
?>