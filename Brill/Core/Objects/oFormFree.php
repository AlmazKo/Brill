<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Класс Формы
 * можно что угодно вставлять в форму
 * @author almazKo
 */
class oFormFree extends oForm{

    function buildFree($id, $content) {
            $html = '';
            $html .= '<form id = "' . $id . '" enctype="multipart/form-data" method="post" action="' . $this->url .'">';
            $html .= $content;
            $html .='<input type="submit"></form>';
            return $html;
    }

    public function __construct($url = null) {
        parent::__construct(array(), $url);

    }
}

