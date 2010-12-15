<?php


class au_Stmt extends Stmt {


// получить урлы и позиции для конкертного сайта
const GET_USER = "SELECT * from au_Users where login = '#login#' and password='#password#' AND status='Active' limit 1";

// Получить группы пользователя
const GET_GROUPS_USER = "SELECT g.id, g.name from au_Groups as g
Join au_UsersGroups as ug on (ug.user_id=#user_id# and ug.group_id=g.id)";

const GET_ALL_USER = "SELECT u.id as user_id, u.login as user_login, u.name as user_name, u.status,g.id as group_id, group_Concat(g.name) as groups  from au_UsersGroups as ug
Join au_Users as u on (ug.user_id=u.id)
Join au_Groups as g on (g.id=ug.group_id)
group by user_id";

}