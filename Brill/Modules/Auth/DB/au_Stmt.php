<?php


class au_Stmt extends Stmt {


// получить урлы и позиции для конкертного сайта
const GET_USER = "SELECT * from au_Users where login = '#login#' and password='#password#' limit 1";
}