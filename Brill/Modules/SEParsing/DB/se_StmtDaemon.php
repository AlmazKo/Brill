<?php
/*
 * Queries
 *
 * Класc для получения sql с их простой обработкой
 */


class se_StmtDaemon extends Stmt {

    // сбросить инфу по сайтам
    const RESET_PARSER= "UPDATE `sep_Keywords` set `yandex` = 'NoData'";
    //получить ip
const GET_INTERFACE_YANDEX_XML = "SELECT i.id, i.interface FROM brill.sep_YandexAccesses as ya
LEFT JOIN sep_Interfaces as i on (i.id=ya.interface_id)
LEFT JOIN sep_InterfaceCountCallToday as it on (it.interface_id=i.id)
LEFT JOIN sep_LimitsIpForHosts as L on (L.host_id=#host_id#)
where it.count<L.every_day OR isnull(it.count)
order by it.count limit 1";

//поставить отметку об использовании ip
const GET_IP_USED = "insert into sep_InterfaceCountCallToday set count=1, interface_id=#interface_id#, host_id=#host_id#
ON DUPLICATE KEY UPDATE count=count + 1, last_date=now()";

    const GET_KEYWORDS = "SELECT * FROM `sep_Keywords` where `yandex` = 'NoData'";
}
