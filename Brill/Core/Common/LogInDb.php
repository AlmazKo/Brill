<?php
/**
 * LogInDb - запись логов в базу
 * требует таблицу
 *
 * @author almaz
 */
class LogInDb extends Log{
    /**
     *
     * @param object $class
     * @param <type> $error
     */
    public static function notice ($class, $error) {
        $className = get_class($class);
        DB::query("insert into mErrors (`class`, `descr`, `date`) values('$className', '$error', now())");
    }
   
}
