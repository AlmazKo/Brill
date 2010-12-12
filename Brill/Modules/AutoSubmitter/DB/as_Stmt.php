<?php
/*
 * Queries
 *
 * Клас для получения sql с их простой обработкой
 */

/*
 * FIX ВСЕ НАХ УБРАТЬ и заменить на хранилище prepare_stmt!!!
 */
class as_Stmt extends Stmt {


// все рассылки
const SUBSCRIBES = "SELECT su.id, su.name, su.form, si.host  FROM brill.as_SubscribesSites as ss
join `brill`.`as_Subscribes` as su on(su.id=ss.subscribe_id)
join `brill`.`as_Sites` as si on(si.id=ss.site_id)";

//список свободных рассылок
const GET_SITE_IN_SUBSCRIBE = "SELECT * FROM as_SubscribesSites as ss where ss.subscribe_id=#subscribe_id# AND status <> 'Ok' limit 1";
#const GET_SITE_IN_SUBSCRIBE = "SELECT * FROM as_SubscribesSites as ss where ss.subscribe_id=#subscribe_id# limit 1";
//список используемых рассылок
const GET_SITE_IN_USED_SUBSCRIBE = "SELECT * FROM as_SubscribesSites as ss where ss.subscribe_id=#subscribe_id# AND FIND_IN_SET(status,'Busy,Error') limit 1";

//получить рассылку пользователя
const GET_SUBSCRIBE_USER = "SELECT * FROM as_Subscribes  where id=#id# limit 1";

const GET_SUBSCRIBES_USER = "SELECT id, name, form FROM `brill`.`as_Subscribes` where user_id=#user_id#";

const GET_SUBSCRIBES_STATUS_USER = "SELECT subscribe_id, GROUP_CONCAT(site_id) as site_ids, GROUP_CONCAT(status) as status_ids
FROM `brill`.`as_SubscribesSites` as ss
JOIN as_Subscribes as s on (ss.subscribe_id = s.id)
WHERE user_id=#user_id#
GROUP BY subscribe_id;";

}