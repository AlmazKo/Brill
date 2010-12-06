<?php

/**
 * Класс Формы
 *
 * @author almazKo
 */
class oForm {
protected $fields = array();
//TODO сделать как роутинг ссылку на экшен. а в верстке делать конструкУРл
protected $action;
protected $method = 'POST';
protected $enctype = 'multipart/form-data';

protected
    $_htmlBefore = '',
    $_htmlAfter = '';
    function __construct(array $fields = array(), $url = array()) {
        $url = array_replace_recursive(array('GET' => array('ajax' => '1')), $url);
        $this->action = Routing::constructUrl($url);
        // Пример:
        //$fields['name'] = array('title' => '', 'value'=>'', 'type'=>'text', 'validator' => null, 'info'=>'', 'error' => false, $checked = array(););

        $this->fields = $fields;
    }
    public function setField($key, array $value) {
        $this->fields[$key] = $value;
    }

    function setHtmlBefore($html) {
        $this->_htmlBefore = $html;
    }

    function setHtmlAfter($html) {
        $this->_htmlAfter = $html;
    }

    function getHtmlBefore() {
       return $this->_htmlBefore ? '<div class="before_form">' . $this->_htmlBefore . '</div>' : '';
    }

    function getHtmlAfter() {
        return $this->_htmlAfter ? '<div class="after_form">' . $this->_htmlAfter . '</div>' : '';
    }
    /**
     * Строит форму
     *
     * @param mixed $submit - если false - форма блочится и кнопка сабмита убирается
     *
     * @return string
     */
    public function buildHtml($idCss = 'form', $classCss = 'form', $submit = 'Отправить') {
        $html = $this->getHtmlBefore();
        if ($this->fields) {
            $html .= '<form '.($idCss ? 'id="' . $idCss . '" ' : '').($classCss ? 'class="' . $classCss . '" ' : '').'enctype="'.$this->enctype.'" method="'.$this->method.'" action="' . $this->action . '">';
            $html .= '<div class="form_content">';
            foreach ($this->fields as $name => $settings) {
                $html .= self::buildFieldHtml($name, $settings);
            }
            $html .= '</div>';
            $html .='<input type="submit" class="submit" value="'.$submit.'" /></form><div style="clear:both"></div>';
        }
        return $html . $this->getHtmlAfter();
    }

    /**
     * строит форму, как текст
     */
    public function buildText ($idCss = null, $classCss = 'text_form') {
        $arr1 = array('title', 'value');
        $arr2 = $this->getArrayAttr($arr1);
        $tbl = new oTable(array($arr1, $arr2));
        $tbl->setViewHead(false);
        return $tbl->build($idCss, $classCss);
    }

    /**
     * строит html элемент
     *
     * @param <type> $name
     * @param <type> $settings \
     */
    private static function buildFieldHtml($name, $settings) {
        $html = '';
        if(!isset($settings['type']) || $settings['type'] != 'hidden') {
            $html = '<p>';
        }
        switch ($settings['type']) {
            case 'text':
                $html .= '<label for="' . $name . '">' . $settings['title'] . ($settings['required'] ? '*' : '') . ': </label><input type="text" name="' . $name . '" id="' . $name . '" value = "' . $settings['value'] . '" autocomplete="off"/>';
                break;
            case 'textarea':
                $html .= '<label for="' . $name . '">' . $settings['title'] . ($settings['required'] ? '*' : '') . ': </label><textarea name="' . $name . '" id="' . $name . '" '.$settings['attr'].'>' . $settings['value'] . '</textarea>';
                break;
            case 'enum':
                $html .= '<span>' . $settings['title'] . '</span>';
                foreach ($settings['value'] as $value => $title) {
                    $html .= '<br /><input type="radio" name="' . $name . '" id="' . $name . '_' . $value . '" value = "' . $value . '" ' . (in_array($value, $settings['checked']) ? 'checked' : '') . '><label for="' . $name . '_' . $value . '">' . $title . '</label>';
                }
                break;
            case 'set':
                $html .= '<span>' . $settings['title'] . '</span>';
                foreach ($settings['value'] as $value => $title) {
                    $html .= '<br /><input type="checkbox" name="' . $name . '" id="' . $name . '_' . $value . '" value = "' . $value . '" ' . (in_array($value, $settings['checked']) ? 'checked' : '') . '><label for="' . $name . '_' . $value . '">' . $title . '</label>';
                }
                break;
            case 'select':
//                $html .= '<label for="' . $name . '">' . $settings['title'] . ($settings['required'] ? '*' : '') . ': </label>';
//                $settings['data']->setChecking((array)$settings['value']);
//                $html .= $settings['data']->buildHtmlSelect($name);
                break;
            case 'multiSelect':
                break;
            case 'hidden':
                $html .= '<input type="hidden" name="' . $name . '" value = "' . $settings['value'] . '"/>';
                break;
            case 'file':
                break;
            case 'captcha':
                $html .= '<label for="' . $name . '">' . $settings['title'] . ($settings['required'] ? '*' : '') . ': </label>';
                if (!isset($settings['src'])) {
                    $settings['src'] = WEB_PREFIX . 'Brill/img/notfound.png';
                }
                $html .= '<input type="text" name="' . $name . '" id="' . $name . '" value = "' . $settings['value'] . '" autocomplete="off" /><img src="' . $settings['src'] . '"/>';
                break;
            case 'submit':
                break;
        }
       
        if (isset($settings['info']) && $settings['info']) {
            $html .= '<span class="form_field_info">' . $settings['info'] . '</span>';
        }
        if (isset($settings['error']) && $settings['error']) {
            $html .= '<span class="form_field_error">' . $settings['error'] . '</span>';
        }
        if(isset($settings['type']) && $settings['type'] != 'hidden') {
            $html .= '<div style="clear:both"></div></p>';
        }
        return $html;
    }

    /**
     * Заполняет форму данными
     * @param array $data
     */
    public function fill($data) {
        foreach ($this->fields as $name => $settings) {
            if (isset($data[$name])) {
                $this->fields[$name]['value'] = $data[$name];
            }
        }
    }
    /**
     * Проверяет, чтоб заполнены ли обязательные поля
     */
    public function isComplited() {
        $result = true;
        foreach ($this->fields as $name => $settings) {
            if (isset($settings['required'])) {
                if($settings['required'] && $settings['value'] === '') {
                    $this->fields[$name]['error'] = 'Поле обязательно для заполнения';
                    $result = false;
                }
            }
        }
        return $result;
    }

    /**
     * Возвращает массив значений
     * @return array
     */
    public function getValues() {
        $values = array();
        foreach ($this->fields as $name => $settings) {
            $values[$name] = $settings['value'];
        }
        return $values;
    }
    public function getFields() {
        return $this->fields;
    }

    /**
     * Формирует массив-таблицу из формы, у которой поля - это только те атрибуты, которые есть в $aAttr
     * @param array $array
     * @return array
     */
    public function getArrayAttr($array = array('title', 'value')) {
        $aAttr = array();
        $values = array();
        foreach ($array as $key => $value) {
            $aAttr[$value] = $key;
        }
        foreach ($this->fields as $name => $settings) {
            $values[$name] = array_intersect_key($settings, $aAttr);
        }
        return $values;
    }
    /**
     * Возвращает значение указанного поля
     *
     * @param string $fieldName
     * @return
     */
    public function getFieldValue($fieldName) {
        $values = $this->getValues();
        return $values[$fieldName];
    }
}