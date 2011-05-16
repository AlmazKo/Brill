<?php
/*
 * Queries
 *
 * Клас для получения sql с их простой обработкой
 */

/*
 * FIX ВСЕ НАХ УБРАТЬ и заменить на хранилище prepare_stmt!!!
 */
class st_StmtDaemon extends Stmt {

    // сбросить инфу по сайтам
    const RESET_PARSER= "UPDATE `sep_Keywords` set `yandex` = 'NoData'";
    //получить ip
const GET_INTERFACE_YANDEX_XML = "SELECT i.id, i.interface, ya.login, ya.xml_key FROM sep_YandexAccesses as ya
LEFT JOIN st_Interfaces as i on (i.id=ya.interface_id)
LEFT JOIN st_InterfaceCountCallToday as it on (it.interface_id=i.id)
LEFT JOIN st_LimitsIpForHosts as L on (L.host_id=#host_id#)
where (it.count < L.every_day OR isnull(it.count))  and ya.status = 'Active'
order by it.count limit 1";

const GET_INTERFACE_FOR_GOOGLE = "SELECT  `interf`.`id`, `interf`.`interface` FROM  #DB_NEW#.st_Interfaces as `interf`
LEFT JOIN #DB_NEW#.st_InterfaceCountCallToday as `intcall` on (`intcall`.`interface_id`=`interf`.`id`)
LEFT JOIN #DB_NEW#.st_LimitsIpForHosts as `L` on (`L`.`host_id`=`intcall`.host_id)
where `interf`.`type`='Usual' and (`intcall`.`count`<`L`.`every_day` OR isnull(`intcall`.`count`))
and (isnull(`L`.`host_id`) or `L`.`host_id`=2)
order by `intcall`.`count` ASC limit 1";

const GET_INTERFACE_SIMPLE = "SELECT i.id, i.interface FROM  st_Interfaces as i
LEFT JOIN st_InterfaceCountCallToday as it on (it.interface_id=i.id)
LEFT JOIN st_LimitsIpForHosts as L on (L.host_id=#host_id#)
where i.type='Usual' and (it.count<L.every_day OR isnull(it.count))
order by it.count limit 1";

//поставить отметку об использовании ip
const SET_INTERFACE_USED = "insert   into   st_InterfaceCountCallToday set count=1, interface_id=:interface_id, host_id=:host_id
ON DUPLICATE KEY UPDATE count=count + 1, last_date=now()";

    const GET_KEYWORDS = "SELECT * FROM `sep_Keywords` where `yandex` = 'NoData'";

    const GET_PROJECT_SITES = "SELECT * FROM `sep_Projects`";

}
