<?php
/*
 * Поиск модуля, экшена и
 *
 * @author almaz
 */
require_once CORE_PATH . 'Daemons/Daemon.php';

class Underworld {

    public static function countRunningDaemons() {
        return 0;
    }
    /**
     * Вызвать демона
     * @param string $daemon
     * @return Daemon
     */
    public function summon() {
        $request = RegistryRequest::instance();
        // временный костыль, а так все должнол получаться из консоли
        //$params = $_GET;
        $params = $request->get('argv');
        if (!$params) {
            throw new Warning(Daemon::option_null());
        }
        Cli::setArgs($params);

        if (Cli::hasArg(Daemon::KEY_NAME_DAEMON)) {
            $nameDaemon = Cli::getArg(Daemon::KEY_NAME_DAEMON);
        } else {
            if (Cli::hasArg(Daemon::CLI_ARG_HELP)){
                throw new CliInput(Cli::getStringForHelp(Daemon::getHelp()));
            } else {
                 throw new CliInput(Daemon::option_null());
            }
        }
        
        

        


        $module = $this->_loadModuleDaemon($nameDaemon);
        $daemon = new $nameDaemon();

        if (!is_subclass_of($daemon, 'Daemon')) {
            throw new Warning($nameDaemon . ' должен быть унаследован от  Daemon');
        }
        $daemon->setModule($module);
        $daemon->setParams($params); 
        
        if (Cli::hasArg($daemon::CLI_ARG_HELP)){
            throw new CliInput(Cli::getStringForHelp(Daemon::getHelp()));
        }
        return $daemon;
    }

    function  __construct() {}



    /**
     * Ищет по всем модуляем демона, если его находит подключает его файл и возвращает путь на модуль
     * @param string $pathModule
     */
        private static function _loadModuleDaemon($nameDaemon) {
        $dirsModules = scandir(MODULES_PATH);

        foreach ($dirsModules as $nameModule) {
            if ($nameModule != '.' && $nameModule != '..') {
                $pathModule = MODULES_PATH . $nameModule . '/' . $nameModule . '.php';
                $pathDaemon = MODULES_PATH . $nameModule . '/' . General::NAME_DIR_DAEMONS . '/' . $nameDaemon . '.php';
                if (file_exists($pathModule) && file_exists($pathDaemon)) {
                    require_once $pathModule;
                    $module = call_user_func(array($nameModule, 'instance'));
                    $module->configureDaemon();
                    include_once $pathDaemon;
                    return $module;
                }
            }
        }

        Log::warning('Не найден модуль для демона : '.$nameDaemon);
    }

    /**
     * Получить массив - статус всех демонов
     */
    public function status () {

    }

    public function banish($nameDaemon) {
        
    }
}