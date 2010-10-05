<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of LogMysql
 *
 * @author almaz
 */
require_once 'Log.php';
class LogMysql extends Log{
    public static function query($query, $time) {
        self::inputLog($time, $query, false, "#82F");
    }

    public static function errorQuery($query) {
        self::inputLog('SQL Error', $query, true, "Red", 'mysql_errors');
    }
}
?>
