<?php


namespace App\controller;


use App\AbstractController;
use Biz\Terms\Service\TermsService;
use Illuminate\Support\Arr;
use support\Request;
use support\utils\ArrayToolkit;
use support\utils\Paginator;
use support\utils\TreeToolKit;

class Excel extends AbstractController
{

    const DEFAULT_PAGING_LIMIT = 1000;

    const DEFAULT_PAGING_OFFSET = 0;

    const MAX_PAGING_LIMIT = 5000;

    public function search(Request $request)
    {
        $conditions = ['id' => -1];
        $params = $request->get();
        $keywords = [];
        if (!empty($params['keywords'])) {
            unset($conditions['id']);
            $keywords = array_filter(array_unique(explode(',', $params['keywords'])));
            $keywords = $this->compareKeywords($keywords);
            asort($keywords);
        }

        if (!empty($params['delIds'])) {
            $conditions = $this->fillRemoveConditions($params['delIds'], $conditions);
        }

        $rows = [];
        foreach ($keywords as $keyword) {
            $conditions['codeLike'] = $this->keywordFormat($keyword);
            $items = $this->searchTerms($conditions);
            empty($items) && $items = [];
            $rows = array_merge($rows, $items);
        }

        $rows = ArrayToolkit::index($rows, 'id');
        $rows = array_values($rows);

        return $this->createSuccessJsonResponse([
            'items' => $rows,
            'keywords' => array_values($keywords)
        ]);
    }

    public function get(Request $request, $id)
    {
        return response($id);
    }

    protected function fillRemoveConditions($delIds, $conditions)
    {
        $delIds = explode(',', $delIds);
        $terms = $this->getTermsService()->getTermsInIds($delIds);
        $noIds = [];
        foreach ($terms as $term) {
            $explainTerms = $this->searchTerms(['codeLike' => $term['code']], false, ['id']);
            $explainTermIds = ArrayToolkit::column($explainTerms, 'id');
            if (!empty($explainTermIds)) {
                $noIds = array_merge($noIds, $explainTermIds);
            }
        }

        $conditions['noIds'] = array_unique($noIds);

        return $conditions;
    }
    protected function compareKeywords($keywords)
    {
        $currentKeyword = end($keywords);
        $prevKeyword = prev($keywords);
        if (strpos($currentKeyword, '.0') !== false) {
            $currentLevel = 1;
        } else {
            $currentLevel = count(explode('.', $currentKeyword));
        }

        $currentLevel = $this->getCatalogLevel($currentKeyword);
        $prevLevel = $this->getCatalogLevel($prevKeyword);

        if ($currentLevel > $prevLevel) {
            $currentParents = $this->getCatalogParents($currentKeyword);
            if (in_array($prevKeyword, $currentParents)) {
                return [$currentKeyword];
            }
        }

        $parents = [];
        foreach ($keywords as $keyword) {
            $parents += $this->getCatalogParents($keyword);
        }
        if (in_array($currentKeyword, $parents)) {
            return [$currentKeyword];
        }

        return $keywords;
    }

    protected function getCatalogLevel($catalog)
    {
        if (empty($catalog)) {
            return 0;
        }

        if (strpos($catalog, '.0') !== false) {
            return 1;
        }

        return count(explode('.', $catalog));
    }

    protected function getCatalogParents($currentCatalog)
    {
        $numbers = explode('.', $currentCatalog);
        $level = count($numbers);
        $parents = [];
        if ($level === 4) {
            array_pop($numbers);
            $parents[] = implode('.', $numbers);
            array_pop($numbers);
            $parents[] = implode('.', $numbers);
            array_pop($numbers);
            $parents[] = sprintf("%s.0", implode('.', $numbers));
        } elseif ($level === 3) {
            array_pop($numbers);
            $parents[] = implode('.', $numbers);//1.1
            array_pop($numbers);
            $parents[] = sprintf("%s.0", implode('.', $numbers));
        } elseif ($level === 2) {
            array_pop($numbers);
            $parents[] = sprintf("%s.0", implode('.', $numbers));
        }

        return $parents;
    }

    protected function keywordFormat($keyword)
    {
        $keywords = explode('.', $keyword);
        if (strpos($keyword, '.0') === false) {
            $keywords[0] .= '.0';
        }


        return implode('.', $keywords);
    }

    protected function searchTerms($conditions, $filter = true, $columns = [])
    {
        $sort['code'] = 'ASC';
        $items = $this->getTermsService()->searchTerms($conditions, $sort, 0, PHP_INT_MAX, $columns);

        return $filter ? $this->filterTerms($items) : $items;
    }

    protected function filterTerms($items)
    {
        $rows = [];
        foreach ($items as $item) {
            $row['id'] = $item['id'];
            if ($item['depth'] == 1) {
                $row['catalog'] = $item['catalog'];
                $row['catalogName'] = $item['name'];
                $row['flowGroupCode'] = '';
                $row['flowGroupCodeName'] = '';
                $row['flowCode'] = '';
                $row['flowCodeName'] = '';
                $row['activityCode'] = '';
                $row['activityCodeName'] = '';
            } elseif ($item['depth'] == 2) {
                $row['catalog'] = '';
                $row['catalogName'] = '';
                $row['flowGroupCode'] = $item['catalog'];
                $row['flowGroupCodeName'] = $item['name'];
                $row['flowCode'] = '';
                $row['flowCodeName'] = '';
                $row['activityCode'] = '';
                $row['activityCodeName'] = '';
            } elseif ($item['depth'] == 3) {
                $row['catalog'] = '';
                $row['catalogName'] = '';
                $row['flowGroupCode'] = '';
                $row['flowGroupCodeName'] = '';
                $row['flowCode'] = $item['catalog'];
                $row['flowCodeName'] = $item['name'];
                $row['activityCode'] = '';
                $row['activityCodeName'] = '';
            } else {
                $row['catalog'] = '';
                $row['catalogName'] = '';
                $row['flowGroupCode'] = '';
                $row['flowGroupCodeName'] = '';
                $row['flowCode'] = '';
                $row['flowCodeName'] = '';
                $row['activityCode'] = $item['catalog'];
                $row['activityCodeName'] = $item['name'];
            }

            $rows[] = $row;
        }

        return $rows;
    }

    /**
     * @return TermsService
     */
    protected function getTermsService()
    {
        return $this->createService('Terms:TermsService');
    }
}