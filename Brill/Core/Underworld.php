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
        $cli = Cli::getInstance();
        if (!$cli->getArgs()) {
            throw new CliInput(Daemon::option_null());
        }
        
        if ($cli->hasArg(Daemon::KEY_NAME_DAEMON)) {
            $nameDaemon = $cli->getArg(Daemon::KEY_NAME_DAEMON);
        } else {
            if ($cli->hasArg(Daemon::CLI_ARG_HELP)){
                throw new CliInput($cli->getStringForHelp(Daemon::getHelp()));
            } else {
                 throw new CliInput(Daemon::option_null());
            }
        }

        $nameDaemon = $cli->getArg(Daemon::KEY_NAME_DAEMON);
        if ('google' === $nameDaemon) {
            $nameDaemon = 'se_ParserGoogle';
        }
        $daemon = $this->_getDaemon($nameDaemon);


        if ($cli->hasArg(Daemon::CLI_ARG_HELP)){
            throw new CliInput(Cli::getStringForHelp(call_user_func(array($daemon, 'getHelp'))));
        }
        $daemon->initialize($cli->getArgs());
        return $daemon;
    }

    function  __construct() {}

    protected function _getDaemon($nameDaemon) {
        $module = $this->_loadModuleForDaemon($nameDaemon);
        $daemon = new $nameDaemon();
        if (!is_subclass_of($daemon, 'Daemon')) {
            throw new Warning($nameDaemon . ' должен быть унаследован от  Daemon');
        }
        $daemon->setModule($module);
        return $daemon;
    }

    /**
     * Ищет по всем модуляем демона, если его находит подключает его файл и возвращает путь на модуль
     * @param string $pathModule
     */
        private static function _loadModuleForDaemon($nameDaemon) {
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

        throw new Warning('Не найден модуль для демона : '.$nameDaemon);
    }

    /**
     * Получить массив - статус всех демонов
     */
    public function status () {

    }

    public function banish($nameDaemon) {
        
    }
}