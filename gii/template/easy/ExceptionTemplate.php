<?php

namespace gii\template\easy;

use gii\template\TemplateInterface;

class ExceptionTemplate implements TemplateInterface
{

    public function getContext(array $args = [])
    {
        $exception = $args['bizId'];
        $className = "{$exception}Exception";
        $phpCode = "<?php\n"
            . "\n"
            . "namespace Biz\\{$exception}\\Exception;\n"
            . "\n"
            . "use support\\exception\\AbstractException;\n"
            . "\n"
            . "class {$className} extends AbstractException \n"
            . "{\n"
            . "    public function __construct(\$code)\n"
            . "    {\n"
            . "        \$this->setMessages();\n"
            . "        parent::__construct(\$code);\n"
            . "    }\n"
            . "\n"
            . "    public function setMessages()\n"
            . "    {\n"
            . "        \$this->messages = [\n"
            . "        \n"
            . "        ];\n"
            . "    }\n"
            . "\n"
            . "}\n";

        $filename = $args['rootPath'] . "/Exception/{$className}.php";

        return [$filename, $phpCode];
    }
}