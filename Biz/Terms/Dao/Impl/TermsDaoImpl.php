<?php

namespace Biz\Terms\Dao\Impl;

use Codeages\Biz\Framework\Dao\AdvancedDaoImpl;
use Biz\Terms\Dao\TermsDao;

class TermsDaoImpl extends AdvancedDaoImpl implements TermsDao
{

    protected $table = 'smp_terms';

    public function truncate()
    {
        $this->db()->executeUpdate("TRUNCATE table {$this->table};");
    }

    protected function createQueryBuilder($conditions)
    {
//        if (isset($conditions['codeLike'])) {
//            $conditions['orCode'] = trim($conditions['codeLike'], '.');
//            $conditions['codeOrLike'] = $conditions['codeLike'];
//            unset($conditions['codeLike']);
//        }

        return parent::createQueryBuilder($conditions);
    }

    public function declares()
    {
        return [
            'serializes' => [
            ],
            'orderbys' => [
                'id',
                'createdTime',
                'depth',
                'code',
            ],
            'conditions' => [
                'id = :id',
                'id > :id_GT',
                'id IN ( :ids)',
                'id NOT IN ( :noIds)',
                'createdTime >= :startTime',
                'createdTime <= :endTime',
                'type = :type',
                'type PRE_LIKE (:typeLike)',
                'code = :code',
                'code PRE_LIKE (:codeLike)',
                '(code PRE_LIKE (:codeOrLike) OR code = (:orCode) )',
                'parentCode = :parentCode',
                'parentCode PRE_LIKE (:parentCodeLike)',
                'name = :name',
                'name LIKE (:nameLike)',
            ],
            'timestamps' => [
                'createdTime',
            ],
        ];
    }
}
