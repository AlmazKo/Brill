<?php

/**
 * DBExt класс автоматизирующий некоторые действия с базой
 *
 * @author almaz
 */
require_once 'DB.php';
class DBExt extends DB{

    /**
     * Формирует запрос на вставку из массива данных
     * @param string $tblName
     * @param string $values
     * @param string $fields
     * @param string $type  ignored / duplicated false
     * @return $int Количество вставленных(изменных) записей
     */
    static function insertOne($tblName, $values) {

        $aValues = self::parseValues($values);
        $aFields = self::parseFields(array_keys($values));
        $query = "insert IGNORE into `$tblName` (" . $aFields . ") "
                   . "values (" . $aValues . ")";

        parent::query($query);
        return parent::$lnk->insert_id;
    }

   /*
     * Получает значения по полю
     * used for only unique fields
     */
    function getByField($tblName, $field, $value, $returnFields) {
////FIX если одно поле
//        $prepared = "SELECT ".implode(',',$returnFields) ." FROM `$tblName` WHERE $field=?";
//      //   $prepared = "SELECT id FROM `$tblName` WHERE $field=?";
//        parent::execute($prepared, $value, $returnFields);
//
//









        $value = is_string($value) ? "'".addslashes($value)."'" : (string) $value;
        $query = "select * from `$tblName` where $field=$value Limit 1";

        $result = parent::query($query);
        $values = null;
        if ($result->num_rows == 1) {
            $values = $result->fetch_assoc();
        }
        return $values;
    }
    /**
     *Возвращает одну строку.
     * @param string $tblName
     * @param field $pk имя ключа
     * @param string/int $value
     * @return array
     */
    public static function getOneRowPk($tblName, $field, $value) {
        $where = self::simpleWhere($field, $value);
        $query = "select * from `$tblName` $where Limit 1";
        $result = parent::query($query);
        $values = null;
        if ($result->num_rows == 1) {
            $values = $result->fetch_assoc();
        } else {
            Log::warning("Получено больше одной строки");
        }
        return $values;
    }
    
    /**
     *Возвращает одну строку.
     * @param string $tblName
     * @param field $pk имя ключа
     * @param string/int $value
     * @return array
     */
    public static function getOneRow($sql, $lnk = null) {
        $result = parent::query($sql);
        $values = null;
        if ($result->num_rows == 1) {
            $values = $result->fetch_assoc();
        } else if ($result->num_rows > 0) {
            Log::warning("Получено больше одной строки");
        }
        return $values;
    }
    /**
     * Возвращает массив строк
     * @param string $tblName
     * @param string $field
     * @param int/array/string $value
     * @return array
     */
    public static function getRows($tblName, $field = null, $value = null) {
        $where = self::simpleWhere($field, $value);
        $query = "select * from `$tblName`".$where;
        $result = parent::query($query);
        $values = null;
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $values[] = $row;
            }
        }
        return $values;
    }

    /**
     *
     * @param string $tblName
     * @param string $order
     * @param int $limit
     * @param int $offset
     *
     * @return array
     */
    static function getAllRows($tblName, $order = null, $limit = null, $offset = null) {
        $query = "select * from `$tblName`";
        $result = parent::query($query);
        $values = array();
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $values[] = $row;
            }
        }
        return $values;
    }

    /**
     * Получает данные по запросу и возращает даннные для класса oTable
     * @param string $sql Query
     * @return array
     */
    function selectToTable($query) {
        $result = parent::query($query);
        $values = $fields = array();
        while ($finfo = mysqli_fetch_field($result)) {
            $fields [] = $finfo->name;
        }

        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $values[] = $row;
            }
        }

        return array($fields, $values);
    }


/**
     * Получает данные по запросу и возращает даннные для класса oList
     * @param string $sql Query
     * @return array
     */
    function selectToList($query) {
        $result = parent::query($query);
        $values = array();
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_row()) {
                $values[] = $row;
            }
        }
        return $values;
    }

    /**
     * Функция обновляет данные
     * @param string $tblName
     * @param string $values
     * @param string $fields
     * @param string $type  ignored duplicated false
     * @return int количество затронутых строк
     */
    function updateOne($tblName, $values, $field, $val) {

        $where = self::simpleWhere($field, $val);
        $aSets = self::parseValuesWithFields($values);
        $query = "update `$tblName` set " . $aSets . $where . " limit 1";
        parent::query($query);
        return parent::$lnk->affected_rows;
    }



    /**
     * Удаляет одну строку
     * @param string $tblName
     * @param field $pk имя ключа
     * @param string/int $value
     * @return array
     */
    public static function deleteOneRow($tblName, $field, $value) {
        $query = "delete from `$tblName` where $field=$value Limit 1";
        Log::dump($query);
       # $result = parent::query($query);
       # return parent::$lnk->affected_rows;
    }

    /**
     * Добавляет всем полям обратные ковычки и возвращает уже строку
     * @param array $fields
     * @return string
     */
   function parseFields($fields) {
        foreach ($fields as $field) {
            $newFields[] = '`'.$field.'`';
        }

        return implode (", ", $newFields);
   }

   /**
    * Создает строку для запроса, учитывая типы данных
    * @param array $values
    * @return string
    */
   static  function parseValues($values) {
       foreach ($values as $value) {
           if (isset($value)) {
               $newValues[] = is_string($value) ? "'".$value."'" : (string) $value;
           } else {
               $newValues[] = 'NULL';
           }
       }
       return implode(", ", $newValues);
   }

    /**
     * Подготаливает ассоциативный массив для update или insert запроса
     * @param array $values ассоциативный массив, у которого ключи это поля
     * @return string подготовленная строка
     */
    static  function parseValuesWithFields($values) {
       foreach ($values as $key => $value) {
           if (isset($value)) {
               $newValues[] ='`'.$key.'` = ' . (is_string($value) ? "'".$value."'" : (string) $value);
           }
       }
       return implode(", ", $newValues);
   }
   /**
    * Формирует простой where
    * @param string $field
    * @param int/string/array $value
    * @return string
    */
   protected static function simpleWhere($field = null, $value = null) {
        $where = '';
        if (isset($field) && isset($value)) {
            if (is_array($value)) {
                $sqlValue = ' IN (' . implode(',', $value) . ')';
            } else if (is_int($value)) {
                $sqlValue = '='.$value;
            } else {
                $sqlValue = "='$value'";
            }
            $where = " where `$field`".$sqlValue;
        }
        return $where;
   }
}