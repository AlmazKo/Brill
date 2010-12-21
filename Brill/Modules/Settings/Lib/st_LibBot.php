<?php
/**
 * Description of Acl
 *
 * @author Alexander
 */
class st_LibBot extends Lib {
    const
        //тип интерфейсов для доступа
        ACCESS_SIMPLE = 1,
        ACCESS_YA_XAML = 2,
        ACCESS_PROXY = 4;
    
    public function __construct() {
        $module = Genera::$loadedModules['SEParsing'];
        require_once $module->pathModels . 'st_Hosts.php';
        require_once $module->pathModels . 'st_InterfaceCountCallToday.php';
        require_once $module->pathModels . 'st_Interfaces.php';
        require_once $module->pathModels . 'st_LimitsIpForHosts.php';
        require_once $module->pathModels . 'sep_YandexAccesses.php';
    }

    public static function get($typeAccess = self::ACCESS_SIMPLE) {
        switch ($typeAccess) {
            case ACCESS_YA_XAML:
                $hostId = 2;
                $result = DBExt::getOneRowSql(Stmt::prepare2(se_StmtDaemon::GET_INTERFACE_YANDEX_XML, array('host_id' => $hostId)));
                break;
            
            default:
                $hostId = 1;
                $result = DBExt::getOneRowSql(Stmt::prepare2(se_StmtDaemon::GET_INTERFACE_YANDEX_XML, array('host_id' => $hostId)));
        }

        if ($result){
            $interfaceId = array_shift($result);
            DBExt::query(Stmt::prepare2(se_StmtDaemon::SET_INTERFACE_USED, array(
                'interface_id' => $interfaceId,
                'host_id' => $hostId
                )));
            return $result;
        } else {
            Log::warning('Закончились IP');
        }
    }
}
