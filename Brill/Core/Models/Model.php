<?php
/*
 * Model родитель всех объектов-моделей. содержит всю логику работы с ними
 *
 * @author AlmazKo <a.s.suslov@gmail.com>
 */

abstract class Model {
    private
        //значения
        $_values = null,
        // контрольная сумма объекта, после заполнения данными из БД
        $_checkSum = null,
        // Имя класса
        $_nameClass;

    protected
        //коннект
        $_lnk,
        //название БД
        $_db,
        //название таблицы
        $_tbl_name,
        //поля - должны быть уникальны
        $_fields = array(),
        /*
         * Используется ли первичный ключ, если да, то им становится первое поле.
         * Поле в таблице должно быть уникальным
         * Если он включен, то его может создать только БД и менять его нельзя.
         * Если отключен - то запрещены все операции обновления и удаления.
         */
        $_isPk = true,
        // накапливаемый буффер для вставки
        $_buffer;

    /**
     * Получение значений $this->_values
     * @param string $field
     * @return
     */
    function  __get($field) {
        if (array_key_exists($field, $this->_values)) {
             return $this->_values[$field];
        } else {
            Log::warning('Не возможно получить свойство. / ' . get_class($this) .'->' . $field . ', т.к. оно не определено');
        }
    }

    /**
     * Задание значений $this->_values
     * @param string $field
     * @param object $value
     */
    function  __set($field, $value) {
        if (in_array($field, $this->_fields)) {
            if ($this->_isPk && $field == $this->_fields[0]) {
                Log::warning(get_class($this) . ' Нельзя изменять первичный ключ '.$field);
            }
            //экранируем все от греха по дельше
            $this->_values[$field] = addslashes($value);
        } else {
            Log::warning('Не возможно задать свойство. / ' . get_class($this) .'->' . $field . ' - не определено');
        }
    }

    /**
     * Получить объект. Можно сразу его заполнить, если передать значение первичного ключа
     * @param mixed $pk
     * @return Model
     */
    final function  __construct($pk = null) {
        $this->_nameClass = get_class($this);
        if (isset($pk)) {
            return $this->getObject($pk);
        } else {
            return $this;
        }
    }

    function  __destruct() {}
    private function __clone() {}

    /**
     * Возвращает объект, полученный по первому полю
     * @param string $valPk значение ключа
     * @return class
     */
    public function getObject($valPk) {
        // подумать, как от этого избавиться
        $valPk = addslashes($valPk);
        $values = DBExt::getOneRow($this->_tblName, $this->_fields[0], $valPk);
        if (isset($values)) {
            $this->initData($values);
            return $this;
        } else {
            return false;
            #Log::warning('Не найдент объект ' . get_class($this) . ' с ключом ' . $this->_fields[0] . '=' . $valPk);
        }
    }

    /**
     * Возвращает объект, как массив
     * @return array
     */
    public function toArray() {
        return $this->getValues();
    }

    /**
     * Возвращает объект, полученный по уникальному полю
     * @param string $valPk значение ключа
     * @return class
     */
    public function getObjectField($field, $value) {
        if (in_array($field, $this->_fields)) {
            $values = DBExt::getOneRow($this->_tblName, $field, $value);
            if (isset($values)) {
                $this->initData($values);
            } else {
                return false;
                #Log::warning('Не найдент объект ' . get_class($this) . ' с ключом ' . $this->_fields[0] . '=' . $valPk);
            }
        } else {
            Log::warning('У объекта ' . get_class($this) . 'нет свойства ' . $field);
        }
    }

    /**
     * Возвращает массив объектов $class
     *
     * @param string $class название класса
     * @param string $field поле по какому ищем
     * @param string&int&array $val значение поля
     * @return array
     * TODO пожохе не нужна
     */
    public static function getObjects($class, $field = null, $val = null) {
        $colClass = new $class();
        if (is_subclass_of($colClass, 'Model')) {
            $values = DBExt::getRows($colClass->_tblName, $field, $val);
            $aObjects = array();
            foreach($values as $row) {
                $obj = new $class();
                $this->initData($row);
                $aObjects[] = $obj;
            }
            return $aObjects;
        } else {
            Log::warning('Для получения коллекции объектов ' . $class . ' - должен быть унаследован от Model');
        }
    }

    /**
     * Получить массив объектов из результата запроса
     * @param string $class
     * @param string $sql
     * @param recource $lnk
     * @return class
     */
    public static function getObjectsFromSql($class, $sql, $lnk = null) {
        $colClass = new $class();
        if (is_subclass_of($colClass, 'Model')) {
            $values = DBExt::selectToArray($sql);
            $aObjects = array();
            foreach ($values as $row) {
                $obj = new $class();
                //TODO менять tbl
                $obj->initData($row);
                $aObjects[] = $obj;
            }
            return $aObjects;
        }  else {
            Log::warning('Для получения коллекции объектов ' . $class . ' - должен быть унаследован от Model');
        }
    }

    /**
     * Получить объект из результата запроса
     * @param string $class
     * @param sring $sql
     * @param recourxce $lnk
     * @return class
     */
    public static function getObjectFromSql($class, $sql, $lnk = null) {
        $model = new $class();
        if (is_subclass_of($model, 'Model')) {
            $row = DBExt::getOneRowSql($sql);
            if ($row) {
                $model->initData($row);
                return $model;
            } else {
                return false;
            }
        }  else {
            Log::warning('Для получения объекта ' . $class . ' - должен быть унаследован от Model');
        }
    }

    /**
     * Пытается заполнить объект из результата sql-запроса
     * @param string $sql
     * @param resource $lnk
     * @return bool
     */
    public function fillObjectFromSql($sql, $lnk = null) {
        $row = DBExt::getOneRowSql($sql, $lnk);
        if ($row) {
            // по идее у модели должен меняться _table
            $this->initData($row);
            return true;
        } else {
            return false;
        }
    }

    /**
     * Пытается заполнить объект из массива.
     * Т.к. данные не известно откуда, то происходит запись только тех полей, которые найдутся в модели.
     * Первичный ключ не изменяется.
     *
     * @param array $values
     * @return bool
     */
    public function fillObjectFromArray(array $values) {
        foreach ($this->_fields as $fld) {
            if ($fld != $this->_fields[0] && isset($values[$fld])) {
               $this->_values[$fld] = $values[$fld];
            }
        }
    }

    /**
     * удаляет объект, полученный по первому полю
     * @param string $valPk значение ключа
     * @return class
     */
    public function delete() {
      DBExt::deleteOneRow($this->_tblName, $this->_fields[0], $this->_values[$this->_fields[0]]);
      unset($this->_values[$this->_fields[0]]);
      $this->_calcCheckSum();
    }

    /**
     * Сбрасывает у объекта его idшник.
     * Следующий save() добавит в базу новую запись
     */
    public function reset() {
        if ($this->_isPk) {
            $this->_values[$this->_fields[0]] = null;
        }
    }

    /**
     * Получить массив значений таблицы объекта
     */
    function getArray() {
        return DBExt::getAllRows($this->_tblName);
    }

    /**
     * Вставляет данные в базу
     */
    protected function _add () {
        $this->_values[$this->_fields[0]] = DBExt::insertOne($this->_tblName, $this->_values);
        $this->_calcCheckSum();
    }

    /**
     * Сохрание объекта
     */
    //сделать какойто идентификатор id каждому объекту модели
    public function save() {
        if ($this->_isPk) {
            if (isset($this->_values[$this->_fields[0]])) {
                $this->_update();
            } else {
                $this->_add();
            }
        } else {
            $this->_add();
        }
        return $this;
    }

    /**
     * Обновление объекта
     */
    protected function _update () {
        //изменения вносим в базу только если изменилась контрольная сумма
        if ($this->_checkSum != $this->_calcCheckSum()) {
            $newValues = $this->_values;
            $valPk = array_shift($newValues);
            if (DBExt::updateOne($this->_tblName, $newValues, $this->_fields[0], $valPk)) {
                //было что-то измено в базе, создаем новую чексумму
                $this->_calcCheckSum();
            }
        }
     }

     /**
      * Добавить значения объекта в буффер множественной вставки
      * если у объекта есть первичный ключ - выполнится простой апдейт
      */
     public function saveInBuffer(){
         if ($this->_isPk && isset($this->_values[$this->_fields[0]])) {
            $this->_update();
         } else {
            if (!$this->_buffer) {
                $this->_buffer = 'INSERT INTO (' . DBExt::parseFields($this->_fields) . ') VALUES ';
            } else {
                 $this->_buffer .= ",\n";
            }
            $this->_buffer .= '(' . DBExt::parseValues($this->_values) . ')';

         }
     }

     /**
      * Выполнить множественную вставку
      * и очистить буффер
      */
     public function executeBuffer() {
         if($this->_buffer) {
            DB::query($this->_buffer);
            $this->cleanBuffer();
         }
     }

     /**
      * Очистить буффер множественной вставки
      */
     public function cleanBuffer() {
         $this->_buffer = null;
     }
    /**
     * Заполняет значения данными из БД,
     * а также создает контрольную сумму значений
     * @param array $values
     */
    protected function initData($values) {
        foreach ($this->_fields as $fld) {
            if (isset($values[$fld])) {
               $this->_values[$fld] = $values[$fld];
            } else {
                Log::warning('Не найдено соответствие в базе полю ' .$fld );
            }
        }
        $this->_calcCheckSum();
    }

    /**
     * Создает чек сумму объекта.
     * Нужна, чтобы избежать лишних апдейтов
     * @return int сгенерированная новая чек-сумма
     */
    protected function _calcCheckSum() {
       return $this->_checkSum = crc32(implode($this->_values));
    }

    /**
     * Возвращает массив значений объекта
     * @return array
     */
    public function getValues() {
        return $this->_values;
    }

    /**
     * Получить массив полей объекта
     * @return array
     */
    public function getFields() {
        return $this->_fields;
    }
}