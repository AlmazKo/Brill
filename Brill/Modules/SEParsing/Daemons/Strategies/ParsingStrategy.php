<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 *
 * @author almaz
 */
interface ParsingStrategy {
   # const URL_SEARCH = '';
    public function parse(Keyword $keyword, $countKeywords = 10);
}

