<?php


namespace gii\template;


use Codeages\Biz\Framework\Context\Biz;

abstract class BaseProcessor
{
    protected $defaultSubPaths = [
        'Dao' => ['Impl'],
        'Exception' => [],
        'Service' => ['Impl'],
        'Job' => [],
        'Event' => [],
    ];

    /**
     * @var Biz
     */
    private $biz;

    public function __construct(Biz $biz)
    {
        $this->biz = $biz;
    }

    abstract public function render(array $args = []);

    abstract public function getTemplates();

    protected function getBiz()
    {
        return $this->biz;
    }
}