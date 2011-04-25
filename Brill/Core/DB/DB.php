<?php
#include 'MysqliExt.php';
#require_once 'mysqliHelper.php';

class DB {
    const DEFAULT_LNK = 'default';
    /**
     *
     * @var PDO 
     */
    protected static $lnk = null;

    /**
     *
     * @param array $config
     */
    public function  __construct() {

    }

    final private static function connector($config){
        try {
            $dsn='mysql:dbname=' . $config[3] . ';host=' . $config[0];
            $lnk = new PDO($dsn , $config[1], $config[2], array(PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES \'UTF8\''));
        } catch (PDOException $e) {
            throw new DBException($e->getMessage(), $e->getCode());
        }
//        try {
//            $lnk = new mysqli($config[0], $config[1], $config[2], $config[3]);
//            if (mysqli_connect_errno()) {
//                LogMysql::errorQuery("Подключение к серверу MySQL невозможно / " . mysqli_connect_error());
//                $lnk = null;
//                die();
//            }
//            if (!$lnk->set_charset('utf8')) {
//                Log::warning("Error loading character set utf8: %s", mysqli_connect_error());
//            }
//        }
//        catch (Exception $e) {
//            var_dump($e->getMessage());
//        }
        
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
        RunTimer::addPoint('Mysql');
        $result = $lnk->query($sql);

        if($result){
            LogMysql::query($sql,  RunTimer::endPoint('Mysql'));
        }else{

            LogMysql::errorQuery($sql . ' / ' .$lnk->errorInfo());
            die();
        }
        return $result;
    }

    /**
     *
     * @param string $prepare_stmt
     * @param array $params
     * @param <type> $lnk
     * @return PDOStatement
     */
    static function execute($prepareStmt, array $params = array(), $returnAllFields = false, $lnk = null) {
        $pdo = $lnk ? $lnk : self::$lnk;
        
        
        
        $sth = $pdo->prepare($prepareStmt);
        foreach ($params as $key => &$value) { 
            $param = self::getPdoType($value);
            if (is_array($value)) {
                $param = $value[1];
                $value = $value[0];
            }
         //   var_dump($key, $value, $param);
            $sth->bindParam($key, $value, $param);
        }
        RunTimer::addPoint('Mysql');
        if($sth->execute()){
            LogMysql::query($sth->queryString,  RunTimer::endPoint('Mysql'));
        }else{
            var_dump($sth->debugDumpParams());
            LogMysql::errorQuery($sth->queryString . "\n\n" . implode('. ' ,$sth->errorInfo()));
            throw new Exception('Error sql');
        }
     //   var_dump($sth->fetchAll());
    //    var_dump($sth->debugDumpParams());
      //  var_dump($params);

        return $sth;




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


/*
        $stmt = self::$lnk->prepare("SELECT id,name,content,date FROM `Pages` WHERE id=?");
        $stmt->bind_param('i', $d);
        $d = 0 ;
        $stmt->execute();
        $stmt->store_result();
        var_dump(self::$lnk->get_result());
 * 
 */
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


   /*     while(mysqli_more_results(self::$lnk)) {
    mysqli_next_result(self::$lnk);
}
   echo '--';
    printf("Number of rows: %d.\n", $stmt->num_rows);
    $stmt->close();
        die();

*/
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


    public static function begin($lnk = null) {
        $pdo = $lnk ? $lnk : self::$lnk;
        return $pdo->beginTransaction();
    }
    
    public static function rollback($lnk = null) {
        $pdo = $lnk ? $lnk : self::$lnk;
        return $pdo->rollBack();
    }
    
    public static function commit($lnk = null) {
        $pdo = $lnk ? $lnk : self::$lnk;
        return $pdo->commit();
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


    protected static function getPdoType($var) {
        if (is_int($var)) {
            return PDO::PARAM_INT;
        } else { 
            return PDO::PARAM_STR;
        }
        
    }
}
