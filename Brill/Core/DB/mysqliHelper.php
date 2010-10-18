<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of MysqliHelper
 *
 * @author almaz
 */
class mysqliHelper {

    private static $int = array(MYSQLI_TYPE_TINY,
                                MYSQLI_TYPE_SHORT,
                                MYSQLI_TYPE_LONG,
                                MYSQLI_TYPE_LONGLONG,
                                MYSQLI_TYPE_INT24,
                                MYSQLI_TYPE_BIT
                              );

    private static $float = array(MYSQLI_TYPE_FLOAT,
                                  MYSQLI_TYPE_DOUBLE,
                                  MYSQLI_TYPE_NEWDECIMAL
                                 );
    private static $string = array(MYSQLI_TYPE_TIMESTAMP,
                                   MYSQLI_TYPE_DATE,
                                   MYSQLI_TYPE_TIME,
                                   MYSQLI_TYPE_DATETIME,
                                   MYSQLI_TYPE_YEAR,
                                   MYSQLI_TYPE_NEWDATE,
                                   MYSQLI_TYPE_ENUM,
                                   MYSQLI_TYPE_SET,
                                   MYSQLI_TYPE_VAR_STRING,
                                   MYSQLI_TYPE_STRING,
                                   MYSQLI_TYPE_CHAR,
                                  );

    private static $blob = array(MYSQLI_TYPE_TINY_BLOB,
                                 MYSQLI_TYPE_MEDIUM_BLOB,
                                 MYSQLI_TYPE_LONG_BLOB,
                                 MYSQLI_TYPE_BLOB
                                );
    /**
     * Возвращает Type specification chars для поля
     * @param array $fieldType
     */
    public static  function getSpecification($fieldsType, $params = null) {

        $strTypes = '';
        if (is_array($fieldsType)) {
            foreach ($fieldsType as $options) {
                if (!$params || in_array($options->name, $params)) {
                    if (in_array($options->type, self::$int)) {
                       $strTypes .= 'i';
                       continue;
                    }
                    if (in_array($options->type, self::$string)) {
                       $strTypes .= 's';
                       continue;
                    }
                    if (in_array($options->type, self::$float)) {
                       $strTypes .= 'd';
                       continue;
                    }
                    if (in_array($options->type, self::$blob)) {
                       $strTypes .= 'b';
                       continue;
                    }
                    Log::warning('Не определен тип поля для type='.$options->type);
                }
               
            }
        }
        return $strTypes;
    }

    /**
     * Переопеределяет эту долбанную bind_param
     * @param <type> $stmt
     * @param <type> $params
     */
    public static function bindParam (&$stmt, $types, &$params) {

        switch (count($params)) {
            case 1:
                echo '90909';
                $stmt->bind_param($types, &$params[0]);
            break;
            case 2:
                $stmt->bind_param($types, $params[0], $params[1]);
            break;
            case 3:
                $stmt->bind_param($types, $params[0], $params[1], $params[2]);
            break;
            case 4:
                $stmt->bind_param($types, $params[0], $params[1], $params[2], $params[3]);
            break;
            case 5:
                $stmt->bind_param($types, $params[0], $params[1], $params[2], $params[3], $params[4]);
            break;
            case 6:
                $stmt->bind_param($types, $params[0], $params[1], $params[2], $params[3], $params[4], $params[5]);
            break;
            case 7:
                $stmt->bind_param($types, $params[0], $params[1], $params[2], $params[3], $params[4], $params[5], $params[6]);
            break;
            case 8:
                $stmt->bind_param($types, $params[0], $params[1], $params[2], $params[3], $params[4], $params[5], $params[6], $params[7]);
            break;
            case 9:
                $stmt->bind_param($types, $params[0], $params[1], $params[2], $params[3], $params[4], $params[5], $params[6], $params[7], $params[8]);
            break;
            case 10:
                $stmt->bind_param($types, $params[0], $params[1], $params[2], $params[3], $params[4], $params[5], $params[6], $params[7], $params[8], $params[9]);
            break;
            default:
                Log::warning('Не поддерживаетсы больше 10 параметров в prepare statements');
        }
    }



   /*
    function refValues($arr){
        if (version_compare(phpversion(), '5.3') >= 0) //Reference is required for PHP 5.3+
        {
            $refs = array();
            foreach($arr as $key => $value)
                $refs[$key] = &$arr[$key];
            return $refs;
        }
        return $arr;
    }
    *
    */
}

