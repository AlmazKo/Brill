<?php
/*
 * Model
 *
 * Класс родитель для вех моделей
 */

abstract class Model {
    //значения
    private
        //значения
        $_values = null,
        // контрольная сумма объекта, после заполнения данными из БД
        $_checkSum = null;

    protected
        //название таблицы
        $_tbl_name,
        //поля - должны быть уникальны
        $_fields = array(),
        /*
         * есть ли первичный ключ. им становится первое поле
         * если он включен, то его создает только БД и менять его нельзя
         * если отключен - то запрещены все операции обновления и удаления
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
            Log::warning('Не возможно получить свойство. / ' . get_class($this) .'->' . $field . ' - не определено');
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
        if (isset($pk)) {
            return $this->getObject($pk);
        } else {
            return $this;
        }
    }

    function  __destruct() {}

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
        } else {
            return false;
            #Log::warning('Не найдент объект ' . get_class($this) . ' с ключом ' . $this->_fields[0] . '=' . $valPk);
        }
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
     *
     * Возвращает массив объектов $class
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
    public static function getObjectFromSql ($class, $sql, $lnk = null) {
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
    public function fillObjectFromSql ($sql, $lnk = null) {
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
     * удаляет объект, полученный по первому полю
     * @param string $valPk значение ключа
     * @return class
     */
    public function delete() {
      DBExt::deleteOneRow($this->_tblName, $this->_fields[0], $this->_values[$this->_fields[0]]);
      unset($this->_values[$this->_fields[0]]);
      $this->calcCheckSum();
    }

    /**
     * Сбрасывает у объекта его idшник
     * следующий save добавит в базу новую запись
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
    protected function add () {
        $this->_calcCheckSum();
        $this->_values[$this->_fields[0]] = DBExt::insertOne($this->_tblName, $this->_values);

    }

    /**
     * Сохрание объекта
     */
    //сделать какойто идентификатор id каждому объекту модели
    public function save() {
        if ($this->_isPk) {
            if (isset($this->_values[$this->_fields[0]])) {
                $this->update();
            } else {
                $this->add();
            }
        } else {
            $this->add();
        }
        return $this;
    }

    /**
     * Обновление объекта
     */
    protected function update () {
        //изменения вносим в базу только если изменилась контрольная сумма
        if ($this->_checkSum != $this->calcCheckSum()) {
            $newValues = $this->_values;
            $valPk = array_shift($newValues);
            $result = DBExt::updateOne($this->_tblName, $newValues, $this->_fields[0], $valPk);
        }
     }

     public function saveInBuffer(){
         if (isset($this->_values[$this->_fields[0]])) {
            $this->update();
         } else {
            if (!$this->_buffer) {
                $this->_buffer = 'INSERT INTO (' . DBExt::parseFields($this->_fields) . ') VALUES ';
            } else {
                 $this->_buffer .= ",\n";
            }
            $this->_buffer .= '(' . DBExt::parseValues($this->_values) . ')';
            
         }
     }

     public function executeBuffer() {
         DB::query($this->_buffer);
     }

     public function cleanBuffer() {
         $this->_buffer = null;
     }
    /**
     * инициализирует данные полученные из БД
     * а также создает контрольную сумму значений
     * @param array $values
     */
    protected function initData($values) {
        //заполнение полей значениями
        foreach ($this->_fields as $fld) {
            if (isset($values[$fld])) {
               $this->_values[$fld] = $values[$fld];
            } else {
                Log::warning('Не найдено соответствие в базе полю ' .$fld );
            }
        }
        $this->calcCheckSum();
    }

    protected function calcCheckSum() {

       return $this->_checkSum = crc32(implode($this->_values));
    }
    public function getValues() {
        return $this->_values;
    }

    public function getFields() {
        return $this->_fields;
    }
}