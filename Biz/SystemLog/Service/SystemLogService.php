<?php

namespace Biz\SystemLog\Service;

interface SystemLogService
{
    /**
     * 记录一般日志.
     *
     * @param string $module  模块
     * @param string $action  操作
     * @param string $message 记录的详情
     */
    public function info($module, $action, $message, array $params = null);

    /**
     * 记录警告日志.
     *
     * @param string $module  模块
     * @param string $action  操作
     * @param string $message 记录的详情
     */
    public function warning($module, $action, $message, array $params = null);

    /**
     * 记录错误日志.
     *
     * @param string $module  模块
     * @param string $action  操作
     * @param string $message 记录的详情
     */
    public function error($module, $action, $message, array $params = null);

    /**
     * 日志搜索
     *                 如array(
     *                         'level'=>'info|warning|error',
     *                      'nickname'=>'xxxxx',
     *                      'startDateTime'=> 'xxxx-xx-xx xx:xx',
     *                      'endDateTime'=> 'xxxx-xx-xx xx:xx'
     *                 );.
     *
     * @param array $conditions 搜索条件，
     * @param array $sort       按什么排序, 暂只提供'created'
     * @param int   $start      开始行数
     * @param int   $limit      返回最多行数
     * @param array   $columns  字段
     *
     * @return array 多维数组
     */
    public function searchLogs($conditions, $sort, $start, $limit, array  $columns = []);

}
