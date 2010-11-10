<?php
/**
 * Таймер
 * Задает точки, и считает время между ними
 */
class Timer {
    private $_datepoint = null;
    private $_alltime = 0;
    private $_count = 0;

    function  __destructor() {
        if ($this->datepoint !== null) { echo 'BAD вызван деструктор таймера';
            $this->endPoint();
        }
    }

    /**
     *  Создать точку отсчета (поинт)
     * @return boolean
     */
    public function addPoint() {
        if ($this->_datepoint === null) {
            $this->_datepoint = microtime(true);
            return true;
        } else {
            Log::notice('Для таймера  уже создана начальная точка');
            return;
        }
    }

    /**
     *  Возвращает разницу с последним поинтом
     * @return float
     */
    public  function endPoint(){
        if ($this->_datepoint === null) {
            Log::warning('Для таймера  не была создана начальная точка');
            return 0;
        } else {
            $current_runtime = microtime(true) - $this->_datepoint;
            $this->_alltime += $current_runtime;
            $this->_datepoint = null;
            $this->_count++;
            return $current_runtime;
        }
    }
    /**
     * Получает все время работы таймера
     */
    public function getAllTime() {
        if ($this->_datepoint) {
            $this->endPoint();
        }
        return $this->_alltime;
    }
}