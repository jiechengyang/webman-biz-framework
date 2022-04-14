<?php

namespace Biz\SystemLog\Exception;

use support\exception\AbstractException;

class SystemLogException extends AbstractException 
{
    public function __construct($code)
    {
        $this->setMessages();
        parent::__construct($code);
    }

    public function setMessages()
    {
        $this->messages = [
        
        ];
    }

}
