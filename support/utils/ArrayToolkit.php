<?php

namespace support\utils;

class ArrayToolkit
{
    public static function get(array $array, $key, $default)
    {
        if (isset($array[$key])) {
            return $array[$key];
        } else {
            return $default;
        }
    }

    public static function column(array $array, $columnName)
    {
        if (function_exists('array_column')) {
            return array_column($array, $columnName);
        }

        if (empty($array)) {
            return [];
        }

        $column = [];

        foreach ($array as $item) {
            if (isset($item[$columnName])) {
                $column[] = $item[$columnName];
            }
        }

        return $column;
    }

    public static function columns(array $array, array $columnNames)
    {
        if (empty($array) or empty($columnNames)) {
            return [];
        }

        $columns = [];

        foreach ($array as $item) {
            foreach ($columnNames as $key) {
                $value = isset($item[$key]) ? $item[$key] : '';
                $columns[$key][] = $value;
            }
        }

        return array_values($columns);
    }

    public static function parts(array $array, array $keys)
    {
        foreach (array_keys($array) as $key) {
            if (!in_array($key, $keys)) {
                unset($array[$key]);
            }
        }

        return $array;
    }

    public static function requireds(array $array, array $keys, $strictMode = false)
    {
        foreach ($keys as $key) {
            if (!array_key_exists($key, $array)) {
                return false;
            }
            if ($strictMode && (is_null($array[$key]) || $array[$key] === '' || $array[$key] === 0)) {
                return false;
            }
        }

        return true;
    }

    public static function changes(array $before, array $after)
    {
        $changes = ['before' => [], 'after' => []];

        foreach ($after as $key => $value) {
            if (!isset($before[$key])) {
                continue;
            }

            if ($value != $before[$key]) {
                $changes['before'][$key] = $before[$key];
                $changes['after'][$key] = $value;
            }
        }

        return $changes;
    }

    public static function group(array $array, $key)
    {
        $grouped = [];

        foreach ($array as $item) {
            if (empty($grouped[$item[$key]])) {
                $grouped[$item[$key]] = [];
            }
            $grouped[$item[$key]][] = $item;
        }

        return $grouped;
    }

    public static function index(array $array, $name)
    {
        $indexedArray = [];

        if (empty($array)) {
            return $indexedArray;
        }

        foreach ($array as $item) {
            if (isset($item[$name])) {
                $indexedArray[$item[$name]] = $item;
                continue;
            }
        }

        return $indexedArray;
    }

    public static function groupIndex(array $array, $key, $index)
    {
        $grouped = [];

        foreach ($array as $item) {
            if (empty($grouped[$item[$key]])) {
                $grouped[$item[$key]] = [];
            }

            $grouped[$item[$key]][$item[$index]] = $item;
        }

        return $grouped;
    }

    public static function rename(array $array, array $map)
    {
        $keys = array_keys($map);

        foreach ($array as $key => $value) {
            if (in_array($key, $keys)) {
                $array[$map[$key]] = $value;
                unset($array[$key]);
            }
        }

        return $array;
    }

    public static function filter(array $array, array $specialValues)
    {
        $filtered = [];

        foreach ($specialValues as $key => $value) {
            if (!array_key_exists($key, $array)) {
                continue;
            }

            if (is_array($value)) {
                $filtered[$key] = (array) $array[$key];
            } elseif (is_int($value)) {
                $filtered[$key] = (int) $array[$key];
            } elseif (is_float($value)) {
                $filtered[$key] = (float) $array[$key];
            } elseif (is_bool($value)) {
                $filtered[$key] = (bool) $array[$key];
            } else {
                $filtered[$key] = (string) $array[$key];
            }

            if (!isset($filtered[$key])) {
                $filtered[$key] = $value;
            }
        }

        return $filtered;
    }

    public static function trim($array)
    {
        if (!is_array($array)) {
            return $array;
        }

        foreach ($array as $key => $value) {
            if (is_array($value)) {
                $array[$key] = static::trim($value);
            } elseif (is_string($value)) {
                $array[$key] = trim($value);
            }
        }

        return $array;
    }

    public static function every($array, $callback = null)
    {
        foreach ($array as $value) {
            if ((is_null($callback) && !$value) || (is_callable($callback) && !$callback($value))) {
                return false;
            }
        }

        return true;
    }

    public static function some($array, $callback = null)
    {
        foreach ($array as $value) {
            if ((is_null($callback) && $value) || (is_callable($callback) && $callback($value))) {
                return true;
            }
        }

        return false;
    }

    /**
     * 二维数组合并值，返回去除重复值的一维数组.
     *
     * @param [type] $doubleArrays [description]
     *
     * @return [type] [description]
     */
    public static function mergeArraysValue($doubleArrays)
    {
        $values = [];
        foreach ($doubleArrays as $array) {
            if (empty($array)) {
                continue;
            }
            foreach ($array as $value) {
                if (in_array($value, $values)) {
                    continue;
                }
                $values[] = $value;
            }
        }

        return $values;
    }

    public static function thin(array $array, array $columns)
    {
        $thinner = [];
        foreach ($array as $k => $v) {
            foreach ($columns as $v2) {
                $thinner[$k][$v2] = $v[$v2];
            }
        }

        unset($array);

        return $thinner;
    }

    /**
     * 二维数组字段根据一维数组值的顺序排序
     *
     * @param array  $twoDimensionalArr 二维数组
     * @param array  $arr               排序规则（一维数组）
     * @param string $field             排序字段
     *
     * @return array|mixed
     */
    public static function twoDimensionalArrSortByArrValWithField(array $twoDimensionalArr, array $arr, string $field)
    {
        $arr = array_flip($arr);
        usort(
            $twoDimensionalArr,
            function ($item, $anotherItem) use ($arr, $field) {
                $itemId = $arr[$item[$field]];
                $anotherItemId = $arr[$anotherItem[$field]];
                if ($itemId == $anotherItemId) {
                    return 0;
                }

                return ($itemId < $anotherItemId) ? -1 : 1;
            }
        );

        return $twoDimensionalArr;
    }
}
