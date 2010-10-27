<?php
/**
 * Форма рассылки
 *
 * @author Alexander
 */
class UserSubscribeForm extends oFormExt {
    public function __construct(array $fields = array(), $url = null) {
        $fields['title'] = array('title' => 'Заголовок', 'value'=>'', 'type'=>'text', 'requried' => true, 'validator' => null, 'info'=>'', 'error' => false, 'attr' => '', $checked = array());
        $fields['company'] = array('title' => 'Компания', 'value'=>'', 'type'=>'text', 'requried' => true, 'validator' => null, 'info'=>'', 'error' => false, $checked = array());
        $fields['preview'] = array('title' => 'Анонс', 'value'=>'', 'type'=>'textarea', 'requried' => true, 'validator' => null, 'info'=>'', 'error' => false, 'attr' => 'rows=4',$checked = array());
        $fields['release'] = array('title' => 'Релиз', 'value'=>'', 'type'=>'textarea', 'requried' => true, 'validator' => null, 'info'=>'', 'error' => false, 'attr' => 'rows=10',$checked = array());
        $fields['email'] = array('title' => 'Email', 'value'=>'', 'type'=>'text', 'requried' => true, 'validator' => null, 'info'=>'', 'error' => false, $checked = array());
        $fields['name'] = array('title' => 'Автор', 'value'=>'', 'type'=>'text', 'requried' => true, 'validator' => null, 'info'=>'', 'error' => false, $checked = array());
        parent::__construct($fields, $url);
    }
}