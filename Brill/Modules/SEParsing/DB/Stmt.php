<?php
/*
 * Queries
 *
 * Клас для получения sql с их простой обработкой
 */

/*
 * FIX ВСЕ НАХ УБРАТЬ и заменить на хранилище prepare_stmt!!!
 */
class Stmt {


// получить урлы и позиции для конкертного сайта
const URLS_AND_POS_FOR_SITE = "SELECT k.id as id, k.name, p.pos, p.pos_dot, u.url FROM `Positions` as p
join Sites as s on (s.id=p.site_id)
join Keywords as k on (k.id=p.keyword_id)
join Urls as u on (u.id=p.url_id)
WHERE p.site_id=#site_id# and to_days(p.date) = to_days(now())";

// получить все ключевики
const ALL_KEYWORDS = "SELECT k.id as id,  s.id as s_id, t.id as t_id, k.name, k.yandex, t.name as thematic, s.name as `set` FROM `sep_Keywords` as k
left join `sep_Thematics` as t on (t.id=k.thematic_id)
left join `sep_Sets` as s on (s.id=k.set_id)
left join `sep_Urls` as u on (u.id=k.url_id)
";

// получить все ключевики кокретной тематики
const ALL_KEYWORDS_THEMATIC = "SELECT k.id as id,  s.id as s_id, t.id as t_id, k.name, k.yandex, t.name as thematic, s.name as `set` FROM `sep_Keywords` as k
left join `sep_Thematics` as t on (t.id=k.thematic_id)
left join `sep_Sets` as s on (s.id=k.set_id)
where t.id=#t_id#";

const ALL_KEYWORDS_SET = "SELECT k.id as id,  s.id as s_id, t.id as t_id, k.name, k.yandex, t.name as thematic, s.name as `set` FROM `sep_Keywords` as k
left join `sep_Sets` as s on (s.id=k.set_id)
left join `sep_Thematics` as t on (t.id=k.thematic_id)
where s.id=#s_id#";

const URLS_AND_POS_FOR_SET = "SELECT k.id AS k_id, k.name as k_name, st.id as set_id, s.id AS site_id, s.name, p.pos, u.url, p.pos_dot
FROM `Positions` AS p
JOIN Sites AS s ON ( s.id = p.site_id )
JOIN Keywords AS k ON ( k.id = p.keyword_id )
JOIN Urls AS u ON ( u.id = p.url_id )
JOIN `Sets` AS st ON ( st.id = k.set_id )
WHERE set_id = #set_id# AND to_days( p.date ) = to_days( now( ) )
ORDER BY k_id, pos DESC";

//получить все сайты с их урлами и позициями, по указанному ключевику
const URLS_AND_POS_FOR_KEYWORD = "SELECT s.id as site_id, s.name, p.pos_dot, p.pos, u.url FROM `sep_Positions` as p
join sep_Sites as s on (s.id=p.site_id)
join sep_Keywords as k on (k.id=p.keyword_id)
join sep_Urls as u on (u.id=p.url_id)
WHERE keyword_id=#keyword_id#"; // and to_days(p.date) = to_days(now())-1

// получить все тематики
const ALL_THEMATICS = "SELECT id, name FROM `sep_Thematics`";

// получить все регионы
const ALL_REGIONS = "SELECT id, name FROM `sep_Regions`";

// получить все сеты
const ALL_SETS = "SELECT id, name FROM `Sets`";

// сбросить инфу по сайтам
const RESET_PARSER= "UPDATE `Keywords` set `yandex` = 'NoData'";

    public function prepareSql($sql, $args = array()) {
        $pSql = $sql;
        foreach ($args as $key => $value) {
            $pSql = str_replace("#$key#", $value, $pSql);

        }
        return $pSql;
    }

}