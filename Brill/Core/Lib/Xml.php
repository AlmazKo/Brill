<?php
/**
 * Description of Xml
 *
 * @author Alexander
 */
class Xml {
    /**
     * преобразует xml теги 
     * @param string $text
     * @return string
     */
    public static function prepareTextForXml ($text){
        return str_replace(array('&', '<', '>'), array('&amp;', '&lt;', '&gt;'), $text);
    }
}
?>
