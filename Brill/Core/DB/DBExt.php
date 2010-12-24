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
        $query .= ' ON DUPLICATE KEY UPDATE ' . self::parseValuesWithFields($values);
        parent::query($query);
        return parent::$lnk->insert_id;
    }

    /**
     * Пытается вставит строки.
     * @todo !! должно быть в транзакции
     * @param string $sql
     * @param int $count - сколько должно вставится
     * @return bool
     */
    static function tryInsert($sql, $count = 1) {
        parent::query($sql);
        return parent::$lnk->affected_rows == $count;
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
    public static function getOneRow($tblName, $field, $value = null) {
        if (is_array($field)) {
            $where ='where ' . self::parseValuesWithFields($field, ' and ');
        } else {
            $where = self::simpleWhere($field, $value);
        }
        $query = "select * from `$tblName` $where Limit 1";
        $result = parent::query($query);
        $values = null;
        if ($result->num_rows == 1) {
            $values = $result->fetch_assoc();
        } else if ($result->num_rows > 0) {
            Log::warning("Не найдена строка");
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
    public static function getOneRowSql($sql, $lnk = null) {
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
     * Функция обновляет данные
     * @param string $tblName
     * @param string $values
     * @param string $fields
     * @param string $type  ignored duplicated false
     * @return int количество затронутых строк
     */
    function updateOne($tblName, $values, $field, $value = null) {
        if (is_array($field)) {
            $where ='where ' . self::parseValuesWithFields($field, ' and ');
        } else {
            $where = self::simpleWhere($field, $value);
        }

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
        $result = parent::query($query);
        return parent::$lnk->affected_rows;
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
     * Есть ли данные по этом запросу
     * 
     * @param string $sql
     * @param <type> $lnk
     * @return bool
     */
    public static function isData($sql, $lnk = null) {
        $result = parent::query($sql, $lnk);
        if ($result->num_rows > 0) {
            return true;
        } else if ($result->num_rows > 0) {
            return false;
        }
    }
    /**
     * Получает данные по запросу и возращает даннные для класса oTable
     * @param string $sql Query
     * @return array
     */
    function selectToTable($query, $lnk = null) {
        $result = parent::query($query, $lnk);
        $values = $fields = array();
        while ($finfo = mysqli_fetch_field($result)) {
            $fields[] = $finfo->name;
        }

        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $values[] = $row;
            }
        }

        return array($fields, $values);
    }

   /**
     * Получает данные по запросу и возращает даннные для класса oTable
     * @param string $sql Query
     * @return array
     */
    function selectToArray($query, $lnk = null) {
        $result = parent::query($query, $lnk);
        $values = array();
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $values[] = $row;
            }
        }

        return $values;
    }

/**
     * Получает данные по запросу и возращает даннные для класса oList
     * @param string $sql Query
     * @return array
     */
    function selectToList($query, $lnk = null) {
        $result = parent::query($query, $lnk);
        $values = array();
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_row()) {
                $values[$row[0]] = $row[1];
            }
        }
        return $values;
    }

    /**
     * Добавляет всем полям обратные ковычки и возвращает уже строку
     * @param array $fields
     * @return string
     */
   static function parseFields($fields) {
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
               if (is_array($value)) {
                   $value = implode(',', $value);
               }
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
    static  function parseValuesWithFields($values, $sep = ', ') {
       foreach ($values as $key => $value) {
           if (isset($value)) {
               if (is_array($value)) {
                   $value = implode(',', $value);
               }
               $newValues[] ='`'.$key.'` = ' . (is_string($value) ? "'".addslashes($value)."'" : (string) $value);
           }
       }
       return implode($sep, $newValues);
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
                $sqlValue = ' IN (' . implode(',', addslashes($value)) . ')';
            } else if (is_int($value)) {
                $sqlValue = '='.$value;
            } else {
                $sqlValue = "='".addslashes($value)."'";
            }
            $where = " where `$field`".$sqlValue;
        }
        return $where;
   }


}