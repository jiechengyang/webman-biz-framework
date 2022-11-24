<?php

namespace gii\template\easy;

use gii\template\TemplateInterface;

class ServiceInterfaceTemplate implements TemplateInterface
{

    public function getContext(array $args = [])
    {
        $service = $args['bizId'];
        $className = "{$service}Service";
        $phpCode = "<?php\n"
            . "\n"
            . "namespace Biz\\{$service}\\Service;\n"
            . "\n"
            . "interface {$className}\n"
            . "{\n"
            . "    public function get{$service}ById(\$id);\n"
            . "\n"
            . "    public function create{$service}(array \$fields);\n"
            . "\n"
            . "    public function update{$service}(\$id, array \$fields);\n"
            . "\n"
            . "    public function delete{$service}ById(\$id);\n"
            . "\n"
            . "}\n";

        $filename = $args['rootPath'] . "/Service/{$className}.php";

        return [$filename, $phpCode];
    }
}