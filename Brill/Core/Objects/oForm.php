<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Класс Формы
 *
 * @author almazKo
 */
class oForm {
protected $fields = array();
    function __construct(array $fields = array()) {
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
    //    Log::dump($this->fields);
        $html = '';
        if ($this->fields) {
            $html .= '<form id = "' . $id . '" enctype="multipart/form-data" method="post" action="'.$_SERVER['REQUEST_URI'].'">';
            foreach ($this->fields as $name => $settings) {
                $html .= self::buildFieldHtml($name, $settings);
            }
            $html .='<input type="submit"></form>';
        }
        return $html;
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
                $html .= '<label for="' . $name . '">' . $settings['title'] . (isset($settings['requried']) ? '*' : '') . ': </label><input type="text" name="' . $name . '" id="' . $name . '" value = "' . $settings['value'] . '" />';
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
                if($settings['requried'] && empty($settings['value'])) {
                    $this->fields[$name]['error'] = 'Поле обязательно для заполнения';
                    $result = false;
                }
            }
        }
        return $result;
    }
}

