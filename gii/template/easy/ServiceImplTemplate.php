<?php

namespace gii\template\easy;

use gii\template\TemplateInterface;

class ServiceImplTemplate implements TemplateInterface
{

    public function getContext(array $args = [])
    {
        $service = $args['bizId'];
        $className = "{$service}ServiceImpl";
        $daoClass = "{$service}Dao";
        $phpCode = "<?php\n"
            . "\n"
            . "namespace Biz\\{$service}\\Service\\Impl;\n"
            . "\n"
            . "use Biz\\BaseService;\n"
            . "\n"
            . "use Biz\\{$service}\\Service\\{$service}Service;\n"
            . "use Biz\\{$service}\\Dao\\{$daoClass};\n"
            . "\n"
            . "class {$className} extends BaseService implements {$service}Service \n"
            . "{\n"
            . "    public function get{$service}ById(\$id)\n"
            . "    {\n"
            . "    \n"
            . "    }\n"
            . "\n"
            . "    public function create{$service}(array \$fields)\n"
            . "    {\n"
            . "    \n"
            . "    }\n"
            . "\n"
            . "    public function update{$service}(\$id, array \$fields)\n"
            . "    {\n"
            . "    \n"
            . "    }\n"
            . "\n"
            . "    public function delete{$service}ById(\$id)\n"
            . "    {\n"
            . "    \n"
            . "    }\n"
            . "\n"
            . '    /**' . "\n"
            . "      * @return ${daoClass}\n"
            . "      */\n"
            . "    protected function get{$daoClass}()\n"
            . "    {\n"
            . "        return \$this->createDao('${service}:${daoClass}');\n"
            . "    \n"
            . "    }\n"
            . "\n"
            . "}\n";

        $filename = $args['rootPath'] . "/Service/Impl/{$className}.php";

        return [$filename, $phpCode];
    }
}