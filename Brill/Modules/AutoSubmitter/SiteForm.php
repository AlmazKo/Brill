<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of SiteForm
 *
 * @author Alexander
 */
class SiteForm {
    // массив [имя поля]= значение
    private $fields = array ();

    private function constructForm($siteHostб, $fields = array()) {
        //
    }
    //должна брать RegistryRequest->get('POST')
    //учитывает все поля с их валидаторами
    //если все ок возвращает тру, иначе вызывает констрк
    private function setFields() {

    }
}
