<?php
/**
 * Форма рассылки
 *
 * @author Alexander
 */
class UserSubscribeForm extends oForm {
    public function __construct(array $fields = array()) {
        $fields['title'] = array('title' => 'Заголовок', 'value'=>'', 'type'=>'text', 'requried' => true, 'validator' => null, 'info'=>'', 'error' => false, 'attr' => '', $checked = array());
        $fields['company'] = array('title' => 'Компания', 'value'=>'', 'type'=>'text', 'requried' => true, 'validator' => null, 'info'=>'', 'error' => false, $checked = array());
        $fields['preview'] = array('title' => 'Анонс', 'value'=>'', 'type'=>'textarea', 'requried' => true, 'validator' => null, 'info'=>'', 'error' => false, 'attr' => 'rows=4',$checked = array());
        $fields['release'] = array('title' => 'Релиз', 'value'=>'', 'type'=>'textarea', 'requried' => true, 'validator' => null, 'info'=>'', 'error' => false, 'attr' => 'rows=10',$checked = array());
        $fields['email'] = array('title' => 'Email', 'value'=>'', 'type'=>'text', 'requried' => true, 'validator' => null, 'info'=>'', 'error' => false, $checked = array());
        $fields['name'] = array('title' => 'Автор', 'value'=>'', 'type'=>'text', 'requried' => true, 'validator' => null, 'info'=>'', 'error' => false, $checked = array());
        parent::__construct($fields);
    }

    /**
     * Сохраняет форму, как xml
     * @param <type> $fileName
     */
    public function save($subscribeName)  {
        $string = '<?xml version="1.0" encoding="UTF-8"?><document></document>';
        $sxe = simplexml_load_string($string);
        foreach ($this->fields as $name => $settings) {
            $field = $sxe->addChild('field', isset($settings['value']) ? $settings['value'] : '');
            foreach($settings as $key => $value) {
                if ($key != 'value') {
                    $field->addAttribute($key, $value);
                }
            }
        }

        $dom = new DOMDocument('1.0');
        $dom->preserveWhiteSpace = false;
        $dom->formatOutput = true;
        $dom->loadXML($sxe->asXML());
        $data = $dom->saveXML();

        $filename = MODULES_PATH . 'AutoSubmitter/XmlSubscribeForms/'.$subscribeName;
        if (!$handle = fopen($filename, 'w')) {
            Log::warning("Не могу открыть файл ($filename)");
        }
        if (fwrite($handle, $data) === FALSE) {
            Log::warning("Не могу произвести запись в файл ($filename)");
        }
        fclose($handle);
        return true;
    }
    /**
     *
     * @param string $subscribeName]
     * @return oForm
     */
    public function load($subscribeName) {
        $sxe = simplexml_load_file($subscribeName);


        foreach ($sxe->document->fields as $key => $value) {
            foreach ($value['parameters'] as $key1 => $value1) {

            }
        }
        return $form;
    }
}