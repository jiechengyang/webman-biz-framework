<?php

namespace Biz\Terms\Dao;

use Codeages\Biz\Framework\Dao\AdvancedDaoInterface;

interface TermsDao extends AdvancedDaoInterface
{
    public function truncate();

    public function getTermsByIds($ids);
}
