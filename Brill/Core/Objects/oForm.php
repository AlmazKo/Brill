<?php

/**
 * Класс Формы
 *
 * @author almazKo
 */
class oForm {
protected $fields = array();
protected $action;
protected $method = 'POST';
protected $enctype = 'multipart/form-data';

protected
    $_htmlBefore = '',
    $_htmlAfter = '';
    function __construct(array $fields = array(), $urlExt = array()) {
        if ($urlExt) {
            $urlExt = array('GET' => $urlExt);
        }
        $url = array_replace_recursive(array('GET' => array('ajax' => '1')), $urlExt);
      //  $url = array_replace_recursive(array('GET' => array('ajax' => '1')), array('GET' => array('id' => '6')));
        $this->action = Routing::constructUrl($url);
        // Пример:
        //$fields['name'] = array('title' => '', 'value'=>'', 'type'=>'text', 'validator' => null, 'info'=>'', 'error' => false, $checked = array(););

        

        foreach ($fields as &$settings) {
            if ('select' == $settings['type']) {
                if (empty($settings['data'])) {
                    $settings['data'] = array();
                }
                if ($settings['data'] instanceof oList) {
                } else {
                    if (!is_array($settings['data'])) {
                        $settings['data'] = array((string)$settings['data']);
                    }
                    echo '<br>--+'.implode('_',$settings['data']);
                    $settings['data'] = new oList($settings['data']);
                }
            }
        }
        $this->fields = $fields;
    }
    /**
     * задать поле с всеми настройками
     * @param <type> $key
     * @param array $value
     */
    public function setField($key, array $value) {
        $this->fields[$key] = $value;
    }

    /**
     * Задать значение поля
     * @param <type> $key
     * @param <type> $attr
     * @param <type> $value
     */
    public function setFieldValue($key, $value) {
        $this->fields[$key]['value'] = $value;
    }

    /**
     * изменить атрибут поля
     * @param <type> $key
     * @param <type> $value
     */
    public function setFieldAttr($key, $attr, $value) {
        $this->fields[$key][$attr] = $value;
    }
    /**
     * Объеденяем значение поля с новыми
     * @param string $key
     * @param array $value
     * @return bool
     */
    public function mergeField($key, array $newValues) {
        if ($this->isField($key)) {
                foreach($newValues as $k => $newValue) {
                        $this->fields[$key][$k]	= $newValue;
                }
        }
        return true;
    }
/*
 * TODO убрать этот хардкод
 */
    function setHtmlBefore($html) {
        $this->_htmlBefore = $html;
    }
/*
 * TODO убрать этот хардкод
 */
    function setHtmlAfter($html) {
        $this->_htmlAfter = $html;
    }
/*
 * TODO убрать этот хардкод
 */
    function getHtmlBefore() {
       return $this->_htmlBefore ? '<div class="before_form">' . $this->_htmlBefore . '</div>' : '';
    }
/*
 * TODO убрать этот хардкод
 */
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
            $html .='<label ></label><input type="submit" class="submit" value="'.$submit.'" /></form><div style="clear:both"></div>';
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
                $html .= '<label for="' . $name . '">' . $settings['title'] . (isset($settings['required']) && $settings['required'] ? '*' : '') . ': </label><input type="text" name="' . $name . '" id="' . $name . '" value = "' . $settings['value'] . '" autocomplete="off"/>';
                break;
            case 'textarea':
                $html .= '<label for="' . $name . '">' . $settings['title'] . (isset($settings['required']) && $settings['required'] ? '*' : '') . ': </label><textarea name="' . $name . '" id="' . $name . '" '.$settings['attr'].'>' . $settings['value'] . '</textarea>';
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
                $html .= '<label for="' . $name . '">' . $settings['title'] . (isset($settings['required']) ? '*' : '') . ': </label>';
                if(empty($settings['data'])) {
                    Log::dump($settings);
                    Log::warning('пустой Data');
                }
                $settings['data']->setChecking((array)$settings['value']);
                $html .= $settings['data']->buildHtmlSelect($name, false, (empty($settings['attr'])) ? '' : $settings['attr']);
                break;
            case 'multiSelect':
                break;
            case 'hidden':
                $html .= '<input type="hidden" name="' . $name . '" value = "' . $settings['value'] . '"/>';
                break;
            case 'password':
                $html .= '<label for="' . $name . '">' . $settings['title'] . (isset($settings['required']) && $settings['required'] ? '*' : '') . ': </label><input type="password" name="' . $name . '" id="' . $name . '" value = "' . $settings['value'] . '" autocomplete="off"/>';
                break;
            case 'file':
                break;
            case 'captcha':
                $html .= '<label for="' . $name . '">' . $settings['title'] . (isset($settings['required']) ? '*' : '') . ': </label>';
                if (!isset($settings['src'])) {
                    $settings['src'] = WEB_PREFIX . 'Brill/img/notfound.png';
                }
                $html .= '<input type="text" name="' . $name . '" id="' . $name . '" value = "' . $settings['value'] . '" autocomplete="off" /><img src="' . $settings['src'] . '?uid='.  uniqid() . '"/>';
                break;
            case 'submit':
                break;
        }

        if (isset($settings['info']) && $settings['info']) {
            $html .= '<span class="form_field_info">' . $settings['info'] . '</span>';
        }
        if (isset($settings['error']) && $settings['error']) {
            $html .= '<br/><span class="form_field_error">' . $settings['error'] . '</span>';
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
    public function fill($data = array()) {
        foreach ($this->fields as $name => &$settings) {
            if (isset($data[$name])) {
                if ('select' == $settings['type']) {
                    $list = $settings['data'];
                    $list->fill($data[$name]);
                    $settings['value'] = $list->getSelected();
                } else {
                    $settings['value'] = (string) $data[$name];
                }
            }
        }
    }
    /**
     * Проверяет, чтоб заполнены ли обязательные поля
     */
    public function isComplited() {
        $result = true;
        foreach ($this->fields as $name => $settings) {
            if (isset($settings['required']) && $settings['required']) {
                if ('select' == $settings['type'] && !$settings) {
                    $result = false;
                    $this->fields[$name]['error'] = 'Необходимо выбрать';
                } else if($settings['value'] === '') {
                    $result = false;
                    $this->fields[$name]['error'] = 'Поле обязательно для заполнения';
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
    public function getField($key) {
        return $this->fields[$key];
    }
    public function isField($key) {
        return array_key_exists($key, $this->fields);
    }
    /**
      * Очищает все значения формы
      */
    public function clean() {
        foreach ($this->fields as $name => $settings) {
            if (isset($settings['value'])) {
                $settings['value'] = '';
            }
        }
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