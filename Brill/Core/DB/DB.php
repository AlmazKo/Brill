<?php
#include 'MysqliExt.php';

require_once 'LogMysql.php';

Class DB {
    protected static $lnk = null;

    /**
     *
     * @param array $config
     */
    public function  __construct() {

    }

    final private static function connector($config){
        $lnk = null;
        $lnk = @new mysqli($config[0], $config[1], $config[2], $config[3]);
        if ($lnk->connect_errno) {
            LogMysql::errorQuery("Подключение к серверу MySQL невозможно / ".$lnk->connect_error);
            $lnk = null;
            die();
        }
        return $lnk;
    }

    public static function connect($nameConfig = null) {
        if (!$nameConfig) {
            if (self::$lnk == null) {
                self::$lnk = self::connector(RegistryDb::instance()->get('default'));
                RunTimer::addTimer('Mysql');
            }
            return self::$lnk;
        } else {
            return self::connector(RegistryDb::instance()->get($nameConfig));
        }

    }


    function __destruct() {
        //echo Log::viewLog();
        if (self::$lnk) {
            self::$lnk->close();
        }
    }

    /**
     * Переопределенная mysqli_query
     * @param string $sql текст запроса
     * @return Resource Результат выполения запрса
     */
    static function query($sql, $lnk = null) {
        $result = false;
        $lnk = $lnk ? $lnk : self::$lnk;
        // тут можно какой нить prepare sql делать //$sql = mysql_real_escape_string($sql);
        RunTimer::addPoint('Mysql');
        $result = $lnk->query($sql);
        if($result){
            LogMysql::query($sql,  RunTimer::endPoint('Mysql'));
        }else{
            LogMysql::errorQuery($sql . ' / ' .$lnk->error);
            die();
        }
        return $result;
    }


     /**
     * Обвертка self::query для получения только одного поля из результа запроса
     * @param string $query SQL-запрос
     * @param string $field Значение какого поля надо вернуть, если не указано - возвращается первое
     * @return string в случае неудачи возвратит NULL
     */
    public function queryField ($query, $field = null) {
        $result = self::query($query);
        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            if($field){
                $row = $result->fetch_assoc();
                return $row[$field];
            }else{
                $result = $result->fetch_row();
                return $result[0];
            }
        }else{
            return null;
        }
    }
}