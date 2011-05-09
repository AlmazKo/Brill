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
    function parsing(Keyword $keyword, $countKeywords);
}

