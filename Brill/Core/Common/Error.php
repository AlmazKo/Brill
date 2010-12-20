<?php
/**
 * Класс ошибки
 *
 * @author almazKo
 */

class Error {
    const
        // Необходима остановка скрипта - действие по умолчанию
        TYPE_WARNING = 0,
        // Остановка скипта не требуется, мелкая ошибка, которая сильно не влияет на работу скрипта
        TYPE_NOTICE = 1;
    
    //TODO сделать приватными
    public
        $id,
        $message,
        $type,
        $time;

    public function isWarning() {
        return (self::TYPE_WARNING == $this->type);
    }

    public function isNotice() {
        return (self::TYPE_NOTICE == $this->type);
    }

    public function getMessage () {
        return $this->message;
    }
    public function __construct($message, $type = Error::TYPE_WARNING) {
        $this->message = $message;
        $this->type = $type;
        $this->time = time();
    }

    public function  __toString() {
        return $this->getMessage();
    }
}

