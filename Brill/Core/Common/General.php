<?php

/**
 * General
 *
 * Класс основных перменных и констант
 */
class General {
    //основные эвенты, остальные надо регистрировать
    const
        EVENT_AFTER_CONSTRUCT_ACTION = 'e_InitAction';
    const 
        NAME_DIR_ACTIONS = 'Actions',
        NAME_DIR_VIEWS = 'Views',
        NAME_DIR_MODELS = 'Models',
        NAME_DIR_LIBS = 'Libs',
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
        $_events = array('e_InitAction'),
        //свершившиеся события, т.е. события которые повторно уже не могут произойти
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
        if (in_array($nameEvent, self::$_events)) {
            if (!in_array($nameEvent, self::$_accomplishedEvents)) {
                foreach (General::$libs as $lib) {
                    $lib->$nameEvent();
                }
                self::$_accomplishedEvents[] = $nameEvent;
            } else {
               Log::warning("Событие $nameEvent не может быть повторно вызвано");
            }

        } else {
            Log::warning("Событие $nameEvent не определено");
        }
    }
}