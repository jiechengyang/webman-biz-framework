<?php

namespace Biz\SystemLog\Service\Impl;

use Biz\BaseService;

use Biz\SystemLog\Service\SystemLogService;
use Biz\SystemLog\Dao\SystemLogDao;

class SystemLogServiceImpl extends BaseService implements SystemLogService 
{

    public function info($module, $action, $message, array $params = null)
    {
        return $this->addLog('info', $module, $action, $message, $params);
    }

    public function warning($module, $action, $message, array $params = null)
    {
        return $this->addLog('warning', $module, $action, $message, $params);
    }

    public function error($module, $action, $message, array $params = null)
    {
        return $this->addLog('error', $module, $action, $message, $params);
    }

    public function searchLogs($conditions, $sort, $start, $limit, array  $columns = [])
    {
        return $this->getSystemLogDao()->search($conditions, $sort, $start, $limit, $columns);
    }

    protected function addLog($level, $module, $action, $message, array $params = null)
    {
        return $this->getSystemLogDao()->create(
            [
                'module' => $module,
                'action' => $action,
                'message' => $message,
                'data' => empty($params) ? '' : is_string($params) ? $params : json_encode($params),
                'userId' => $params['userId'] ?? 1 ,
                'ip' => $params['currentIp'] ?? '',
                'createdTime' => time(),
                'level' => $level,
            ]
        );
    }

    /**
     * @return SystemLogDao
     */
    protected function getSystemLogDao()
    {
        return $this->createDao('SystemLog:SystemLogDao');

    }
}
