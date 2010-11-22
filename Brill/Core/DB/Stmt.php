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
        DIRECTION = 'direction';

    //массив стандартных настроек
    protected static $_arr = array(
        self::DIRECTION => 'ASC',
        self::LIMIT => 1000,
        self::OFFSET => 1000,
        self::PAGE => 0,
        self::ORDER => null);

    public function prepare($sql, $args = array()) {
        $aExt = array_merge(self::$_arr, $args);
        $added = array();
        $pSql = $sql;
        foreach ($args as $key => $value) {
            if (!array_key_exists($key, self::$_arr)) {
                $pSql = str_replace("#$key#", $value, $pSql);
            }
        }
        if ($pSql) {
            $pSql .= self::_addExt($aExt);
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
        $str .= self::_addLimit($arr[self::LIMIT], $arr[self::OFFSET], $arr[self::PAGE]);
        return $str;
    }

    /**
     * Возвращает строку с лимитом
     * @param int $limit
     * @param int $offset
     * @param int $page
     * @return string
     */
    protected static function _addLimit($limit, $offset, $page) {
        //TODO доделать
        $str = '';
        if ($limit) {
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