<?php
/*
 * Statements
 *
 */
class Stmt {
    const
        LIMIT = 'limit',
        OFFSET = 'offset',
        PAGE = 'page',
        ORDER = 'order',
        DIRECTION = 'direction',
        RAND = 'rand';

    //массив стандартных настроек
    protected static $_arr = array(
        self::DIRECTION => 'ASC',
        self::LIMIT => 1000,
        self::OFFSET => 1000,
        self::PAGE => 0,
        self::ORDER => null,
        self::RAND => null);

    /**
     * Подготавливает sql строку.
     * ищет в коде конструкции типа #var# и заменяет их, на переданные значения.
     * @param string $sql
     * @param array $args
     * @return string
     */
    public function prepare($sql, $args = array()) {
        if (!$sql) {
            LogMysql::errorQuery('Пустой sql');
        }
        $pSql = $sql;
        if (is_array($args)) {
            foreach ($args as $key => $value) {
                if (!array_key_exists($key, self::$_arr)) {
                    if ((string)$value !== (string)(int) $value) {
                        //$value = '\'' . $value . '\'';
                    } else if (is_array($value)) {
                        $value = '\'' . implode(',', $value) .'\'';
                    }
                    $pSql = str_replace("#$key#", $value, $pSql);
                }
            }
            if ($pSql && is_array($args)) {
                $aExt = array_merge(self::$_arr, $args);
                $pSql .= self::_addExt($aExt);
            }

        }
        return $pSql;
    }

    public function prepare2($sql, $args = array(), $ext = false) {
        if (!$sql) {
            LogMysql::errorQuery('Пустой sql');
        }
        $pSql = $sql;

        if (is_array($args)) {
            foreach ($args as $key => $value) {
                if (!array_key_exists($key, self::$_arr)) {
                    if (!get_magic_quotes_gpc()) {
                        $value = addslashes($value);
                    }
                    $pSql = str_replace("#$key#", $value, $pSql);
                }
            }
            if (is_array($ext)) {
                $aExt = array_merge(self::$_arr, $ext);
                $pSql .= self::_addExt($aExt);
            }

        }
        return $pSql;
    }

    /**
     * Добавяет к запросу ограничивающие и сортирующие функции
     * @param array $arr
     * @return string
     */
    protected static function _addExt(array $arr) {
        $str = '';
        $str .= self::_addSort($arr[self::ORDER], $arr[self::DIRECTION]);
        $str .= self::_addLimit($arr[self::LIMIT], $arr[self::OFFSET], $arr[self::PAGE], $arr[self::RAND]);
        return $str;
    }

    /**
     * Возвращает строку с лимитом
     * @param int $limit
     * @param int $offset
     * @param int $page
     * @return string
     */
    protected static function _addLimit($limit, $offset, $page, $rand) {
        //TODO доделать
        $str = '';
        if ($limit) {
            if ($rand) {
                $str .= ', RAND()';
            }
            $str .= ' limit ' . $limit;
        }
        return $str;
    }

    /**
     * Возвращает строку с сортировкой
     * @param string $order
     * @param string $direction
     * @return string
     */
    protected static function _addSort($order, $direction) {
        $str = '';
        if ($order) {
            $str = ' order by ' . $order;
            if ($direction) {
                $str .= ' ' . $direction;
            }
        }
        return $str;
    }
}