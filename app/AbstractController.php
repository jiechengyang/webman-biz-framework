<?php


namespace App;


use Biz\User\CurrentUser;
use Codeages\Biz\Framework\Context\Biz;
use support\bootstrap\Container;
use support\Request;

class AbstractController
{
    /**
     * 接口成功业务码
     */
    const BIS_SUCCESS_CODE = 0;

    /**
     * 未匹配到业务错误码的统一业务码
     */
    const BIS_FAILED_CODE = -1;

    /**
     *  请求方法错误业务码
     */
    const ERROR_CODE_METHOD_FAILED = 4041001;

    /**
     * 无权限访问业务码
     */
    const ERROR_CODE_NOT_ALLOW_FAILED = 4031001;

    /**
     * post 请求 必要参数缺失
     */
    const ERROR_CODE_POST_DATA_FAILED = 4001001;

    /**
     * get 请求 必要参数缺少或错误
     */
    const ERROR_CODE_GET_DATA_FAILED = 4001002;

    /**
     *
     */
    const DEFAULT_PAGING_LIMIT = 10;
    /**
     *
     */
    const DEFAULT_PAGING_OFFSET = 0;
    /**
     *
     */
    const MAX_PAGING_LIMIT = 500;

    /**
     *
     */
    const PREFIX_SORT_DESC = '-';


    public function beforeAction(Request $request)
    {
    }

    public function afterAction(Request $request, $response)
    {

    }


    /**
     *
     * @param null $data
     * @param string $message
     * @param int $statusCode
     * @return \support\Response
     */
    protected function createSuccessJsonResponse($data = null, $message = 'success', $statusCode = 200)
    {
        return json(['code' => self::BIS_SUCCESS_CODE, 'data' => $data, 'message' => $message], JSON_UNESCAPED_UNICODE, $statusCode);
    }

    /**
     * @param string $message
     * @param int $statusCode
     * @return \support\Response
     */
    protected function createFailJsonResponse($message = 'failed', $statusCode = 200)
    {
        return json(['code' => self::BIS_FAILED_CODE, 'data' => null, 'message' => $message], $statusCode);
    }

    /**
     * @param Request $request
     * @return array
     */
    protected function getOffsetAndLimit(Request $request, $offsetKey = 'offset', $limitKey = 'limit')
    {
        $offset = $request->get($offsetKey, self::DEFAULT_PAGING_OFFSET);
        $limit = $request->get($limitKey, self::DEFAULT_PAGING_LIMIT);

        return [$offset, $limit > self::MAX_PAGING_LIMIT ? self::MAX_PAGING_LIMIT : $limit];
    }

    /**
     * @param Request $request
     * @return array
     */
    protected function getSort(Request $request)
    {
        $sortStr = $request->get('sort');

        if ($sortStr) {
            $explodeSort = explode(',', $sortStr);

            $sort = [];
            foreach ($explodeSort as $part) {
                $prefix = substr($part, 0, 1);
                $field = str_replace(self::PREFIX_SORT_DESC, '', $part);
                if (self::PREFIX_SORT_DESC == $prefix) {
                    $sort[$field] = 'DESC';
                } else {
                    $sort[$field] = 'ASC';
                }
            }

            return $sort;
        }

        return [];
    }

    /**
     * @param $objects
     * @param $total
     * @param $offset
     * @param $limit
     * @return array
     */
    protected function makePagingObject($objects, $total, $offset, $limit)
    {
        return [
            'rows' => $objects,
            'paging' => [
                'total' => $total,
                'offset' => $offset,
                'limit' => $limit,
            ],
        ];
    }

    /**
     * @return CurrentUser
     */
    protected function getCurrentUser()
    {
        $biz = $this->getBiz();

        return $biz['user'];
    }

    /**
     *
     * @return Biz
     */
    final protected function getBiz()
    {
        return Container::get(Biz::class);
    }


    protected function createService($serviceAlias)
    {
        return $this->getBiz()->service($serviceAlias);
    }
}