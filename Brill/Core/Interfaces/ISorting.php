<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 *
 * @author Alexander
 */
interface ISorting {
    /**
     *  Сортирует данные по полю $field и направлению $direction
     */
    function sort($field, $direction = null);
}

