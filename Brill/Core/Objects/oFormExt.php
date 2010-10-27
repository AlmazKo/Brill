<?php
/**
 * Class  extended form
 * @author almazKo
 */
class oFormExt extends oForm{


    /**
     * form wrapper
     * @param <type> $id
     * @param <type> $content
     * @return string
     */
    function buildFree($id, $content) {
            $html = '';
            $html .= '<form id = "' . $id . '" enctype="multipart/form-data" method="post" action="' . $this->url .'">';
            $html .= $content;
            $html .='<input type="submit"></form>';
            return $html;
    }

    /**
     * Save Form as xml
     * @param <type> $fileName
     */
    public function save($fileName)  {
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

        if (!$handle = fopen($fileName, 'a')) {
            Log::warning("Не могу открыть файл ($fileName)");
        }
        if (fwrite($handle, $data) === FALSE) {
            Log::warning("Не могу произвести запись в файл ($fileName)");
        }
        fclose($handle);
        return true;
    }
    /**
     * Load form in xml file
     * @param string $fileName
     * @return oForm
     */
    public function load($fileName) {
        $sxe = simplexml_load_file($fileName);


        foreach ($sxe->document->fields as $key => $value) {
            foreach ($value['parameters'] as $key1 => $value1) {

            }
        }
        return $form;
    }
}

