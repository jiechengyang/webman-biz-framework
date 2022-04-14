<?php

use Phpmig\Migration\Migration;

class SmpTermsTable extends Migration
{
    /**
     * Do the migration
     */
    public function up()
    {
        $container = $this->getContainer();
        $container['db']->exec("CREATE TABLE `smp_terms` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `name` varchar(255) NOT NULL COMMENT '条款名称',
  `childrenNum` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '下级数量',
  `depth` int(11) NOT NULL DEFAULT '1' COMMENT '层级',
  `seq` int(11) NOT NULL DEFAULT '0' COMMENT '索引',
  `description` text COMMENT '备注',
  `code` varchar(255) NOT NULL DEFAULT '' COMMENT '编码',
  `catalog` varchar(255) DEFAULT NULL COMMENT '类型',
  `parentCode` varchar(255) NOT NULL COMMENT '父编码',
  `createdTime` int(11) unsigned NOT NULL COMMENT '创建时间',
  `updateTime` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '最后更新时间',
  PRIMARY KEY (`id`),
  UNIQUE KEY `code` (`code`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='流程框架条款';");
    }

    /**
     * Undo the migration
     */
    public function down()
    {
        $container = $this->getContainer();
        $container['db']->exec("DROP TABLE IF EXISTS `smp_terms`");
    }
}
