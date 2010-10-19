<?php

/**
 * Description of oTableExt
 *
 * @author almaz
 */

class oTableExt extends oTable {
    //put your code here
    /**
     * Строит таблицу
     * @param string $idCss
     * @return string
     */
    public function buildHtml($idCss = false) {
        $html = '<table '.($idCss ? 'id="'.$idCss.'" ' : '').'border="0" cellpadding="0" cellspacing="0" class="tableExt">';
            $html .= '<tr><th class="table_chk"></th>';
            foreach ($this->viewCols as $i => $cell) {
                $html .= '<th>' . $this->headers[$i] . '</th>';
            }
            $html .= '</tr>';
            $idRow = 0;
        if(empty($this->values)) {
            $html .= '<tr><td colspan="'.count($this->fields).'" class="null_table">' . LNG_NULL_TABLE . '</td></tr>';
        } else {
            foreach ($this->values as $row) {
                $html .= '<tr><td><input type="checkbox" name="table_chk[]" value="" /></td>';
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
