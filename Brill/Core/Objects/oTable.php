<?php

/**
 * Класс настройки и сборки таблицы
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
    const
        OPT_DEL = 'Del',
        OPT_EDIT = 'Edit',
        SORT_ASC = 'asc',
        SORT_DESC = 'desc'    ;
    
    protected
        //текущая страница
        $page = 1,
        // строка для поиска
        $search = '',
        // в каких столбцах искать, по умолчанию - во всех
        $searchFields = array(),
        // по какому столбцу сортировка, по умолчанию - без сортировки
        $sort = null,
        // направление сортировки
        $directSort = 'ASC',
        $altWhere = '',
        // название/заголовок таблицы, по умолчанию - нет
        $name = null,
        //отображаемое название полей
        $headers = array(),
        $htmlHeaders = array(),
        // значения таблицы
        $values = array(),
        //названия полей
        $fields = array(),
        //масссив отображаемых полей
        $view_cols = array(),
        //количество строк
        $count = 0,
        $separator = false,
        $separatorValue = null,
        $separatorHeader = null,
        $rulesView = null,
        $mapsView = null,
        $viewIterator = false,
        $viewHead = true,
        $typeSelected = false,
        $viewSorting = true,
        $_opts = array(),
        $_sort;

    function __construct($data) {
        if (is_array($data)) {
            $this->fields = $this->headers = $this->viewCols = $data[0];
            $this->values = $data[1];
        } else {
            Log::warning('Переданы, не поддерживаемые ' .__CLASS__ . ' данные');
        }
        $this->count = count($this->values);
    }

    /**
     * добавляет правила для отображения полей
     *
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
     * @param string $nameSep какое поле должно измениться, чтобы сработал разделитель
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
     * @return string
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
     * если надо сделать специфическую обработку
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
     * @return bool
     */
    public function isField($field) {
        return in_array($field, $this->fields);
    }

    /**
     * выводить ли таблицу с нумерацией
     * @param bool $view
     */
    function setViewIterator($view = false) {
        $this->viewIterator = (bool) $view;
    }

    /**
     * выводить таблицу с ссылкой на редактирование
     * @param bool $view
     */
    function setIsEdit($edit = false) {
        if ($edit) {
            $this->_opts[self::OPT_EDIT] = true;
        } else {
            unset($this->_opts[self::OPT_EDIT]);
        }
    }

   /**
     * выводить таблицу с ссылкой на удаление
     * @param bool $view
     */
    function setIsDel($del = false) {
        if ($del) {
            $this->_opts[self::OPT_DEL] = true;
        } else {
            unset($this->_opts[self::OPT_DEL]);
        }
    }

    /**
     * Добавить свою опцию
     *
     * @param string $img название картинки
     * @param string $action - на какой экшен уходим
     * @param string $urlGetParameter - параметр
     */
    function setCustomOpt($name, $title, $img, $action, $act, $urlGetParameter) {
        $this->_opts[$name] = array(
            'action' => $action,
            'act' => $act,
            'image' => $img,
            'arg' => $urlGetParameter,
            'title' => $title
            );
    }

    function buildOpt ($pkValue) {
        $html = '';
        foreach ($this->_opts as $key => $value) {
            if ($key != self::OPT_DEL && $key != self::OPT_EDIT) {
                $html .= '<a href="' . Routing::constructUrl(array('action' => $value['action'], 'act' => $value['act']), false) . '?' . $value['arg'].  '=' . $pkValue.'" ajax="1">'.
                         '<img title="' . $value['title'] . '" src="' . WEB_PREFIX .'Brill/img/' . $value['image'] . '" /></a>';
            }
        }
        return $html;
    }

    function isOptions () {
        return (bool) count($this->_opts);
    }
    /**
     * Выводить ли шапку таблицы
     * @param bool $view
     */
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

    /**
     * Поддержка интерфейса ISorting
     */
    function  sort($field, $direction = self::SORT_ASC) {
        if (is_null($direction)) {
            $direction = self::SORT_ASC;
        }
        if ($this->isField($field)) {
            if($this->values) {
                 $this->_sort = array();
                if(self::SORT_ASC == $direction) {
                    $this->_sort[$field] = self::SORT_DESC;
                } else {
                    $this->_sort[$field] = self::SORT_ASC;
                }
                
                foreach ($this->values as $row) {
                    $tmp[] = $row[$field];
                }
                if (self::SORT_DESC != $direction) {
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
     * выводить ли столбец с нумерацией
     * @param bool $view
     */
    function setViewSorting($view = false) {
        $this->viewSorting = (bool) $view;
    }

    /**
     * Возвращает столбец таблицы, учитывая сортировку
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

    function addCol($field) {
        $this->fields[] = $this->headers[] = $this->viewCols[] = $field;
    }
    /**
     * Задать столбец в таблице
     * если уже есть с таким именем - обновит текущей столбец
     * @param string $field название столбца
     * @param array $col массив значений столбца
     * @return bool  вернет false, если размеры столбцов не совпадают
     */
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

    /**
     * Хелпер. показывает стрелочки, направления сортировки
     * @param <type> $field
     * @return <type>
     */
    protected function _arrowSort($field) {
        if (isset($this->_sort[$field])) {
            if(self::SORT_ASC == $this->_sort[$field]) {
                $img = 'desc';
            } else {
                $img = 'asc';
            }
        } else {
            $img = 'dot';
        }
        return '<img src="' . WEB_PREFIX .'Brill/img/'.$img.'.png" />';
    }
    /**
     * Строит шапку таблицы
     * @return string верстка щапки
     */
    public function buildHead (){
        $html = '<tr>';

        if ($this->viewIterator) {
            $html .= '<th><a href="'. Routing::constructUrl(array('nav' => array())).'">#</a></th>';
        }
        foreach ($this->viewCols as $i => $cell) {
            if ($this->viewSorting) {

                $html .= '<th>'.$this->_arrowSort($this->fields[$i]).'<a href="'. Routing::constructUrl(array('nav' => array('field' => $this->fields[$i], 'order' => (isset($this->_sort[$this->fields[$i]])) ? $this->_sort[$this->fields[$i]] : self::SORT_ASC ))).'" ajax="1">' . $this->headers[$i] . '</a></th>';
            } else {
                $html .= '<th>' . $this->headers[$i] . '</th>';
            }
        }

        if ($this->isOptions()) {
            $html .= '<th class="options">Опции</th>';
        }
        $html .= '</tr>';
        return $html;
    }

    /**
     * Строит тело таблицы
     * @return string верстка тела таблицы
     */
    public function buildBody (){
        $html = '';
        $idRow = 0;
        $cols = count($this->viewCols);
        if (!$cols) {
          $cols = count($this->fields);
        }
        if ($this->viewIterator) {
            $cols++;
        }
        if ($this->isOptions()) {
            $cols++;
        }
        if(empty($this->values)) {

            $html .= '<tr><td colspan="' . $cols . '" class="null_table">' . LNG_NULL_TABLE . '</td></tr>';
        } else {
            foreach ($this->values as $row) {
                $html .= '<tr>';
                if ($this->viewIterator) {
                    $html .= '<td>' . ($idRow + 1) . '</td>';
                }
                if ($this->separator &&  ($this->separatorValue != $row[$this->separator] || $idRow == 0)) {
                    $html .= '<tr><td colspan="' . $cols . '" class="separator_table">' . ($this->separatorHeader ? $row[$this->separatorHeader] : '') . '</td></tr>';
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
                if ($this->isOptions()) {
                    $html .= '<td class="options">';
                    
                    $html .= $this->buildOpt($row[$this->fields[0]]);
                    if (isset($this->_opts[self::OPT_EDIT])) {
                        $html .= '<a href="' . Routing::constructUrl(array('act' => 'edit'), false) . '?' .$this->fields[0].  '=' . $row[$this->fields[0]].'" ajax="1"><img src="' . WEB_PREFIX .'Brill/img/edit.png" /></a>';
                    }
                    if (isset($this->_opts[self::OPT_DEL])) {
                        $html .= '<a href="' . Routing::constructUrl(array('act' => 'del'), false) . '?' .$this->fields[0].  '=' . $row[$this->fields[0]].'" ajax_areusure="1"><img src="' . WEB_PREFIX .'Brill/img/del.png" /></a>';
                    }
                    
                    $html .= '</td>';
                }
                $idRow++;
                $html .= '</tr>';
            }
        }
        return $html;
    }

}