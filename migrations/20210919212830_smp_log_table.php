<?php

use Phpmig\Migration\Migration;

class SmpLogTable extends Migration
{
    /**
     * Do the migration
     */
    public function up()
    {
        $container = $this->getContainer();
        $container['db']->exec("CREATE TABLE `smp_log` (
            `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '系统日志ID',
            `userId` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '操作人ID',
            `module` varchar(32) NOT NULL COMMENT '日志所属模块',
            `action` varchar(255) NOT NULL COMMENT '日志所属操作类型',
            `message` text NOT NULL COMMENT '日志内容',
            `data` text COMMENT '日志数据',
            `ip` varchar(255) NOT NULL COMMENT '日志记录IP',
            `createdTime` int(10) unsigned NOT NULL COMMENT '日志发生时间',
            `level` char(10) NOT NULL COMMENT '日志等级',
            PRIMARY KEY (`id`),
            KEY `userId` (`userId`),
            KEY `idx_module_action` (`module`,`action`),
            KEY `idx_action` (`action`)
          ) ENGINE=InnoDB DEFAULT CHARSET=utf8
          ");
    }

    /**
     * Undo the migration
     */
    public function down()
    {
        $container = $this->getContainer();
        $container['db']->exec("DROP TABLE IF EXISTS `smp_log`");
    }
}
