<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of UserDataProject
 *
 * @author lubyagin sergey
 */
class UserDataProject extends UserData{

    protected  $title = 'тайтл';
    protected  $annotation = 'аннотация';
    protected  $text = 'текст';
    protected  $link = 'link';
    protected  $anchor = 'anchor';
    protected  $keywords = 'keywords';
    protected  $Data = array();//этот массив заполняет пользовател в процессе работы

    function  __construct() {
//
//        $f = fopen('user.txt', 'rt');
//        while (!feof ($f)){
//            $s = fgets ($f, 255);
//            $arr = explode(':', $s);
//            $this->Data[$arr[0]] = $arr[1];
//        }
//        $this->setData =
//        fclose($f);
    }


    function SetTitle($value) {
        $this->title = $value;
    }
    function GetTitle() {
        return $this->title;
    }

    function SetAnnotation($value) {
        $this->annotation = $value;
    }
    function GetAnnotation() {
        return $this->annotation;
    }

    function SetText($value) {
        $this->text = $value;
    }
    function GetText() {
        return $this->text;
    }

    function SetLink($value) {
        $this->link = $value;
    }
    function GetLink() {
        return $this->link;
    }

    function SetAnchor($value) {
        $this->anchor = $value;
    }
    function GetAnchor() {
        return $this->anchor;
    }

    function SetKeywords($value) {
        $this->keywords = $value;
    }
    function GetKeywords() {
        return $this->keywords;
    }


    function SetData($arr){
//        foreach ($arr as $key=>$value){
//            $this->Data[$key] = $value;
//        }
//        $f= fopen('user.txt', 'wt');
//        foreach ($this->Data as $key=>$value){
//            $str = $key . ':' . $value;
//            fputs($f, $str . "\r\n", 255);
//        }
//        fclose($f);
    }
    function GetData(){
        return $this->Data;
    }

}
?>
