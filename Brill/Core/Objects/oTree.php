<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of oTree
 *
 * @author Alexander
 */
class oTree {
    private $tree;
    public function __construct($array) {
        $this->tree = $array;
    }

    public function buildHtml ($array = null) {
        $html .= '<ul>';
        if (!$array) {
            $array = $this->tree;
        }
        foreach ($array as $key => $value) {

            if (is_array($value)) {
                $this->buildHtml($value);
            }
        }
        $html .= '</ul>';
    }
}
