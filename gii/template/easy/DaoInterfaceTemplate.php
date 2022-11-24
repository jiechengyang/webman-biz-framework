<?php

namespace gii\template\easy;

use gii\template\BaseTemplate;
use gii\template\TemplateInterface;

class DaoInterfaceTemplate extends BaseTemplate implements TemplateInterface
{

    public function getContext(array $args = [])
    {
        $dao = $args['bizId'];
        $className = "{$dao}Dao";
        $phpCode = "<?php\n"
            . "\n"
            . "namespace Biz\\{$dao}\\Dao;\n"
            . "\n"
            . "use Codeages\Biz\Framework\Dao\AdvancedDaoInterface;\n"
            . "\n"
            . "interface {$className} extends AdvancedDaoInterface\n"
            . "{\n"
            . "\n"
            . "}\n";

        $filename = $args['rootPath'] . "/Dao/{$className}.php";

        return [$filename, $phpCode];
    }
}