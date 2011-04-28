<?php
/**
 * Helper
 *
 * Класс различных хелперов
 */

class Helper {
    /**
     * Делает запись лога в файл
     * @param string $name путь к файлу
     * @param string $text что записываем
     * @param boolean $clear делать ли очистку файла перед записью
     * @throw FileException
     */
    public static function logFileWrite($name, $text, $clear = false){
        $mode = ($clear) ? 'w' : 'a';
        $filename = DIR_PATH . '/logs/'.$type.'.log';
        if (!$handle = fopen($filename, $mode)) {
            throw new FileException('Не могу открыть файл(' . $filename. ') на запись');
        }
        
        $text .= "\n";
        if (fwrite($handle, $text) === false) {
           throw new FileException('Не могу записать в файл(' . $filename. ')');
        }
        fclose($handle);
    }
    
    
    
    static function logwrite($type, $str){
        $filename = 'logs/'.$type.'_logs';
        if (!$handle = fopen($filename, 'a')) return false;
        $time = date("Y.m.d H:i:s");
        $str = $time.'  '.$str.".\n";
        if (fwrite($handle, $str) === FALSE) {
           return false;
        }
        fclose($handle);
    return true;
}
}
?>