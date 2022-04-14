<?php

namespace Biz\SystemLog\Dao\Impl;

use Codeages\Biz\Framework\Dao\AdvancedDaoImpl;
use Biz\SystemLog\Dao\SystemLogDao;

class SystemLogDaoImpl extends AdvancedDaoImpl implements SystemLogDao
{

    protected $table = 'smp_log';

    public function declares()
    {
        return [
            'serializes' => [
                array('data' => 'json'),
           ],
            'orderbys' => [
                'id',
                'createdTime',
           ],
            'conditions' => [
                'module = :module',
                'action = :action',
                'level = :level',
                'userId = :userId',
                'createdTime > :startDateTime',
                'createdTime < :endDateTime',
                'createdTime >= :startDateTime_GE',
                'createdTime <= :startDateTime_LE',
                'userId IN ( :userIds )',
                'action IN ( :actions )',
           ],
            'timestamps' => [
                'createdTime',
           ],
        ];
    }
}
