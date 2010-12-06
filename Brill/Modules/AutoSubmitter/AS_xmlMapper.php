<?php

/**
 * у родителя описано чтение файлов, валидация, сохрание SimpleXMl объекта
 * получение атрубитов и другие общие методы
 *
 */
class xmlParser{
    protected  $filexml = NULL;

    function  __construct() {
    }
    function xmlOpen($filexml){
        $this->filexml = simplexml_load_file($filexml);
    }
    function xmlClose(){
        $this->filexml = NULL;
    }
}


class AS_xmlMapper extends xmlParser{
    protected $RULES = array();
    protected $obj_UserDataProject = NULL;
    protected $obj_AS_Site = NULL;
    protected $delimeter = '';


    function  __construct(UserDataProject $obj_UserDataProject, AS_Site $obj_AS_Site) {
        $this->obj_UserDataProject = $obj_UserDataProject;
        $this->obj_AS_Site = $obj_AS_Site;
        $this->xmlOpen($this->obj_AS_Site->rulefilexml);
        if (isset($this->filexml->rule)){
            $number = 1;
            foreach ($this->filexml->rule as $value) {
                $this->RULES[$number] = AS_xmlMapper::obhod_rule_tags($value, 'rule');
                $number++;
            }
         }else{
             //правил нет :(
         }
         $this->xmlClose();
    }

    static function obhod_rule_tags($xmlrule = null, $name = ''){
            $i = 1;
            $arr = array();
            if (is_object($xmlrule)){
                foreach ($xmlrule->attributes() as $property => $value) {
                    $arr['parameters'][$property] = (string)$value;
                }
                foreach ($xmlrule as $object => $value){
                  if (key_exists($object, $arr)){
                      $arr[$object . '_' . $i] = AS_xmlMapper::obhod_rule_tags($value, (string)$object);
                  }else{
                      $arr[$object] = AS_xmlMapper::obhod_rule_tags($value, (string)$object);
                  }
                  $i++;
                }
            }
        return $arr;
    }

    /*
     * возвращаем полный массив подобный XML
     */
    function getRule($id_rule){
        if (key_exists($id_rule, $this->RULES)){
             return $this->RULES[$id_rule];
        }else{
            return false;
        }
    }

    /*
     * возвращает массив правил
     *   [1] => Array
     *   (
     *       [url] => http://www.press-release.ru/
     *       [xxxx] => adfasdf
     *   )
     *
     *   [2] => Array
     *   (
     *       [url] => http://www.press-release.ru/add/
     *   )
     *
     *   [3] => Array
     *   (
     *       [url] => http://www.press-release.ru/
     *   )
     */
    function listRules(){
        if (count($this->RULES)){
            $arr = array();
            foreach ($this->RULES as $key => $value) {
                foreach ($value['parameters'] as $key1 => $value1) {
                    $arr[$key][$key1] = $value1;
                }
            }
            return $arr;
        }else{
            return false;
        }
    }

    /*
     * возвращает true или false
     */
    function isAjaxRule($id_rule){
        if ($this->RULES[$id_rule]['action']['parameters']['isajax'] == 'true'){
             return true;
        }else{
            return false;
        }
    }

    /*
     * возвращает двумерный массив параметр = значение
     * $array_data - это то что мы получили от пользователя
     */
    function getGetRule($id_rule){
        if (isset($this->RULES[$id_rule]['parameters_for_get'])){
            $arr = array();
            //тут мы смотрим что заполнено через XML карту
            foreach ($this->RULES[$id_rule]['parameters_for_get'] as $key => $value) {
                if (!empty($value['userdataproject'])){
                    $result = '';
                    foreach ($value as $key1 => $value1) {
                        if (substr_count($key1,'userdataproject')){
                            $method =  'Get' . $value1['parameters']['name'];
                            $result .= $this->delimeter . $this->obj_UserDataProject->$method();
                        }
                    }
                    $arr[$value['parameters']['htmlname']] = $result;
                }
            }

           //тут мы смотрим что заполнено в процессе работы


            return $arr;
        }else{
            return false;
        }
    }

    /*
     * возвращает двумерный массив параметр = значение
     */
    function getPostRule($id_rule){
        if (isset($this->RULES[$id_rule]['parameters_for_post'])){
            $arr = array();
            //тут мы смотрим что заполнено через XML карту
            foreach ($this->RULES[$id_rule]['parameters_for_post'] as $key => $value) {
                if (!empty($value['userdataproject'])){
                    $result = '';
                    foreach ($value as $key1 => $value1) {
                        if (substr_count($key1,'userdataproject')){
                            $method =  'Get' . $value1['parameters']['name'];
                            $result .= $this->delimeter . $this->obj_UserDataProject->$method();
                        }
                    }
                    $arr[$value['parameters']['htmlname']] = $result;
                };
            }

           //тут мы смотрим что заполнено в процессе работы


            return $arr;
        }else{
            return false;
        }
    }

    /*
     * возвращает в XML подобном виде
     */
    function getDynamicFieldsRule($id_rule){
        $arr = array();

        //тут мы смотрим что заполнено через XML карту
        if (count($this->RULES[$id_rule]['parameters_for_get'])){
            $i = 1;
            foreach ($this->RULES[$id_rule]['parameters_for_get'] as $key => $value) {
                if (empty($value['userdataproject'])){
                    $arr['parameters_for_get'][$i] = $value;
                    $i++;
                }
            }
        }else{
        }
        if (count($this->RULES[$id_rule]['parameters_for_post'])){
            $i = 1;
            foreach ($this->RULES[$id_rule]['parameters_for_post'] as $key => $value) {
                if (empty($value['userdataproject'])){
                    $arr['parameters_for_post'][$i] = $value;
                    $i++;
                };
            }
        }else{
        }

        //тут мы смотрим что заполнено в процессе работы, т.к. поля могли уже заполниться
/*
        if (count($arr['parameters_for_get'])){
            foreach ($arr['parameters_for_get'] as $key => $value) {
                foreach ($value as $key1 => $value1) {
                    if (key_exists($value1['parameters']['htmlname'], $this->obj_UserDataProject->GetData())){
                        unset ($value);//он есть в заполненых, поэтому удаляем из пустых
                        echo '1';
                    }
                }
            }
        }
        if (!count($arr['parameters_for_get'])){
            unset($arr['parameters_for_get']);
        }
        if (count($arr['parameters_for_post'])){
            foreach ($arr['parameters_for_post'] as $key => $value) {
                foreach ($value as $key1 => $value1) {
                    if (key_exists($value1['parameters']['htmlname'], $this->obj_UserDataProject->GetData())){
                        unset ($value);//он есть в заполненых, поэтому удаляем из пустых
                        echo '2';
                    }
                }
            }
        }
 * 
 */
        if (!count($arr['parameters_for_post'])){
            unset($arr['parameters_for_post']);
        }

        if (count($arr)){
            return $arr;
        }else{
            return false;
        }

    }


    /*
     *возвращает двумерный массив свойство = значение
     *     [url] => http://www.press-release.ru/
     *   [value] => При использовании материалов ссылка на сайт обязательна
     */
    function getResponseRule($id_rule){
        if (count($this->RULES[$id_rule]['response']['parameters'])){
            $arr = array();
            foreach ($this->RULES[$id_rule]['response']['parameters'] as $key => $value) {
                $arr[$key] = $value;
            }
            return $arr;
        }else{
            return false;
        }
    }

    /*например
     *     [Content-Type] => application/x-www-form-urlencoded; charset=UTF-8;
     *    [X-Requested-With] => XMLHttpRequest
     */
    function getHeadersRule($id_rule){
        if (isset($this->RULES[$id_rule]['headers'])){
            $arr = array();
            foreach ($this->RULES[$id_rule]['headers'] as $key => $value) {
                $arr[strtolower(trim($value['parameters']['name']))] = trim($value['parameters']['value']);
            }
            return $arr;
        }else{
            return false;
        }
    }

    /*
     * например http://www.press-release.ru/
     */
    function getActionRule($id_rule){
        if (!empty($this->RULES[$id_rule]['action']['parameters']['url'])){
            return $this->RULES[$id_rule]['action']['parameters']['url'];
        }else{
            return false;
        }
    }

}

