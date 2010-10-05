<?php
/* 
 * Класс
 */


/**
 * Description of Front
 *
 * @author almaz
 */


require 'Config.php';


class Front {

    private static $defaultAction;
    static function run() {
        $instance = new Front();
        $instance->init();
        $instance->handleRequest();
    }

    private function init() {
       /*
        * Если будет обработки конфига или других редко меняющихся вещей
        * например новых модулей...
        */
        self::$defaultAction = 'Welcome';
    }

    private function handleRequest() {
        $request = RegistryRequest::instance();
        $context = RegistryContext::instance();
        $actR = new ActionResolver();
        $act = $actR->getAction($request);
        $act->execute($context);
    }
}