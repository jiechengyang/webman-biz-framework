<?php


namespace gii\template\easy;


use Codeages\Biz\Framework\Context\Biz;
use gii\template\BaseProcessor;
use gii\template\TemplateInterface;
use function DI\value;

class EasyProcessor extends BaseProcessor
{
    private $templates = [
        'daoInterface' => DaoInterfaceTemplate::class,
        'daoImpl' => DaoImpTemplate::class,
        'serviceInterface' => ServiceInterfaceTemplate::class,
        'serviceImpl' => ServiceImplTemplate::class,
        'exception' => ExceptionTemplate::class
    ];

    public function getTemplates()
    {
       return $this->templates;
    }

    public function render(array $args = [])
    {
        $args['bizId'] = ucwords($args['bizId']);
        $path = dirname(dirname(dirname(__DIR__))) . DIRECTORY_SEPARATOR . 'Biz' . DIRECTORY_SEPARATOR . $args['bizId'];
        if (is_dir($path)) {
            throw new \Exception("the {$args['bizId']} biz already exist!");
        }

        $args['rootPath'] = $path;
        empty($args['tableName']) && $args['tableName'] = $this->parseTableName($args['bizId'], $args['prefix'] ?? '');
        try {
            $this->renderPath($path);
            foreach ($this->getTemplates() as $template) {
                if (class_exists($template)) {
                    /** @var $templateObj TemplateInterface */
                    $templateObj = new $template($this->getBiz());
                    list($filename, $content) = $templateObj->getContext($args);
                    if (!empty($filename)) {
                        file_put_contents($filename, $content);
                    }
                }
            }

            return $path;
        } catch (\Exception $e) {
            shell_exec("rm -rf {$path}/*");
            throw $e;
        }
    }

    protected function renderPath($root)
    {
        mkdir($root);
        foreach ($this->defaultSubPaths as $path => $subPath) {
             mkdir($root . DIRECTORY_SEPARATOR . $path);
             foreach ($subPath as $value) {
                 mkdir($root . DIRECTORY_SEPARATOR . $path . DIRECTORY_SEPARATOR . $value);
             }
        }
    }

    protected function parseTableName($bizId, $prefix = '')
    {
        $words = preg_split("/[\s]/", $bizId);
        if ($prefix && !empty($this->getBiz()['db.options']['prefix'])) {
            $prefix = $this->getBiz()['db.options']['prefix'];
        }

        return $prefix . strtolower(implode('_', $words));
    }
}