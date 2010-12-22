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
const GET_INTERFACE_YANDEX_XML = "SELECT i.id, i.interface, ya.login, ya.xml_key FROM sep_YandexAccesses as ya
LEFT JOIN st_Interfaces as i on (i.id=ya.interface_id)
LEFT JOIN st_InterfaceCountCallToday as it on (it.interface_id=i.id)
LEFT JOIN st_LimitsIpForHosts as L on (L.host_id=#host_id#)
where it.count<L.every_day OR isnull(it.count)
order by it.count limit 1";

const GET_INTERFACE_SIMPLE = "SELECT i.id, i.interface FROM  st_Interfaces as i
LEFT JOIN st_InterfaceCountCallToday as it on (it.interface_id=i.id)
LEFT JOIN st_LimitsIpForHosts as L on (L.host_id=#host_id#)
where i.type='Usual' and (it.count<L.every_day OR isnull(it.count))
order by it.count limit 1";

//поставить отметку об использовании ip
const GET_IP_USED = "insert into st_InterfaceCountCallToday set count=1, interface_id=#interface_id#, host_id=#host_id#
ON DUPLICATE KEY UPDATE count=count + 1, last_date=now()";

const GET_KEYWORDS_YA_STANDART = "SELECT * FROM `sep_Keywords` as k
join sep_Sets as s on(k.set_id = s.id)
where find_in_set('YaXml', search_type) and `yandex` = 'NoData'";

const GET_PROJECT_SITES = "SELECT * FROM `sep_Projects`";

const GET_FREE_SET ="SELECT s.id FROM sep_Sets as s
left join sep_StatusSetsSearchTypes as sst on (sst.set_id=s.id and sst.search_type='#search_type#')
where  (sst.status='No' OR isnull(sst.status)) AND FIND_IN_SET('YaXml', s.search_type)";

const SET_USED_SET = "insert ignore into sep_StatusSetsSearchTypes set set_id=#set_id#, search_type='#search_type#', status = 'Busy'";

const GET_KEYWORDS_SET = "SELECT k.id, k.name, k.region_id, uk.url FROM `sep_Keywords` as k
left join sep_Urls as uk on (uk.id = k.url_id)
where set_id=#set_id#";

const GET_SITE = "SELECT id FROM `sep_Sites` where name='#name#' limit 1";
const GET_URL = "SELECT id FROM `sep_Urls` where url='#url#' limit 1";
}
