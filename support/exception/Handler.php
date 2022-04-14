<?php
/**
 * This file is part of webman.
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the MIT-LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @author    walkor<walkor@workerman.net>
 * @copyright walkor<walkor@workerman.net>
 * @link      http://www.workerman.net/
 * @license   http://www.opensource.org/licenses/mit-license.php MIT License
 */

namespace support\exception;

use support\utils\ExceptionUtil;
use Webman\Http\Request;
use Webman\Http\Response;
use Throwable;
use Webman\Exception\ExceptionHandler;

/**
 * Class Handler
 * @package support\exception
 */
class Handler extends ExceptionHandler
{
    public $dontReport = [
    ];

    public $openHttpCodes = [
        400,
        401,
        422,
        201,
        403,
        500
    ];

    public function report(Throwable $exception)
    {
        parent::report($exception);
    }

    public function render(Request $request, Throwable $exception): Response
    {
        list($httpCode, $error) = ExceptionUtil::getErrorAndHttpCodeFromException($exception);
        if ($this->requestIsJson($request)) {
            $this->_debug && $error['traces'] = (string)$exception;
            $httpCode = $this->rewriteHttpCode($httpCode);
            return new Response($httpCode, ['Content-Type' => 'application/json'],
                json_encode($error, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES));
        }

        $error = $this->_debug ? nl2br((string)$exception) : 'Server internal error';
        return new Response($httpCode, [], $error);
    }

    protected function rewriteHttpCode($httpCode)
    {
        if (!$this->_debug && !in_array($httpCode, $this->openHttpCodes)) {
            $httpCode = 200;
        }

        return  $httpCode;
    }

    protected function requestIsJson(Request $request)
    {
        return $request->expectsJson()
            || false !== strpos(strtolower($request->header('content-type')), 'application/json')
            || false !== strpos(strtolower($request->header('content-type')), 'text/json');
    }

}