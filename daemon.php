<?php
/*
 * Фасад для демонов
 *
 */

////@iconv(ENCODING_CODE, 'cp1251', (string) $value->doc->url);
//echo urldecode('http://iskalko.ru/cat/moscow/%EF%F0%EE%E7%F0/');
//die;
require 'Brill/FrontDaemon.php';

FrontDeamon::run();
