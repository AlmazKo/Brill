<?php
/* 
 * Model
 *
 * ����� �������� ��� ��� �������
 */

require_once 'DBExt.php';

abstract class Model {
    protected $tbl_name;
    private $values = null;
    protected $fields = array();

    final function  __construct() {
     /* �����. ����� �������� � update �� ����������� ��� ����, � ������ �������� ��������
      *    if ($this->values === null) {
            $this->values = array();
            //���������� ����� ���������� ����������
          foreach ($this->fields as $field => $value) {
                $this->values[$field] = $value;
            }

        }
      *  */
    }

    /**
     * �������� �������� �� ����
     * used for only unique fields
     */
    function getObject($field, $val) {
        // ��������, ��� �� ����� ����������
        $val = addslashes($val);
        $values = DBExt::getByField($this->tbl_name, $field, $val); 
        if (empty($values)) return false;
        $this->values = array();
        //���������� ����� ����������
        foreach ($this->fields as $fld) {
            $this->values[$fld] = $values[$fld];
        }
        return true;
    }

    /**
     * �������� ������ ��������
     */
    function getArrayObjects() {
        return DBExt::select($this->tbl_name);
    }

    /**
     * ��������� ������ � ����
     */
    function add () {
        return  DBExt::insert($this->tbl_name, $this->values);

    }
    function  __get($field) {
        if (array_key_exists($field, $this->values)) {
             return $this->values[$field];
        } else {
            Log::warning('�� �������� �������� ��������. / ' . get_class($this) .'->' . $field . ' - �� ����������');
        }
       
    }

    function  __set($field, $value) {
        if (in_array($field, $this->fields)) {
            //���������� ��� �� ����� �� ������
            $this->values[$field] = addslashes($value);
        } else {
            Log::warning('�� �������� ������ ��������. / ' . get_class($this) .'->' . $field . ' - �� ����������');
        }
        
    }
    /**
     *
     * @param string $field �������� ������ ���������� ����
     */

    //������� ������� ������������� id ������� ������� ������
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


