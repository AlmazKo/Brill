<?php

/**
 * General
 *
 * Класс основных перменных и констант
 */
class General {
    const NAME_DIR_ACTIONS = 'Actions';
    const NAME_DIR_VIEWS = 'Views';
    const NAME_DIR_MODELS = 'Models';
    const NAME_DIR_LIBS = 'Libs';
    private static $std_headers = array();
    const SITE_NAME = '';
    public static $defaultModule;
    public static $loadedModules;
    public static $route;
    public static function init() {}

}
