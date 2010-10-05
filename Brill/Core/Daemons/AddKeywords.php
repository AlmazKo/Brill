<?php
/* 
 * Добавление ключевых слов из текстового файла
 */
require_once DIR_PATH . '/Models/Keywords.php';
$k = new Keywords();
$handle = fopen(DIR_PATH . "/tmp/keywords.txt", "r");

while (!feof($handle)) {
    $str = ucfirst(strtolower(trim(fgets($handle, 512))));
    if ($str) {
        $k->name = $str;
        $k->add();
    }

}
fclose($handle);