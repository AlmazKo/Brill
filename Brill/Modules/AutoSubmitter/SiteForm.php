<?php

/**
 * Форма под конкретный сайт
 *
 * @author Alexander
 */
class SiteForm extends oForm {
    // массив [имя поля]= значение
    private $fields = array ();
    private $site = array ();


    private function constructForm($fields = array()) {
        //
    }
    //должна брать RegistryRequest->get('POST')
    //учитывает все поля с их валидаторами
    //если все ок возвращает тру, иначе вызывает constructForm, которая отдается во вью
    private function setFields() {

    }
}
