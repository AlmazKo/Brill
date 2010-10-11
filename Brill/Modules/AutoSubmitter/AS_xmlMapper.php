<?php

/**
 * у родителя описано чтение файлов, валидация, сохрание SimpleXMl объекта
 * получение атрубитов и другие общие методы
 *
 */
class AS_xmlMapper extends xmlMapper {
    //мапер для рассылки

    //релизоват такие методы
    function isRule($name);
    function getRule($name);//получить правило
    function listRules();// получает масив правил
    function isAjax($rule);
    function getGet($rule);// массив полей
    function getPost($rule);
    function getNullFields($rule);
/* ................*/
}

