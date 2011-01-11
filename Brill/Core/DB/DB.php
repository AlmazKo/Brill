<?php
#include 'MysqliExt.php';
#require_once 'mysqliHelper.php';

class DB {
    const DEFAULT_LNK = 'default';
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
        if (mysqli_connect_errno()) {
            LogMysql::errorQuery("Подключение к серверу MySQL невозможно / " . mysqli_connect_error());
            $lnk = null;
            die();
        }
        if (!$lnk->set_charset('utf8')) {
            Log::warning("Error loading character set utf8: %s", mysqli_connect_error());
        }
        return $lnk;
    }

    public static function connect($nameConfig = null) {
        if (!$nameConfig) {
            if (self::$lnk == null) {
                self::$lnk = self::connector(RegistryDb::instance()->get(self::DEFAULT_LNK));
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
     *
     * @param <type> $prepare_stmt
     * @param <type> $params
     * @param <type> $lnk
     * @return <type>
     */
    static function execute($prepare_stmt, $params, $returnFields = null, $lnk = null) {
//Log::dump(func_get_args());
//        $result = false;
//        $lnk = $lnk ? $lnk : self::$lnk;
//        $stmt = $lnk->prepare($prepare_stmt);
//
//
//
//
//        $fieldsInfo = mysqli_fetch_fields($stmt->result_metadata());
//        //$typesR = mysqliHelper::getSpecification($fieldsInfo);
//        $types = mysqliHelper::getSpecification($fieldsInfo, array_keys($params));
//        Log::dump($types);
//
//        mysqliHelper::bindParam(&$stmt, $types, $params);
//        $params[0] = 0;
//        Log::dump($stmt);



        $stmt = self::$lnk->prepare("SELECT id,name,content,date FROM `Pages` WHERE id=?");
        $stmt->bind_param('i', $d);
        $d = 0 ;
        $stmt->execute();
        $stmt->store_result();
        var_dump(self::$lnk->get_result());
//
//        foreach($column as $col_name)
//        {
//          // Assign the fetched value to the variable '$data[$name]'
//          $params[] =& $data[$col_name] ;
//        }
//        $res = call_user_func_array(array($stmt, "bind_result"), $params) ;
//
//
//
//        //        Log::dump($stmt->param_count());
//    $stmt->bind_result($col1);
//
//    /* fetch values */
//    while ($stmt->fetch()) {
//        var_dump($col1);
//    }

                    /* fetch values */
//    while ($row = $stmt->fetch()) {
//       Log::dump($row);
//    }


        while(mysqli_more_results(self::$lnk)) {
    mysqli_next_result(self::$lnk);
}
   echo '--';
    printf("Number of rows: %d.\n", $stmt->num_rows);
    $stmt->close();
        die();
//        // тут можно какой нить prepare sql делать //$sql = mysql_real_escape_string($sql);
//        RunTimer::addPoint('Mysql');
//        $result = $lnk->query($sql);
//        if($result){
//            LogMysql::query($sql,  RunTimer::endPoint('Mysql'));
//        }else{
//            LogMysql::errorQuery($sql . ' / ' .$lnk->error);
//            die();
//        }
//        return $result;
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

    //static function



}
