<?php


class au_Stmt extends Stmt {


// получить урлы и позиции для конкертного сайта
const GET_USER = "SELECT * from au_Users where login = '#login#' and password='#password#' AND status='Active' limit 1";

const IS_USER = "SELECT id from au_Users where login = '#login#' limit 1";

// Получить группы пользователя
const GET_GROUPS_USER = "SELECT g.id, g.name from au_Groups as g
Join au_UsersGroups as ug on (ug.user_id=#user_id# and ug.group_id=g.id)";

const GET_LIST_GROUPS = "SELECT id,name FROM au_Groups";

const GET_ALL_USER = "SELECT u.id as user_id, u.login as user_login, u.name as user_name, u.status,g.id as group_id, group_Concat(g.name) as groups  from au_UsersGroups as ug
Join au_Users as u on (ug.user_id=u.id and status='Active')
Join au_Groups as g on (g.id=ug.group_id)
group by user_id";



const ADD_USERS_GROUPS = "INSERT INTO au_UsersGroups (user_id, group_id) values (#user_id#, #group_id#)";

const DEL_USERS_GROUPS_USER = "DELETE FROM au_UsersGroups where user_id = #user_id#";

const GET_SITES_USERS_USER = "SELECT s.host, su.login, su.password FROM `as_SitesUsers` as su
join as_Sites as s on (s.id=su.site_id)
WHERE user_id = #user_id#";
}