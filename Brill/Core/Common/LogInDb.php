<?php
/**
 * LogInDb - запись логов в базу
 * требует коннекта
 *
 * @author almaz
 */
class LogInDb extends Log{
    private static $_lnk;

    public function setConnection($db) {
        self::$_lnk= $db;
    }

    public static function notice ($descr, $class) {
        if (self::$_lnk) {
            DB::query('insert into Logs ()');
        } else {
            parent::warning('Не был задан connect для класса LogInDb');
        }
    }
   
}
