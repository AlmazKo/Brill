<?php
/**
 * Daemon
 * Родительский класс всех демонов, пауков, ботов и прочей нечисти
 */
  class Daemon implements CliInterface{
    /* ПЛАНИРУЕМЫЕ
     * инициализация
     * просмотр списка демонов
     * просмотр списка запущенных демонов
     * запуск демона
     * проверка что запуще из консоли
     * получение параметров запуска
     * остановка демона
     * получить статус демона
     * получить информацию о демоне ( сколько щас жрет памяти, процессора, сколько уже работает)
     * apaчевский аналог start|stop|restart|reload|force-reload|start-htcacheclean|stop-htcacheclean|status
     *
     */
    //аргумент для получения имени демона
    const KEY_NAME_DAEMON = 'n';
    protected
    // ссылка на конфигурацию модуля
        $_module,
        $_params;
    protected static $_cliParams = array('n' => 'Daemon\'s name.', 'h' => 'View help');
    
    public function  __construct() {}
    public function start() {}
    public function stop() {}
    
    public function restart() {}
    public function isRun() {}
    public function getStatus() {}
    protected function _setStatus() {}
    protected function _error() {}

    public function setModule($module) {
        $this->_module = $module;
    }

    public function setParams($params) {
        $this->_params = $params;
    }

    public function getParams($params) {
        return $this->_params;
    }
    
    /**
     * Получить массив принимаемых демоном параметров
     */
    public function getACliParams() {
  
        
    }
    
    public function getHelp() {
        return self:: $_cliParams;
    }
    
    /**
     * Получение строки параметров для отображения в консоли
     * @param type $encode
     * @return string 
     */
    public function getStringParams($prefix = '-', $encode = 'utf8') {
       $params = $this->getACliParams();
       $string = "Parameters:\n";
       foreach ($params as $key => $value) {
           $string .= str_pad($prefix . $key, 8, " ", STR_PAD_LEFT) . "\t" . $value . "\n";
       } 
       return $string;
    }
    
    /**
     * Сколько копий дмено можно запусать одновременно
     */
    public function maxCountRun(){
        return 4;
    }
    
    public function option_h() {
        
        
    }
    
    public static function option_null() {
        return "Пропущены операнды\nПопробуйте с опцией ` -h`, чтобы получить дополнительную информацию";
    }
}