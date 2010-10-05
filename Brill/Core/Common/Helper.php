<?php
/**
 * Helper
 *
 * ����� ��������� ��������
 */

class Helper {
    /**
     * ������ ������ ���� � ����
     * @param string $name ���� � �����
     * @param string $text ��� ����������
     * @param boolean $clear ������ �� ������� ����� ����� �������
     * @throw FileException
     */
    public static function logFileWrite($name, $text, $clear = false){
        $mode = ($clear) ? 'w' : 'a';
        $filename = DIR_PATH . '/logs/'.$type.'.log';
        if (!$handle = fopen($filename, $mode)) {
            throw new FileException('�� ���� ������� ����(' . $filename. ') �� ������');
        }
        
        $text .= "\n";
        if (fwrite($handle, $text) === false) {
           throw new FileException('�� ���� �������� � ����(' . $filename. ')');
        }
        fclose($handle);
    }
}
?>
