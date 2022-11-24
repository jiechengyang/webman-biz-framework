<?php

namespace Biz;

use Biz\GB28281\DeviceTypeEnum;
use support\exception\AbstractException;
use support\utils\ArrayToolkit;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Codeages\Biz\Framework\Event\Event;
use Monolog\Logger;

class BaseService extends \Codeages\Biz\Framework\Service\BaseService
{
    private $lock = null;

    /**
     * @param $deviceId
     * @return array
     */
    protected function getDeviceTypeCodeByDeviceId($deviceId)
    {
        $code = substr($deviceId, 10, 3);
        $typeName = DeviceTypeEnum::videoRecorderEnums($code);
        if (!$typeName) {
            $typeName = DeviceTypeEnum::cameraEnums($code);
        }

        return [$code, $typeName];
    }

    protected function createDao($alias)
    {
        return $this->biz->dao($alias);
    }

    protected function createService($alias)
    {
        return $this->biz->service($alias);
    }

        /**
     * @return EventDispatcherInterface
     */
    private function getDispatcher()
    {
        return $this->biz['dispatcher'];
    }

    /**
     * @param string      $eventName
     * @param Event|mixed $subject
     *
     * @return Event
     */
    protected function dispatchEvent($eventName, $subject, $arguments = [])
    {
        if ($subject instanceof Event) {
            $event = $subject;
        } else {
            $event = new Event($subject, $arguments);
        }

        return $this->getDispatcher()->dispatch($eventName, $event);
    }

    protected function beginTransaction()
    {
        $this->biz['db']->beginTransaction();
    }

    protected function commit()
    {
        $this->biz['db']->commit();
    }

    protected function rollback()
    {
        $this->biz['db']->rollback();
    }

    /**
     * @return Logger
     */
    protected function getLogger()
    {
        return $this->biz['logger'];
    }

    protected function createNewException($e)
    {
        if ($e instanceof AbstractException) {
            throw $e;
        }

        throw new \Exception();
    }
}