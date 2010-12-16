<?php

/**
 * Description of List
 *
 * @author Alexander
 */

class oList implements ISorting {
    private $page = 1;
    private $search = '';
    private $sort = null;
    private $directSort = 'ASC';
    private $altWhere = '';
    private $name = null;
    private $headers = array();
    private $htmlHeaders = array();
    private $values = array();
    private $selected = array();
    private $_multi = false;

    public function setMulti($multi = true) {
        $this->_multi = $multi;
    }
    public function setSelected ($array = array()) {
        $this->selected = $array;
    }

    /**
     *
     * @return array
     */
    public function getSelected() {
        return $this->selected;
    }
    function __construct(array $data, array $default = array()) {
        $list = array();
        foreach ($data as $key => $value) {
            $list[$key] = $value;
        }
        if ($default) {
            $list[key($default)] = current($default);
        }
        asort($list);
        $this->values = $list;
    }
    public function getArray () {
        return $this->values;
    }
    function  Sort($field, $direction = null) {
        if ($field === 'key' || $field === 0){
           if(!$direction || $direction === 'ASC') {
                sort($this->values);
            } else {
                asort($this->values);
            }
        } else if($field === 'value' || $field === 1) {
            if(!$direction || $direction === 'ASC') {
                ksort($this->values);
            } else {
                krsort($this->values);
            }
        }
    }
    /**
     * включенг ли мультиселект
     * @return bool
     */
    public function isMulti() {
        return $this->_multi;
    }

    /**
     * Задаем массив значений значения
     * @param array $value
     */
    public function setChecking(array $value) {
        $this->selected = $value;
//        if (count($this->selected) > 1) {
//            $this->_multi = true;
//        }
    }
    /**
     * Возвращает отмеченные значения
     * @return <type>
     */
    public function getChecking() {
        return $this->selected;
    }
    public function buildHtmlSelect ($idCss = false, $classCss = false) {

        $html = '<select ' . ($idCss ? 'name="'.$idCss.($this->_multi ? '[]' : '').'" id="'.$idCss.'" ' : '').($classCss ? 'class="'.$classCss.'" ' : '').($this->_multi ? ' multiple="multiple" rows="4" ': '').'>';
        foreach ($this->values as $key => $value) {
            $selected = "";
            if (in_array($key, $this->selected)) {
                $selected = 'selected="selected"';
            }
            $html .= '<option ' . $selected . ' value="' . $key . '">' . $value . '</option>';
        }
        $html .= '</select>';
        return $html;
    }


    /**
     * Заполнение селекта данными.
     * Старые данные стираются
     * @param mixed $data
     */
    public function fill($data) {
        $this->selected = array();
        if ($this->isMulti()) {
            if (!is_array($data)) {
               $data = array($data);
            }
            foreach ($data as $value) {
                if (array_key_exists($value, $this->values)) {
                    $this->selected[] = $value;
                }
            }
        } else {
            if (is_array($data['name'])) {
                $data = (string) current($data);
            } else {
                $data = (string) $data;
            }
            if (array_key_exists($data, $this->values)) {
                $this->selected[] = $data;
            }
        }
       //      Log::dump($this->selected); echo '000';
    }
}