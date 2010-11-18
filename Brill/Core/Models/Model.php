<?php
/*
 * Model
 *
 * Класс родитель для вех моделей
 */

/*
 * save()
 * getCols($aCols ext
 * getFields() ext
 * getObject($pk)
 * getObjects($field)
 * delete($pk)
 *
 * update()
 * add()
 *
 *
 */


abstract class Model {

    //название таблицы
    protected $tbl_name;

    //поля - должны быть уникальны
    protected $fields = array();

    //значения
    private $values = null;
    /*
     * есть ли первичный ключ. им становится первое поле
     * если он включен, то его создает только БД и менять его нельзя
     * если отключен - то запрещены все операции обновления и удаления
     */
    protected $isPk = true;

    // контрольная сумма объекта, после заполнения данными из БД
    private $checkSum = null;

    /**
     * Получение значений $this->values
     * @param string $field
     * @return
     */
    function  __get($field) {
        if (array_key_exists($field, $this->values)) {
             return $this->values[$field];
        } else {
            Log::warning('Не возможно получить свойство. / ' . get_class($this) .'->' . $field . ' - не определено');
        }

    }

    /**
     * Задание значений $this->values
     * @param string $field
     * @param object $value
     */
    function  __set($field, $value) {
        if (in_array($field, $this->fields)) {
            if ($this->isPk && $field == $this->fields[0]) {
                Log::warning('Нельзя изменять первичный ключ '.$field);
            }
            //экранируем все от греха по дельше
            $this->values[$field] = addslashes($value);
        } else {
            Log::warning('Не возможно задать свойство. / ' . get_class($this) .'->' . $field . ' - не определено');
        }
    }



    final function  __construct($pk = null) {
        if (isset($pk)) {
            return $this->getObject($pk);
        } else {
            return $this;
        }
    }

    function  __destruct() {

    }


    /**
     * Возвращает объект, полученный по первому полю
     * @param string $valPk значение ключа
     * @return class
     */
    public function getObject($valPk) {
        // подумать, как от этого избавиться
        $valPk = addslashes($valPk);
        $values = DBExt::getOneRow($this->tbl_name, $this->fields[0], $valPk);
        if (isset($values)) {
            $this->initData($values);
        } else {
            Log::warning('Не найдент объект ' . get_class($this) . ' с ключом ' . $this->fields[0] . '=' . $valPk);
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
            $values = DBExt::getRows($colClass->tbl_name, $field, $val);
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
     * удаляет объект, полученный по первому полю
     * @param string $valPk значение ключа
     * @return class
     */
    public function delete() {
      DBExt::deleteOneRow($this->tbl_name, $this->fields[0], $this->values[$this->fields[0]]);
      unset($this->values[$this->fields[0]]);
      $this->calcCheckSum();
    }

    /**
     * Сбрасывает у объекта его idшник
     * следующий save добавит в базу новую запись
     */
    public function reset() {
        if ($this->isPk) {
            $this->values[$this->fields[0]] = null;
        }
    }
    /**
     * Получить массив значений таблицы объекта
     */
    function getArray() {
        return DBExt::getAllRows($this->tbl_name);
    }

    /**
     * Вставляет данные в базу
     */
    protected function add () {
        $this->calcCheckSum();
        $this->values[$this->fields[0]] = DBExt::insertOne($this->tbl_name, $this->values);

    }

    /**
     * Сохрание объекта
     */
    //сделать какойто идентификатор id каждому объекту модели
    public function save() {
        if ($this->isPk) {
            if (isset($this->values[$this->fields[0]])) {
                $this->update();
            } else {
                $this->add();
            }
        } else {
            Log::warning('Объекты, которые не поддеживают первичный ключ - изменять нельзя');
        }
        return $this;
    }

    /**
     * Обновление объекта
     */
    protected function update () {
        //изменения вносим в базу только если изменилась контрольная сумма
        if ($this->checkSum != $this->calcCheckSum()) {
            $newValues = $this->values;
            $valPk = array_shift($newValues);
            $result = DBExt::updateOne($this->tbl_name, $newValues, $this->fields[0], $valPk);
        }
     }

    /**
     * инициализирует данные полученные из БД
     * а также создает контрольную сумму значений
     * @param array $values
     */
    protected function initData($values) {
        //заполнение полей значениями
        foreach ($this->fields as $fld) {
            if (isset($values[$fld])) {
               $this->values[$fld] = $values[$fld];
            } else {
                Log::warning('Не найдено соответствие в базе полю ' .$fld );
            }
        }
        $this->calcCheckSum();
    }

    protected function calcCheckSum() {
       $this->checkSum = crc32(implode($this->values));
       return $this->checkSum;
    }
    public function getValues() {
        return $this->values;
    }

    public function getFields() {
        return $this->fields;
    }
}