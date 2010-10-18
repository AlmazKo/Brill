<?php
/*
 * Statements
 *
 */
class Queries {

    public function getSql($sql, $args = array()) {
        $pSql = constant("self::$sql");
        return $pSql;
    }

}
