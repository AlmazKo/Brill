<?php
/**
 * Класс ошибки
 *
 * @author almazKo
 */
class Error {
    public
        $id,
        $message,
        $type,
        $time;

    public function __construct($message) {
        $this->message = $message;

    }
}

