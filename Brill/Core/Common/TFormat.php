<?php
/*
 * TFormat
 *
 * класс формирующий вывод текста
 */

class TFormat {

    /**
     * Форматирует число. Предполагается, что вводятся секунды
     * @param int $fNumber Количество цифр после запятой
     * @param string $timing В каком виде 's' - секунды, 'ms' - милисекунды
     */
    public static function timer($value, $fNumber = 2) {
        $result = '';
        $timing = 's';
        if ($value < 1) {
            $value *= 1000;
            $timing = 'ms';
        } else if ($value >= 60 ) {
            $min = (int) $value / 60;
            $result .= $min . ' min ';
            $value -= $min * 60;
        }

        $result .= sprintf('%01.' . $fNumber . 'f', $value);
        return $result . ' ' . $timing;
    }

    /**
     * Формутирует вывод времени
     * @param int $time
     * @return string
     */
    public static function fullTime($time = null) {
        return ($time) ? date("Y.m.d H:i:s", $time): date("Y.m.d H:i:s");
    }

   /**
     * Формутирует вывод времени
     * @param int $time
     * @return string
     */
    public static function time($time = null) {
        return ($time) ? date("H:i:s", $time): date("H:i:s");
    }
    /**
     * Формутирует вывод даты
     * @param int $time
     * @return string
     */
    public static function date($time = null) {
        return ($time) ? date("Y.m.d", $time): date("Y.m.d");
    }

    /**
     * форматирует вывод лог файла для HTML
     * @param string $title
     * @param string $text
     * @param boolean $block
     * @param string $color
     * @return string
     */
    public static function htmlMessageLog($title, $descr, $block, $color) {

        if ($block) {
            $text = '<fieldset style="border: 1px solid ' . $color . ';"><legend style="color: ' . $color . '; font-weight: bold;"> '
            . $title. '  </legend><pre style="color: #333;">'.  $descr
            . '</pre></fieldset>';
        } else {
            $text = '<span style="color: ' . $color . ';">['. $title . ']</span> ' . '<span style="color: #333;">'.  $descr . '</span><br />';
        }
        return $text;

    }


     /**
     * форматирует вывод лог файла для HTML
     * @param string $title
     * @param string $text
     * @return string
     */
    public static function txtMessageLog($title, $descr) {
        return    $text = '[' . self::time() . '] ' . $title . ': ' . $text;
    }

}
?>
