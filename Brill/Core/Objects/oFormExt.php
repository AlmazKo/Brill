<?php
/**
 * Class  extended form
 * @author almazKo
 */
class oFormExt extends oForm {


    /**
     * form wrapper
     * @param string $id
     * @param string $content
     * @return string
     */
    function buildFree($id, $content) {
            $html = '';
            $html .= '<form id = "' . $id . '" enctype="multipart/form-data" method="post" action="' . $this->action .'">';
            $html .= $content;
            $html .='<input type="submit"></form>';
            return $html;
    }

    /**
     * Save Form as xml
     * @param <type> $fileName
     */
    public function save($fileName)  {
        $data = $this->getXmlAsText();
        //TODO вынести отсюда  работу с файлами
        if (!$handle = fopen($fileName, 'w')) {
            Log::warning("Не могу открыть файл ($fileName)");
        }
        if (fwrite($handle, $data) === FALSE) {
            Log::warning("Не могу произвести запись в файл ($fileName)");
        }
        fclose($handle);
        return true;
    }

    /**
     * Load form in string
     * @param string $fileName
     * @return oForm
     */
    public function loadFromString($string) {
        $this->fields = $fields = array();
        $sxe = simplexml_load_string($string);
        if ($sxe) {
            foreach ($sxe->field as $key => $value) {
                $attr = current((array)$value->attributes());
                if (isset($attr['data']) && is_string($attr['data'])) {
                    $attr['data'] = @unserialize($attr['data']);
                }
                $v = (array)$value;
                $fields[$attr['name']] = $attr;
                $fields[$attr['name']]['value'] = $value ? (string)$value : '' ;
            }
        }
        $this->fields = $fields;
    }
    /**
     * Загрузить форму из xml файла
     * @return <type>
     */
    public function loadFromFile() {
        $this->fields = array();
        $sxe = simplexml_load_file($fileName);
        foreach ($sxe->document->fields as $key => $value) {
            $this->fields[$value['name']];
            foreach ($value['parameters'] as $key1 => $value1) {

            }
        }
        return $form;
    }


    /**
     * TODO Возможно вынести в хелпер для работы с хмл
     * Returned XML as text
     * @return string
     */
    public function getXmlAsText() {
        $sxe = $this->getXml();
        // Проводятся манипуляции, чтобы XML была человеческого вида
        $dom = new DOMDocument('1.0');
        $dom->preserveWhiteSpace = false;
        $dom->formatOutput = true;
        $dom->loadXML($sxe->asXML());
        $data = $dom->saveXML();
        return $data;
    }

     /**
     * Конвертирует форму в Xml
     * TODO convertToXml()
     * @return SimpleXMLElement
     */
    public function getXml() {
        $string = '<?xml version="1.0" encoding="UTF-8"?><document></document>';
        $sxe = simplexml_load_string($string);
        foreach ($this->fields as $name => $aField) {
            $value = isset($aField['value']) ? $aField['value'] : '';
            $value = htmlspecialchars($value, ENT_QUOTES, ENCODING_CODE);
            $newField = $sxe->addChild('field', $value);
            foreach($aField as $key => $value) {
                if ('value' != $key && 'data' != $key) {
                    $newField->addAttribute($key, $value);
                } else if ('data' == $key) {
                    $newField->addAttribute($key, serialize($value));
                }
            }
            $attrs = $newField->attributes();
            if (!isset($attrs['name'])) {
                $newField->addAttribute('name', $name);
            }
        }
        return $sxe;
    }
}
