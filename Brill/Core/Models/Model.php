<?php
/* 
 * Model
 *
 *  ласс родитель дл€ вех моделей
 */

require_once 'DBExt.php';

abstract class Model {
    protected $tbl_name;
    private $values = null;
    protected $fields = array();

    final function  __construct() {
     /* убрал. чтобы например в update не заполн€лись все пол€, а только заданные значени€
      *    if ($this->values === null) {
            $this->values = array();
            //заполнение полей дефолтными значени€ми
          foreach ($this->fields as $field => $value) {
                $this->values[$field] = $value;
            }

        }
      *  */
    }

    /**
     * ѕолучает значени€ по полю
     * used for only unique fields
     */
    function getObject($field, $val) {
        // подумать, как от этого избавитьс€
        $val = addslashes($val);
        $values = DBExt::getByField($this->tbl_name, $field, $val); 
        if (empty($values)) return false;
        $this->values = array();
        //заполнение полей значени€ми
        foreach ($this->fields as $fld) {
            $this->values[$fld] = $values[$fld];
        }
        return true;
    }

    /**
     * ѕолучить массив объектов
     */
    function getArrayObjects() {
        return DBExt::select($this->tbl_name);
    }

    /**
     * ¬ставл€ет данные в базу
     */
    function add () {
        return  DBExt::insert($this->tbl_name, $this->values);

    }
    function  __get($field) {
        if (array_key_exists($field, $this->values)) {
             return $this->values[$field];
        } else {
            Log::warning('Ќе возможно получить свойство. / ' . get_class($this) .'->' . $field . ' - не определено');
        }
       
    }

    function  __set($field, $value) {
        if (in_array($field, $this->fields)) {
            //экранируем все от греха по дельше
            $this->values[$field] = addslashes($value);
        } else {
            Log::warning('Ќе возможно задать свойство. / ' . get_class($this) .'->' . $field . ' - не определено');
        }
        
    }
    /**
     *
     * @param string $field обновить только конкретное поле
     */

    //сделать какойто идентификатор id каждому объекту модели
    function save($field = null) {
        if ($field) {
            $value = $this->__get($field);
            $result = DBExt::update($this->tbl_name, array($field => $value), 'id', $this->id);
        } else {
            $result = DBExt::update($this->tbl_name, $this->values, 'id', $this->id);
        }
        
    }
    function update () {}
    
    function delete() {}

}


