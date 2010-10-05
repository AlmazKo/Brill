<?php
/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of List
 *
 * @author Alexander
 */
require_once 'ISorting.php';

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
    private $multi = false;

    public function setSelected ($array = array()) {
        $this->selected = $array;
    }
    function __construct(array $data) {
        $list = array();
         foreach ($data as $value) {
            $list[$value[0]] = $value[1];
        }
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

    public function BuildHtmlSelect ($html = "<select>") {
        foreach ($this->values as $key => $value) {
            $selected = "";
            if (in_array($key, $this->selected)) {
                $selected = "selected=selected";
            }
            $html .= '<option ' . $selected . ' value="' . $key . '">' . $value . '</option>';
        }
        $html .= '</select>';
        return $html;
    }
}