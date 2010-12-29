<?php
/**
 * Класс расширяющий возможности DOMDocument
 * и работающий с utf8
 *
 * @author almaz
 */
class DomExt {
    private
        $_dom,
        $_xpath;

    /**
     * Загрузка дома из текста.
     * Ошибки игнорируеются, utf-8 поддерживается
     * @param <type> $html
     * @param <type> $encode
     * @return <type>
     */
    public function __construct($html, $encode = 'UTF-8') {
        $this->_dom = new DOMDocument('1.0', $encode);
        $this->_dom->preserveWhiteSpace = false;
        libxml_use_internal_errors(TRUE);
        $html = preg_replace('/(<META(?:.*)charset=[\"\']?)([^\'\"]*)([\"\']?(?:.*)\>)/i', '$1' . $encode . '$3', $html, 1);
        $this->_dom->loadHTML('<?xml encoding="UTF-8">' . $html);
        libxml_clear_errors();
        // dirty fix
        foreach ($this->_dom->childNodes as $item) {
            if (XML_PI_NODE == $item->nodeType) {
                $this->_dom->removeChild($item); // remove hack
            }
        }
       // $this->_dom->encoding = 'UTF-8';
        $this->_xpath = new DOMXpath($this->_dom);
        return $this->_dom;
    }

    /**
     * Из html выдергивает форму и парсит ее
     * Правильно отрабатывает html5 фичи
     * 
     * @param int $formNum id или порядковый номер формы
     * @return array
     */
    public function parseForm($formNum = 0) {
        $entries = $this->_xpath->query('//*/form[position()=1]//select[@name] | //*/form[position()=1]//input[@name]| //*/form[position()=1]//textarea[@name]');
        $form = array();
        foreach ($entries as $el) {
            if ('input' == $el->nodeName) {
                switch ($el->getAttribute('type')) {
                    case 'text':
                        $form[$el->getAttribute('name')] = $el->getAttribute('value');
                        break;
                    case 'hidden':
                        $form[$el->getAttribute('name')] = $el->getAttribute('value');
                        break;
                    case 'checkbox':
                    case 'radio':
                        if (!isset($form[$el->getAttribute('name')])) {
                            $form[$el->getAttribute('name')] = array();
                        }
                        $form[$el->getAttribute('name')][] = $el->getAttribute('value');
                        break;
                    case 'radio':
                        if (!isset($form[$el->getAttribute('name')])) {
                            $form[$el->getAttribute('name')] = array();
                        }
                        $form[$el->getAttribute('name')][] = $el->getAttribute('value');
                        break;
        #            case 'file':
        #            case 'image':
                    case 'submit':
                        $form[$el->getAttribute('name')] = $el->getAttribute('value');
                        break;
       #             case 'reset':
       #             case 'button':
                    default:
                        //остальные обрабатывать также как и text
                        $form[$el->getAttribute('name')] = $el->getAttribute('value');
                        break;
                }
            } elseif ('select' == $el->nodeName) {
                $selectName = $el->getAttribute('name');
                foreach($el->childNodes as $option) {
                    $form[$selectName][$option->getAttribute('value')] = trim($option->nodeValue);
                }

            } elseif ('textarea' == $el->nodeName) {
                $form[$el->getAttribute('name')] = $el->nodeValue;
            }
        }
        return $form;
    }

}