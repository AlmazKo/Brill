<?php
/**
 * Description of Acl
 *
 * @author Alexander
 */
class st_Lib extends Lib {
    const
        //тип интерфейсов для доступа
        INTERFACE_SIMPLE = 0,
        INTERFACE_YA_XAML = 1,
        INTERFACE_GOOGLE = 2,
        INTERFACE_PROXY = 4;

    const INTERFASE_LOCALHOST = '127.0.0.1';

    public static function isLocal ($interface) {
        return  '127.0.0.1' != $interface || 'localhost' != $interface;
    }
    
    /**
     * Получить интерфейс
     * @param int $type какой тип интерфеса необходим
     * @return array & failure
     */
    public static function getInterface($type = self::ACCESS_SIMPLE) {
        switch ($type) {
            case self::INTERFACE_YA_XAML:
                $hostId = 1;
                $result = DBExt::getOneRowSql(Stmt::prepare2(st_StmtDaemon::GET_INTERFACE_YANDEX_XML, array('host_id' => $hostId)));
                break;
            
            case self::INTERFACE_GOOGLE:
                $hostId = 2;
                $result = DB::execute(se_StmtDaemon::prepare(st_StmtDaemon::GET_INTERFACE_FOR_GOOGLE))->fetchALL(PDO::FETCH_ASSOC);   ;
                $result = $result[0];
                break;
            
            default:
                $hostId = 0;
                $result = DBExt::getOneRowSql(Stmt::prepare2(st_StmtDaemon::GET_INTERFACE_SIMPLE, array('host_id' => $hostId)));
        }

        if ($result){
            $interfaceId = array_shift($result);
            DBExt::execute(Stmt::prepare2(st_StmtDaemon::SET_INTERFACE_USED), array(
                ':interface_id' => (int)$interfaceId,
                ':host_id' => (int)$hostId
                ));
            echo "\n -------- IP: " . $result['interface'] ."\n";
            return $result;
        } else {
            throw new LimitInterfacesException('закончились IP');
        }
    }
}
