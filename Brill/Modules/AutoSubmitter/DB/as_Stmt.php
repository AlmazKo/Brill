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


const GET_SITE_IN_SUBSCRIBE = "SELECT * FROM as_SubscribesSites as ss where ss.subscribe_id=#subscribe_id# AND status = 'No' limit 1";
}
