<?php


namespace App\controller;


use App\AbstractController;
use Biz\Terms\Service\TermsService;
use support\Request;
use support\utils\Paginator;
use support\utils\TreeToolKit;

class Excel extends AbstractController
{

    const DEFAULT_PAGING_LIMIT = 1000;

    const DEFAULT_PAGING_OFFSET = 0;

    const MAX_PAGING_LIMIT = 5000;

    public function search(Request $request)
    {
        $conditons = ['id' => -1];
        $params = $request->get();
        $keywords = [];
        if (!empty($params['keywords'])) {
            unset($conditons['id']);
            $keywords = array_filter(array_unique(explode(',', $params['keywords'])));
            asort($keywords);
        }

        $rows = [];
        var_dump($keywords);
        foreach ($keywords as $keyword) {
            $keyword = (int)$keyword;
            $conditons['codeLike'] = $keyword . '.';
            $items = $this->searchTerms($conditons);
            empty($items) && $items = [];
            $rows = array_merge($rows, $items);
        }

        return $this->createSuccessJsonResponse([
            'items' => $rows,
            'keywords' => implode(',', $keywords)
        ]);
    }

    public function get(Request $request, $id)
    {
        return response($id);
    }

    protected function searchTerms($conditions)
    {
        $sort['code'] = 'ASC';
        $items = $this->getTermsService()->searchTerms($conditions, $sort, 0, PHP_INT_MAX);
        foreach ($items as $index => &$item) {
            $item['no'] = $index + 1;
        }

        return $this->filterTerms($items);
    }

    protected function filterTerms($items)
    {
        $rows = [];
        foreach ($items as $item) {
            $row['id'] = $item['id'];
            $row['no'] = $item['no'];
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