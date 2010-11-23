<?php
/*
 * Поиск модуля, экшена и
 *
 * @author almaz
 */
require_once CORE_PATH . 'Daemons/Daemon.php';

class Underworld {

    /**
     * Вызывать демона
     * @param string $daemon
     * @return Daemon
     */
    public function summon($daemon = 'se_ParserYandexXml') {
        $sep = '/';
/*
 * module=se daemon=ParserYandexXml
 */
        $module = 'SEParsing';
        $modulePath = MODULES_PATH . 'SEParsing' . $sep . $module . '.php';
        require_once $modulePath;
        $module = new $module();
        $module->configureDaemon();
        $filePath = MODULES_PATH . 'SEParsing' . $sep . General::NAME_DIR_DAEMONS . $sep . $daemon . '.php';
        if (file_exists($filePath)) {

            require_once $filePath;
            
            if (class_exists($daemon)) {
                $daemon = new $daemon();
                if (!is_subclass_of($daemon, 'Daemon')) {
                    Log::warning($classAction . ' должен быть унаследован от Daemon');
                }
                $daemon->setModule($module);
                return $daemon;
            } else {
                 Log::warning('Не найден класс '.$daemon);
            }
        } else {
            Log::warning('Не найден файл: '.$filePath);
        }

    }

    function  __construct() {}
}
