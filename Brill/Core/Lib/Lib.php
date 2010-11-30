<?php
abstract class Lib {
    // событие срабатываемое в конце конструктора Action
    // должны быть ограничения для внутренних запросах
    public function e_InitAction() {}

    /**
     * Событие "Инициализация внутреннего экшена"
     */
    public function e_InitActionInternal() {}

    //TODO сделать кучу других евентов
}
