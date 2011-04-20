<?php
/**
 * General
 *
 * Класс основных перменных и констант
 */

class General {
    //основные эвенты, остальные надо регистрировать
    const
        EVENT_AFTER_CONSTRUCT_ACTION = 'e_InitAction',
        EVENT_AFTER_CONSTRUCT_ACTION_INTERNAL = 'e_InitActionInternal',
        EVENT_BEFORE_RUNACT = 'e_beforeRunAct',
        EVENT_LOGOUT = 'e_Logout';

    //Module settings
    const
        NAME_DIR_ACTIONS = 'Actions',
        NAME_DIR_VIEWS = 'Views',
        NAME_DIR_MODELS = 'Models',
        NAME_DIR_DB = 'DB',
        NAME_DIR_LIB = 'Lib',
        NAME_DIR_DAEMONS = 'Daemons',
        SITE_NAME = '';
    //UserLib стандартные группы пользователей, которые есть всегда
    const
        GROUP_USER = 1,
        GROUP_MANAGER = 10,
        GROUP_ADMIN = 100;
    const
        // максимальное количество одновременно работающих демонов одного класса
        DAEMON_MAX_RUN = 5,
        // максимально количество одновременно работающих демнов
        DAEMON_MAX_ALL = 10;
    
    public static
        //модуль по умолчанию
        $defaultModule,
        //загруженные конфиги модулей
        $loadedModules,
        $route,
        //загруженные библиотеки
        $libs;

    private static
        $_stdHeaders = array(),
        //доступные события
        $_events = array(self::EVENT_AFTER_CONSTRUCT_ACTION => 0,
                         self::EVENT_AFTER_CONSTRUCT_ACTION_INTERNAL => 0,
                         self::EVENT_BEFORE_RUNACT => 0,
                         self::EVENT_LOGOUT => 0
            ),
        //свершившиеся события
        $_accomplishedEvents = array();

    public static function init() {}
    public static function listEvents() {
        return self::$_events;
    }

    /**
     * Срабатывание события
     * @param string $nameEvent
     */
    public static function runEvent($nameEvent) {
        if (array_key_exists($nameEvent, self::$_events)) {
                /*
                 * Проходим по всем инициализированным библиотекам
                 * и вызываем их обработчики на это событие
                 */
                foreach (General::$libs as $lib) {
                    $result = $lib->$nameEvent();
                    if (isset($result)) {
                        if ($result instanceof Error) {
                            /**
                             * внутреннее делегирование полномочий
                             * на страницу ошибок
                             */
                            $context = RegistryContext::instance();
                            $context->setError($result);
                            $iRoute = new InternalRoute();
                            $iRoute->module = 'Pages';
                            $iRoute->action = 'Error';
                            $actR = new ActionResolver();
                            $act = $actR->getInternalAction($iRoute);
                            $act->execute();
                            die;
                        }
                    }
                }
                self::$_events[$nameEvent]++;
        } else {
            Log::warning("Событие $nameEvent не определено");
        }
        return true;
    }

    public static function getCurrentModule() {
        $routing = Routing::instance();
        $module = $routing->module;
        if ($module) {
            return self::$loadedModules[$module];
        } else {
            return self::$loadedModules[self::$defaultModule];
        }

    }
}