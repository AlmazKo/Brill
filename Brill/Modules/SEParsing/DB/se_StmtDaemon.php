<?php
/*
 * Queries
 *
 * Класc для получения sql с их простой обработкой
 */

class se_StmtDaemon extends Stmt {

    const DB_ACC = 'webexpert_acc';
    // сбросить инфу по сайтам
    const RESET_PARSER= "UPDATE `sep_Keywords` set `yandex` = 'NoData'";
    //получить ip
const GET_INTERFACE_YANDEX_XML = "SELECT i.id, i.interface, ya.login, ya.xml_key FROM sep_YandexAccesses as ya
LEFT JOIN st_Interfaces as i on (i.id=ya.interface_id)
LEFT JOIN st_InterfaceCountCallToday as it on (it.interface_id=i.id)
LEFT JOIN st_LimitsIpForHosts as L on (L.host_id=#host_id#)
where (it.count < L.every_day OR isnull(it.count)) and ya.status = 'Active'
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
const GET_PROJECT_SITE = "SELECT * FROM `sep_Projects` limit 1";
const GET_FREE_SET ="SELECT s.id FROM sep_Sets as s
left join sep_StatusSetsSearchTypes as sst on (sst.set_id=s.id and sst.search_type=:search_type)
where  (sst.status='No' OR isnull(sst.status)) AND FIND_IN_SET(:search_type, s.search_type) limit 1";

const HAS_USED_SET = "select set_id from #NEW#.sep_StatusSetsSearchTypes where set_id = :set_id";

const GET_FREE_KEYWORDS = "SELECT kw_id, kw_parent, kw_keyword, kw_url, kw_range, kw_premiya, ss_isyaregion, rg_region
FROM #DB_ACC#.z_keywords AS t1
JOIN #DB_ACC#.z_seoset AS t2 ON t1.kw_parent = t2.ss_id
LEFT JOIN #DB_ACC#.z_regions AS t3 ON t2.ss_region = t3.rg_id
WHERE t1.kw_parsed =  '0'
AND t2.ss_isactive =  '1'
ORDER BY t1.kw_parent DESC 
LIMIT 0 , :limit" ;

// получение незанятого сета
const GET_SET_FREE = "SELECT kw_parent, ssst.* FROM #DB_ACC#.z_keywords AS t1
JOIN #DB_ACC#.z_seoset AS t2 ON t1.kw_parent = t2.ss_id
left JOIN #DB_ACC#.sep_StatusSetsSearchTypes AS ssst ON ssst.set_id = t2.ss_id
WHERE t2.ss_isactive = '1' AND  t1.kw_parsed= '0' AND (ISNULL(ssst.status) OR ssst.status='No')
order by kw_parent desc
LIMIT 1";


const GET_SET_FREE_GOOGLE = "SELECT kw_parent, ssst.* FROM #DB_ACC#.z_keywords AS t1
JOIN #DB_ACC#.z_seoset AS t2 ON t1.kw_parent = t2.ss_id
left JOIN #DB_ACC#.sep_StatusSetsSearchTypes AS ssst ON ssst.set_id = t2.ss_id
WHERE t2.ss_isactive = '1' AND  t1.kw_parsedgoogle= '0' AND (ISNULL(ssst.status) OR ssst.status='No')
order by kw_parent desc
LIMIT 1";

const GET_FREE_SET_ACC = "SELECT kw_parent FROM #DB_ACC#.z_keywords AS t1
JOIN #DB_ACC#.z_seoset AS t2 ON t1.kw_parent = t2.ss_id
WHERE t2.ss_isactive = '1' AND  t1.kw_parsed= '0'
order by kw_parent desc
LIMIT 1";

const GET_KEYWORDS_BY_SET = "SELECT kw_id, kw_parent,kw_keyword, kw_url, kw_range, kw_premiya, ss_isyaregion, rg_region 
FROM #DB_ACC#.z_keywords AS t1
JOIN #DB_ACC#.z_seoset AS t2 ON t1.kw_parent = t2.ss_id
LEFT JOIN #DB_ACC#.z_regions AS t3 ON t2.ss_region = t3.rg_id
WHERE  t1.kw_parsed='0' AND t2.ss_id = :set_id
ORDER BY t1.kw_parent Desc";


const GET_KEYWORDS_BY_SET_GOOGLE = "SELECT kw_id, kw_parent,kw_keyword, kw_url, kw_range, kw_premiya, ss_isyaregion, rg_region 
FROM #DB_ACC#.z_keywords AS t1
JOIN #DB_ACC#.z_seoset AS t2 ON t1.kw_parent = t2.ss_id
LEFT JOIN #DB_ACC#.z_regions AS t3 ON t2.ss_region = t3.rg_id
WHERE  t1.kw_parsedgoogle='0' AND t2.ss_id = :set_id
ORDER BY t1.kw_parent Desc";

const GET_SETS_BY_IDS = "SELECT ss_id, ss_queries FROM #DB_ACC#.`z_seoset` WHERE ss_id in (:sets)";

const SET_USED_SET = "insert ignore into #DB_ACC#.sep_StatusSetsSearchTypes set set_id=:set_id, search_type=:search_type, status = :status
    ON DUPLICATE KEY UPDATE status = :status";

const GET_PROJECT_FREE ="SELECT p.id, p.site, p.site_id FROM sep_Projects as p
left join sep_StatusProjectsSearchTypes as spt on (spt.project_id=p.id and spt.search_type='#search_type#')
where  (spt.status='No' OR isnull(spt.status))";

const SET_PROJECT_SET = "insert ignore into sep_StatusProjectsSearchTypes set project_id=#project_id#, search_type='#search_type#', status = '#status#'";

const GET_KEYWORDS_SET = "SELECT k.id, k.name, k.region_id, uk.url from sep_SetsKeywords  as sk
join sep_Keywords as k on (k.id=sk.keyword_id)
join sep_Urls as uk on (uk.id = sk.url_id)
join sep_Regions as r on (r.id = k.region_id)
where set_id=#set_id#";

const GET_SITE = "SELECT id FROM `sep_Sites` where name='#name#' limit 1";
const GET_URL = "SELECT id FROM `sep_Urls` where url='#url#' limit 1";

const GET_SEOCOMP_YA = 
"SELECT sc_id  FROM #DB_ACC#.z_seocomp WHERE sc_parent = :parent AND sc_date = :date AND sc_keyword = :keyword LIMIT 1";

const SET_SEOCOMP_YA =
"INSERT ignore INTO #DB_ACC#.z_seocomp SET sc_parent = :parent, sc_date = :date, sc_poss = :seoh, sc_keyword = :keyword, sc_range = :range, sc_premiya = :premiya
     ON DUPLICATE KEY UPDATE sc_poss = :seoh";
    public static function prepare($nameStmt) {
        return str_replace("#DB_ACC#", se_StmtDaemon::DB_ACC, $nameStmt);
    }
}
