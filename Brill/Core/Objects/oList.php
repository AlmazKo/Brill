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
class oList {
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
    function __construct(array $list) {
        $this->values = $list;
    }
    public function getArray () {
        return $this->values;
    }

    public function BuildHtmlSelect ($html = "<select>") {
        foreach ($this->values as $key => $value) {
            $selected = "";
            if (in_array($key, $this->selected)) {
                $selected = "selected=selected";
            }
            $html .= '<option ' . $selected . ' value="' . $value[0] . '">' . $value[1] . '</option>';
        }
        $html .= '</select>';
        return $html;
    }
}
