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
        EVENT_BEFORE_RUNACT = 'e_beforeRunAct';

    const
        NAME_DIR_ACTIONS = 'Actions',
        NAME_DIR_VIEWS = 'Views',
        NAME_DIR_MODELS = 'Models',
        NAME_DIR_DB = 'DB',
        NAME_DIR_LIB = 'Lib',
        NAME_DIR_DAEMONS = 'Daemons',
        SITE_NAME = '';

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
                         self::EVENT_BEFORE_RUNACT => 0
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
                    $lib->$nameEvent();
                }
                self::$_events[$nameEvent]++;
        } else {
            Log::warning("Событие $nameEvent не определено");
        }
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