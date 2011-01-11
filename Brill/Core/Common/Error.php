<?php
/**
 * Класс ошибки
 * собирает всю иняорфмацию о случившнейся ошрибкие
 * @author almazKo
 */

class Error {
    const
        // Необходим останов скрипта - действие по умолчанию
        TYPE_WARNING = 0,
        // Останов скипта не требуется, мелкая ошибка, которая сильно не влияет на работу скрипта
        TYPE_NOTICE = 1;
    
    protected
        $_id,
        $_message,
        $_type,
        $_time,
        $_debugTrace;

    public function __construct($message, $type = Error::TYPE_WARNING) {
        $this->_message = $message;
        $this->_type = $type;
        $this->_time = microtime(true);
        $this->_debugTrace = debug_backtrace();
    }

    public function  __toString() {
        return $this->getMessage();
    }
    
    /**
     * Является ли ошибка критической
     * @return bool
     */
    public function isWarning() {
        return (self::TYPE_WARNING == $this->_type);
    }

    /**
     * Является ли ошибка не критичной для дальнейшего продолжения работы программы
     * @return bool
     */
    public function isNotice() {
        return (self::TYPE_NOTICE == $this->_type);
    }

    /**
     * Получить текст сообщения ошибки
     * @return string
     */
    public function getMessage () {
        return $this->_message;
    }

    /**
     * Возвращает отладочную информацию в виде массива
     * @return array
     */
    public function getDebug() {
        return $this->_debugTrace;
    }

    /**
     * Возвращает время происшествия ошибки
     * @return float
     */
    protected function getTime() {
        return $this->_time;
    }
}

