<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Cli
 *
 * @author almazko
 */
final class Cli {
    const ARG_HELP = 'h';
   private static $_args;
   
   
  
   public function getStringForHelp($args, $prefix = '-', $encode = 'utf8') {
       $string = "Use the following arguments:\n";
       foreach ($args as $key => $value) {
           $string .= str_pad($prefix . $key, 8, " ", STR_PAD_LEFT) . "\t" . $value . "\n";
       } 
       return $string;
    }
   public static function setArgs (array $args) {
       if (is_null(self::$_args)) {
           foreach ($args as $key => $value) {
              if (preg_match('/(?:\-([^=]))(?:=(.*))?/i', $value, $matches)) {
                  self::$_args[$matches[1]] = isset($matches[2]) ? $matches[2] : null;
              }
           }
           return true;
       } else {
           return false;
       }
   }
   
   public static function hasArg($key) {
       if (array_key_exists($key, self::$_args)) {
           return true;
       } else {
           return false;
       }
   }
   
   public static function getArg($key) {
       if (self::hasArg($key)) {
           return self::$_args[$key];
       } else {
           return false;
       }
   }
   
}
