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
     * Сохраняет xml получая данные из поста
     * @param <type> $fileName
     */
   public function save($fileName22 ='asa')  {




$string =  "<?xml version='1.0'?>
<document>
</document>";
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
$data =$dom->saveXML();



$subscribeName = '0'.'_'.'0'.'.xml';

$filename = MODULES_PATH . 'AutoSubmitter/XmlSubscribeForms/'.$subscribeName;
$somecontent = $data;
    // В нашем примере мы открываем $filename в режиме "дописать в конец".
    // Таким образом, смещение установлено в конец файла и
    // наш $somecontent допишется в конец при использовании fwrite().
    if (!$handle = fopen($filename, 'w')) {
         echo "Не могу открыть файл ($filename)";
         exit;
    }

    // Записываем $somecontent в наш открытый файл.
    if (fwrite($handle, $somecontent) === FALSE) {
        echo "Не могу произвести запись в файл ($filename)";
        exit;
    }

    echo "Ура! Записали ($somecontent) в файл ($filename)";

    fclose($handle);


   }
}
