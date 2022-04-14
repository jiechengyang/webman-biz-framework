<?php

namespace gii\template\easy;

use gii\template\BaseTemplate;
use gii\template\TemplateInterface;
use support\utils\ArrayToolkit;

class DaoImpTemplate extends BaseTemplate implements TemplateInterface
{

    /**
     * @param array $args
     * @return string
     */
    public function getContext(array $args = [])
    {
        $dao = $args['bizId'];
        $tableName = $args['tableName'];
        $declares = $this->parseDeclares($args['declares'] ?? [], $tableName);
        $declaresStr = $this->splitDeclares($declares);
        $className = "{$dao}DaoImpl";
        $phpCode = "<?php\n"
            . "\n"
            . "namespace Biz\\{$dao}\\Dao\\Impl;\n"
            . "\n"
            . "use Codeages\\Biz\\Framework\\Dao\\AdvancedDaoImpl;\n"
            . "use Biz\\{$dao}\\Dao\\{$dao}Dao;\n"
            . "\n"
            . "class {$className} extends AdvancedDaoImpl implements {$dao}Dao \n"
            . "{\n"
            . "\n"
            . "    protected " . '$table = ' . "'{$tableName}';\n"
            . "\n"
            . "    public function declares()\n"
            . "    {\n"
            . "        {$declaresStr}"
            . "    } \n"
            . "}\n";

        $filename = $args['rootPath'] . "/Dao/Impl/{$className}.php";

        return [$filename, $phpCode];
    }

    protected function splitDeclares($declares)
    {
        $str = "return [\n";
        if (!empty($declares)) {
            foreach ($declares as $key => $declare) {
                $subStr = "            '{$key}' => ";
                if (is_string($declare)) {
                    $subStr .= " '{$declares}',\n";
                } elseif (is_array($declare)) {
                    $subStr .= "[ \n";
                    foreach ($declare as $sKey => $sVal) {
                        if (is_numeric($sKey)) {
                            $subStr .= "                '{$sVal}',\n";
                        } else {
                            $subStr .= "                '{$sKey}' => '{$sVal}',\n";
                        }
                    }
                    $subStr .= "           ], \n";
                }

                $str .= $subStr;
            }
        }

        $str .= "        ];\n";

        return $str;
    }

    protected function parseDeclares($declares, $tableName)
    {
        empty($declares['serializes']) && $declares['serializes'] = [];
        empty($declares['orderbys']) && $declares['orderbys'] = [];
        empty($declares['conditions']) && $declares['conditions'] = [];
        $tableDescriptions = $this->getTableDescriptions($tableName);
        $fields = ArrayToolkit::column($tableDescriptions, 'Field');
        if (in_array('id', $fields)) {
            $declares['orderbys'][] = 'id';
            array_push($declares['conditions'], 'id = :id', 'id > :id_GT', 'id IN ( :ids)', 'id NOT IN ( :noIds)');
        }

        if (in_array('title', $fields)) {
            $declares['orderbys'][] = 'id';
            array_push($declares['conditions'], 'title = :title', 'title LIKE :title', 'title PRE_LIKE :likeTitle');
        }

        if (in_array('createdTime', $fields)) {
            $declares['orderbys'][] = 'createdTime';
            $declares['timestamps'][] = 'createdTime';
            array_push($declares['conditions'], 'createdTime >= :startTime', 'createdTime <= :endTime');
        }

        if (in_array('updatedTime', $fields)) {
            $declares['orderbys'][] = 'updatedTime';
            $declares['timestamps'][] = 'updatedTime';
        }

        return $declares;
    }

    protected function getTableDescriptions($tableName)
    {
        return $this->getBiz()['db']->fetchAll("DESC `{$tableName}`;") ?? [];
    }
}