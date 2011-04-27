<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of CliInterface
 *
 * @author almazko
 */
interface CliInterface {
    const CLI_ARG_HELP = 'h';
    
    public function getCliString();
    public function getCliArray();
    public function getCliParams();
    public function getNeedParams();
}

