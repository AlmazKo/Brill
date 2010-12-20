<?php
/**
 * Description of Acl
 *
 * @author Alexander
 */
class se_Lib extends Lib {

    public function __construct() {
        $module = Genera::$loadedModules['SEParsing'];
        require_once $module->pathModels . 'sep_Hosts.php';
        require_once $module->pathModels . 'sep_InterfaceCountCallToday.php';
        require_once $module->pathModels . 'sep_Interfaces.php';
        require_once $module->pathModels . 'sep_LimitsIpForHosts.php';
        require_once $module->pathModels . 'sep_YandexAccesses.php';
    }

    public static function getIp () {
        $result = DBExt::getOneRowSql(Stmt::prepare2(se_StmtDaemon::GET_INTERFACE_YANDEX_XML, array('host_id' => '1')));
        if ($result){
            DBExt::query(Stmt::prepare2(se_StmtDaemon::GET_IP_USED, array(
                'interface_id' => $result['id'],
                'host_id' => '1'
                )));
            return $result['interface'];
        } else {
            Log::warning('Закончились IP');
        }
    }
}
