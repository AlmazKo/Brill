<?php
/* 
 * Обвертка RegistryContext для Action
 * чтобы провернуть поддержку пространства имен в шаблонах
 *
 */
class ActionWrapContext{
    // префикс добавляемый ко всем вызовам
    protected $_prefix;

    public  static function instance() {
        if (self::$instance === null) {
           self::$instance = new self();
        }
        return self::$instance;
    }
    public function  setPrefix($prefix) {
        $this->_prefix = $prefix;
    }

    public function set($key, $value) {
        $key = $this->_prefix . $key;
        parent::set($key, $value);
    }

    public function get($key) {
        $key = $this->_prefix . $key;
        parent::get($key);
    }

    public function  is($key) {
        $key = $this->_prefix . $key;
        parent::is($key);
    }

    public function  __get($key) {
        $key = $this->_prefix . $key;
        parent::__get($key);
    }

    public function  del($key) {
        $key = $this->_prefix . $key;
        parent::del($key);
    }
}
?>
