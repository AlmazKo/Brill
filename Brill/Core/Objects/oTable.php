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
function cmp($a, $b) {
    if ($a == $b) {
        return 0;
    }
    return ($a < $b) ? -1 : 1;
}


class oTable implements ISorting{
    protected $page = 1;
    protected $search = '';
    protected $sort = null;
    protected $directSort = 'ASC';
    protected $altWhere = '';
    protected $name = null;
    //отображаемое название полей
    public $headers = array();
    protected $htmlHeaders = array();
    protected $values = array();
    //названия полей
    protected $fields = array();
    //масссив отображаемых полей
    protected $view_cols = array();
    //количество строк
    protected $count = 0;

    protected $separator = false;
    protected $separatorValue = null;
    protected $separatorHeader = null;
    protected $rulesView = null;
    protected $mapsView = null;
    protected $viewIterator = false;
    protected $viewHead = true;
    protected $typeSelected = false;
    protected $viewSorting = true;
    function __construct($data) {
        if(is_object($data)) {
            if (is_subclass_of($tbl, 'Model')) {
                $this->fields = $this->headers = $this->viewCols = $data->getFields();
                $this->values = $data->getValues();
            } else {
                Log::warning('Переданы, не поддерживемые ' .__CLASS__ . ' данные');
            }

        } else if (is_array ($data)) {
            $this->fields = $this->headers = $this->viewCols = $data[0];
            $this->values = $data[1];
        } else {
            Log::warning('Переданы, не поддерживемые ' .__CLASS__ . ' данные');
        }

        $this->count = count($this->values);

    }

    /**
     * добавляет правила для отображения полей
     * @param string $field
     * @param string $rule
     */
    function addRulesView($field, $rule) {
        if ($this->isField($field)) {
            $this->rulesView[$field] = $rule;
        }
    }

    /*
     * Связывает столбец с пред-обработчиком
     */
    function addMap ($field, $func) {
        if ($this->isField($field)) {
            $this->mapsView[$field] = $func;
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
     * @param string $cell
     * @param string $field
     * @param string $row
     * @return <type>
     */
    protected function buildTd($cell, $field, $row) {
        $str = $cell;
        if (isset($this->mapsView[$field])) {
            $func = $this->mapsView[$field];
            $str = $func($str);
        }
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
     * Существует ли это поле
     * @param string $field
     */
    public function isField($field) {
        return in_array($field, $this->fields);
    }

    /**
     * выводить столбец с нумерацией
     * @param bool $view
     */
    function setViewIterator($view = false) {
        $this->viewIterator = (bool) $view;
    }

    function setViewHead($view = true) {
        $this->viewHead = (bool) $view;
    }
    /**
     *
     * @param array $selected
     * @param bool $multi  true - checked, false - radio
     * @param int $minMulti  минимльное количество для выбора
     * @param int $maxMulti максимальое количество для выбора, если стоит -1 - то не ограничено
     */
    public function setSelected(array $selected, $multi = true, $minMulti = 1,  $maxMulti = -1){

    }

    function  sort($field, $direction = null) {
        if ($this->isField($field)) {
            if($this->values) {
                foreach ($this->values as $row) {
                    $tmp[] = $row[$field];
                }
                if ($direction !== 'DESC' ) {
                    asort($tmp);
                } else {
                    arsort($tmp);
                }
                foreach ($tmp as $key => $row) {
                    $new[] = $this->values[$key];
                }
                 unset($tmp);
                $this->values = $new;
            }
            return true;
        } else {
            return false;
        }
    }
    /**
     * выводить столбец с нумерацией
     * @param bool $view
     */
    function setViewSorting($view = false) {
        $this->viewSorting = (bool) $view;
    }

    /**
     * Возвращает столбец таблицы
     *
     * @param string $field
     * @return array, в случае осутствия столбца - false
     */
    public function getCol($field) {
        if ($this->isField($field)) {
            $col = array();
            foreach ($this->values as $row) {
                $col[] = $row[$field];
            }
            return $col;
        } else {
            return false;
        }
    }

    public function setCol($field, $col) {
        if (count($col) == count($this->values)) {
            foreach ($this->values as $key => $row) {
                
                $this->values[$key][$field] = $col[$key];
            }
            return true;
        } else {
            return false;
        }
    }
    /**
     * Строит таблицу
     * @param string $idCss
     * @return string
     */
    public function build($idCss = false, $classCss = false) {
        $html = '<table '.($idCss ? 'id="'.$idCss.'" ' : '').($classCss ? 'class="'.$classCss.'" ' : '').'border="0" cellpadding="0" cellspacing="0" >';
        
        if ($this->viewHead) {
            $html .= $this->buildHead();
        }
        $html .= $this->buildBody();
        $html .= '</table>';
        return $html;
    }

    public function buildHead (){
        $html = '<tr>';

        if ($this->viewIterator) {
            $html .= '<th><a href="'. Routing::constructUrl(array('nav' => array())).'">#</a></th>';
        }
        foreach ($this->viewCols as $i => $cell) {
            if ($this->viewSorting) {
               
                $html .= '<th> <a href="'. Routing::constructUrl(array('nav' => array('field' => $this->fields[$i]))).'">' . $this->headers[$i] . '</a></th>';
            } else {
                $html .= '<th>' . $this->headers[$i] . '</th>';
            }
            
        }
        $html .= '</tr>';
        return $html;
    }


    public function buildBody (){
        $html = '';
        $idRow = 0;
        if(empty($this->values)) {
            $html .= '<tr><td colspan="'.count($this->fields).'" class="null_table">' . LNG_NULL_TABLE . '</td></tr>';
        } else {
            foreach ($this->values as $row) {
                $html .= '<tr>';
                if ($this->viewIterator) {
                    $html .= '<td>' . ($idRow + 1) . '</td>';
                }
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
        return $html;
    }

}