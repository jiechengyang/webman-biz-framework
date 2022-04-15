<?php

namespace Biz\Terms\Service\Impl;

use Biz\BaseService;

use Biz\Terms\Service\TermsService;
use Biz\Terms\Dao\TermsDao;

class TermsServiceImpl extends BaseService implements TermsService
{
    public function countTerms(array $conditions = [])
    {
        return $this->getTermsDao()->count($conditions);
    }

    public function searchTerms(array $conditions = [], array $orderby = [], $start, $limit, array $columns = [])
    {
        return $this->getTermsDao()->search($conditions, $orderby, $start, $limit, $columns);
    }

    public function truncate()
    {
        return $this->getTermsDao()->truncate();
    }

    public function getTermsById($id)
    {
        return $this->getTermsDao()->get($id);
    }

    public function batchCreateTerms(array $items)
    {
        if (empty($items)) {
            return false;
        }

        $rows = [];
        $topCatalogs = $this->generateTopCatalogs();
        foreach ($items as &$item) {
            $catalog = $item['catalog'];
            if (in_array($catalog, $topCatalogs)) {
                $item['code'] = $catalog;
                $item['depth'] = 1;
                $item['parentCode'] = '0';
            } else {
                $levels = explode('.', $catalog);
                $levels[0] .= '.0';
                $item['code'] = implode('.', $levels);
                $depth = count($levels);
                $item['depth'] = $depth;
                array_pop($levels);
                $item['parentCode'] = implode('.', $levels);
            }
        }

        try {
            $this->beginTransaction();
            $this->getTermsDao()->batchCreate($items);
            $this->commit();
            return true;
        } catch (\Exception $e) {
            $this->rollback();
            throw $e;
        }

    }

    public function createTerms(array $fields)
    {

    }

    public function updateTerms($id, array $fields)
    {

    }

    public function deleteTermsById($id)
    {

    }

    protected function generateLevels($n = 20)
    {
        $items = [];
        for ($i = 1; $i <= $n; $i++) {
            $sk = $i <= 9 ? sprintf('%s.0', $i) : $i;
            $values = [];
            for ($j = 1; $j <= 9; $j++) {
                $key = sprintf("%s.%s", $i, $j);
                $values2 = [];
                for ($j2 = 1; $j2 <= 9; $j2++) {
                    $key2 = sprintf("%s.%s", $key, $j2);
                    $values2[] = $key2;
                    $values3 = [];
                    for ($j3 = 1; $j3 <= 9; $j3++) {
                        $key3 = sprintf("%s.%s", $key2, $j3);
                        $values3[] = $key3;
                    }
                    $items[$key2] = $values3;
                }
                $items[$key] = $values2;
                $values[] = $key;
            }
            $items[$sk] = $values;
        }

        ksort($items, SORT_NUMERIC);

        return $items;
    }

    protected function generateTopCatalogs($n = 20)
    {
        $items = [];
        for ($i = 1; $i <= $n; $i++) {
            $items[] = sprintf("%s.0", $i);
        }

        return $items;
    }

    /**
     * @return TermsDao
     */
    protected function getTermsDao()
    {
        return $this->createDao('Terms:TermsDao');

    }

}
