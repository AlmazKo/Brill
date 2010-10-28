<?php
/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of DBExt
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
    static function insert($tblName, $values, $type = null) {
        $aValues = self::parseValues($values);
        $aFields = self::parseFields(array_keys($values));
        $query = "insert IGNORE into `$tblName` (" . $aFields . ") "
                   . "values (" . $aValues . ") ";
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
     * @param <type> $tblName
     * @param <type> $pk
     * @param <type> $field
     * @return <type>
     */
    public static function getOneRow($tblName, $field, $value) {
        $query = "select * from `$tblName` where $field=$value Limit 1";

        $result = parent::query($query);
        $values = null;
        if ($result->num_rows == 1) {
            $values = $result->fetch_assoc();
        }
        return $values;
    }

    public static function getRows($tblName, $field, $value) {
        $query = "select * from `$tblName` where $field=$value";
        $result = parent::query($query);
        $values = null;
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $values[] = $row;
            }
        }
        return $values;
    }
    
    /*
     * Получает значения по полю
     * used for only unique fields
     */
    static function select($tblName) {

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
    function selectToArray($query) {
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
     *
     * @param string $tblName
     * @param string $values
     * @param string $fields
     * @param string $type  ignored duplicated false
     */
    function update ($tblName, $values, $field, $value) {
        $value = is_string($value) ? "'".$value."'" : (string) $value;
        $aSets = self::parseValuesWithFields($values);
        $query = "update `$tblName` set " . $aSets . " where $field=$value";
        parent::query($query);
        return parent::$lnk->affected_rows;

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


      static  function parseValuesWithFields($values) {
       foreach ($values as $key => $value) {
           if (isset($value)) {
               $newValues[] ='`'.$key.'` = ' . (is_string($value) ? "'".$value."'" : (string) $value);
           }
       }
       return implode(", ", $newValues);
   }


}