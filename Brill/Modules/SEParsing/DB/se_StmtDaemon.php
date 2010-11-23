<?php
/*
 * Queries
 *
 * Класc для получения sql с их простой обработкой
 */


class se_StmtDaemon extends Stmt {

    // сбросить инфу по сайтам
    const RESET_PARSER= "UPDATE `sep_Keywords` set `yandex` = 'NoData'";
    const GET_IP = 'SELECT ri_id, ri_ip  FROM z_routeip WHERE ri_isactive = 1 AND ri_quota>200 ORDER BY ri_quota DESC, RAND() LIMIT 0, 1';
    const GET_KEYWORDS = "SELECT * FROM `sep_Keywords` where `yandex` = 'NoData'";
}