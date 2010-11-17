<?php
/*
 * Statements
 *
 */
class Stmt {
    public function prepareSql($sql, $args = array()) {
        $pSql = $sql;
        foreach ($args as $key => $value) {
            $pSql = str_replace("#$key#", $value, $pSql);
        }
        return $pSql;
    }
}
