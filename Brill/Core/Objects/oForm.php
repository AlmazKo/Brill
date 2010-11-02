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
    function __construct(array $fields = array(), $url = null) {
        $this->action = Routing::constructUrl($url);
        // Пример:
        //$fields['name'] = array('title' => '', 'value'=>'', 'type'=>'text', 'validator' => null, 'info'=>'', 'error' => false, $checked = array(););

        $this->fields = $fields;
    }

    /**
     * Строит форму
     *
     * @param bool $disable
     * @return string
     */
    public function buildHtml($id = 'form',$disable = false) {
        $html = '';
        if ($this->fields) {
            $html .= '<form id="' . $id . '" enctype="'.$this->enctype.'" method="'.$this->method.'" action="' . $this->action . '">';
            foreach ($this->fields as $name => $settings) {
                $html .= self::buildFieldHtml($name, $settings);
            }
            $html .='<label></label><input type="submit"></form>';
        }
        return $html;
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
        $html = '<p>';
        switch ($settings['type']) {
            case 'text':
                $html .= '<label for="' . $name . '">' . $settings['title'] . (isset($settings['requried']) ? '*' : '') . ': </label><input type="text" name="' . $name . '" id="' . $name . '" value = "' . $settings['value'] . '" autocomplete="off"/>';
                break;
            case 'textarea':
                $html .= '<label for="' . $name . '">' . $settings['title'] . (isset($settings['requried']) ? '*' : '') . ': </label><textarea name="' . $name . '" id="' . $name . '" '.$settings['attr'].'>' . $settings['value'] . '</textarea>';
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
                break;
            case 'multiSelect':
                break;
            case 'hidden':
                break;
            case 'file':
                break;
            case 'submit':
                break;
        }
        if ($settings['info']) {
            $html .= '<br /><span class="form_field_info">' . $settings['info'] . '</span>';
        }
        if ($settings['error']) {
            $html .= '<br /><span class="form_field_error">' . $settings['error'] . '</span>';
        }
        $html .= '</p>';
        return $html;
    }

    /**
     * Заполняет форму данными
     * @param <type> $data
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
            if (isset($settings['requried'])) {
                if($settings['requried'] && $settings['value'] === '') {
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
    public function getArrayAttr($array) {
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