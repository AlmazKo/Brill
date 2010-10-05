<?php
/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of List
 *
 * @author Alexander
 */
class oTable {
    private $page = 1;
    private $search = '';
    private $sort = null;
    private $directSort = 'ASC';
    private $altWhere = '';
    private $name = null;
    //отображаемое название полей
    public $headers = array();
    private $htmlHeaders = array();
    private $values = array();
    //названия полей
    private $fields = array();
    //масссив отображаемых полей
    private $view_cols = array();
    //количество строк
    private $count = 0;

    private $separator = false;
    private $separatorValue = null;
    private $separatorHeader = null;
    private $rulesView = null;
    function __construct(array $tbl) {
        $this->fields = $this->headers = $this->viewCols = $tbl[0];
        $this->values = $tbl[1];
        $this->count = count($this->values);


    }

    /**
     * добавляет правила для отображения полей
     * @param string $field
     * @param string $rule
     */
    function addRulesView($field, $rule) {
        if (in_array($field, $this->fields)) {
            $this->rulesView[$field] = $rule;
        }
    }
    /**
     * Разделитель таблицы
     * @param string $nameSep какое поле должно измениться, чтобы сработало разделитель
     */
    function separator($nameSep, $headerSep = null) {
        if (isset($this->values[0][$nameSep])) {
            $this->separator = $nameSep ;
            $this->separatorValue = $this->values[0][$nameSep];
            // указываем значение какого поля выводить в разделителе
            if ($headerSep) {
                $this->separatorHeader = $headerSep;
            }
        }

    }
    /**
     * Форматирует строку по шаблону
     * @param <type> $cell
     * @param <type> $field
     * @param <type> $row
     * @return <type>
     */
    private function buildTd($cell, $field, $row) {
        $str = $cell;
        if (isset($this->rulesView[$field])) {
            $str = $this->rulesView[$field];
            foreach ($this->fields as $f) {
                $str = str_replace("#$f#", $row[$f], $str);
            }
        }
        return $str;
    }

    /*
     * Отобразить только необходимые столбцы
     * принимает список столбцов
     */
    function viewColumns () {
        $cols = func_get_args();
        $this->viewCols = array();
        $i = 0;
        foreach ($this->fields as $field) {
            if (in_array($field, $cols)) {
                $this->viewCols[$i] = $field;
            }
            $i++;
        }
        if(empty($this->viewCols)) $this->viewCols = $this->fields;
    }

    /*
     * Заменяет текст заголовков
     */
    public function setNamesColumns(array $names) {
        $i = 0;
        foreach ($this->fields  as $field) {
            if (isset($names[$field])) {
                 $this->headers[$i] = $names[$field];
            }
            $i++;
        }
    }
    /**
     * Возвращает 2х мерный массив значений
     * если надо сделать специфический вывод
     * @return array
     */
    public function getValues() {
        return $this->values;
    }

    /**
     * Возвращает массив названий полей
     * если надо сделать специфическую обработку
     * @return array
     */
    public function getFields() {
        return $this->fields;
    }

    /**
     * Строит таблицу
     * @param string $idCss
     * @return string
     */
    public function build($idCss = false) {
        $html = '<table '.($idCss ? 'id="'.$idCss.'" ' : '').'border="0" cellpadding="0" cellspacing="0">';
            $html .= '<tr>';
            foreach ($this->viewCols as $i => $cell) {
                $html .= '<th>' . $this->headers[$i] . '</th>';
            }
            $html .= '</tr>';
            $idRow = 0;
        if(empty($this->values)) {
            $html .= '<tr><td colspan="'.count($this->fields).'" class="null_table">' . LNG_NULL_TABLE . '</td></tr>';
        } else {
            foreach ($this->values as $row) {
                $html .= '<tr>';
                if ($this->separator &&  ($this->separatorValue != $row[$this->separator] || $idRow == 0)) {
                    $html .= '<tr><td colspan="' . count($this->viewCols) . '" class="separator_table">' . ($this->separatorHeader ? $row[$this->separatorHeader] : '') . '</td></tr>';
                    $this->separatorValue = $row[$this->separator];
                }
                $i = 0;
                foreach ($row as $cell) {
                    //включено ли ли это поле
                    if(isset($this->viewCols[$i])) {
                        $html .= '<td>' . $this->buildTd($cell, $this->viewCols[$i], $row) .'</td>';
                    }
                    $i++;
                }
                $idRow++;
                $html .= '</tr>';
            }
        }
        $html .= '</table>';
        return $html;
    }
}
