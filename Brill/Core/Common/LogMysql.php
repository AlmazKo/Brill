<?php
/**
 * LogMysql - класс логгирования mysql опереаций
 *
 * @author almaz
 */

class LogMysql extends Log{
    public static $log = array();
    public static function query($query, $time) {
        self::$log[] = array($time, $query);
        self::inputLog($time, $query, false, "#82F");
    }

    public static function errorQuery($query) {
       $descr= htmlspecialchars($query) . "\n";
       self::warning($descr, true, 'SQL Error');
       echo self::inputLog('SQL Error', $descr, true, 'Red', 'mysql_errors');

    }
    
    function getLog() {
        return self::$log;
        
    }
}
?>
