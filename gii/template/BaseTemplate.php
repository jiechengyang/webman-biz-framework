<?php


namespace gii\template;


use Codeages\Biz\Framework\Context\Biz;

class BaseTemplate
{
    private $biz;

    public function __construct(Biz $biz)
    {
        $this->biz = $biz;
    }

    /**
     * @return Biz
     */
    public function getBiz(): Biz
    {
        return $this->biz;
    }
}