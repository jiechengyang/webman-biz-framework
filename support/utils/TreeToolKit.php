<?php


namespace support\utils;


class TreeToolKit
{
    /**
     * 把数据集转换成Tree(引用)
     * @param $list
     * @param string $pk
     * @param string $pid
     * @param string $child
     * @param int $root
     * @return array
     */
    public static function buildTree($list, $pk = 'id', $pid = 'pid', $child = 'children', $root = 0)
    {
        // 创建Tree
        $tree = array();
        if (is_array($list)) {
            // 创建基于主键的数组引用
            $refer = array();
            foreach ($list as $key => $data) {
                $refer[$data[$pk]] = &$list[$key];
            }

            foreach ($list as $key => $data) {
                // 判断是否存在parent
                $parentId = $data[$pid];
                if ($root == $parentId) {
                    $tree[] = &$list[$key];
                } else {
                    if (isset($refer[$parentId])) {
                        $parent = &$refer[$parentId];
                        $parent[$child][] = &$list[$key];
                    }
                }
            }
        }

        return $tree;
    }

    /**
     * [reference_delivery_tree 引用传值实现无限级分类]
     * @param  [type] $list  [数据源]
     * @param string $pk [主键key]
     * @param string $pid [上级key]
     * @param string $child [子数据key]
     * @return [array]        [无限级分类数据结构]
     */
    public static function referenceDeliveryTree($list, $pk = 'id', $pid = 'pid', $child = 'children', $root = 0)
    {
        $tree = [];
        if (!is_array($list)) {
            return $tree;
        }

        $data = array_column($list, null, $pk);
        foreach ($data as $key => $item) {
            if (isset($data[$item[$pid]])) {//存在值则为二级分类
                $data[$item[$pid]][$child][] = &$data[$item[$pk]];//传引用直接赋值与改变
                //$data[$item['pid']]在这里被改变，tree里面引用，所以会一直变化
            } else {// 由于是传引用思想，这里将不会有值;/至少三级分类;如果不存在表示pid为0，第一级
                $tree[] = &$data[$item[$pk]];
            }
        }

        return $tree;
    }

    public static function getTreeDrowDownList($list, $pk = 'id', $pid = 'pid', $child = 'children', $root = 0)
    {
        return self::getDrowDownList(self::referenceDeliveryTree($list, $pk, $pid, $child, $root));
    }

    public static function getDrowDownList($tree = [], &$result = [], $deep = 0, $separator = "　　")
    {
        $deep++;
        foreach ($tree as $list) {
            $result[$list['id']] = $deep == 1 ? str_repeat($separator, $deep - 1) . $list['name'] : str_repeat($separator, $deep - 1) . '├' . $list['name'];
            if (isset($list['children'])) {
                self::getDrowDownList($list['children'], $result, $deep);
            }
        }

        return $result;
    }


    /**
     * [递归创建子孙树]
     * @param  [Array]  $data      [数据源]
     * @param  [int]  $parent_id [默认从最远的根开始]
     * @param integer $lv [排序]
     * @return [Array]             [tree]
     */
    public static function spanningTree($data, $parent_id = null, $lv = 0)
    {
        $result = [];
        foreach ($data as $key => $value) {
            if ($value['parent_id'] == $parent_id) {
                $value['lv'] = $lv;
                $result[] = $value;
                $result = array_merge($result, self::spanningTree($data, $value['id'], $lv + 1));
            }
        }

        return $result;
    }

    /**
     * [递归创建子孙树-对象]
     * @param  [Object]  $object      [数据源]
     * @param  [int]  $parent_id [默认从最远的根开始]
     * @param integer $lv [排序]
     * @return [Array Object]             [对象数组]
     */
    public static function spanningObjectTree($object, $parent_id, $lv = 0)
    {
        $result = [];
        foreach ($object as $obj) {
            if ($obj->parent_id == $parent_id) {
                $obj->lv = $lv;
                $result[] = $obj;
                $result = array_merge($result, self::spanningObjectTree($object, $obj->id, $lv + 1));
            }
        }

        return $result;
    }

    /**
     * [递归创建子孙树-对象 2]
     * @param  [type] $object    [数据源]
     * @param  [type] $pkey [上级IDkey]
     * @param  [type] $parent_id [默认从最远的根开始]
     * @param  [type] $lv        [排序]
     * @return [type]            [对象数组]
     */
    public static function createObjectTree($object, $pkey = 'parent_id', $parent_id = 0, $lv = 1)
    {
        $result = [];
        foreach ($object as $obj) {
            if ($obj->{$pkey} === $parent_id) {
                isset($object->lv) and $obj->lv = $lv;
                $result[] = $obj;
                $obj->childrens = self::createObjectTree($object, $pkey, $obj->id, $lv + 1);
            }
        }

        return $result;
    }

    /**
     * [递归创建家谱树]
     * @param  [Array] $data [数据源]
     * @param  [type] $id   [description]
     * @return [Array]       [description]
     */
    public static function spanningParentTree($id, $data = [])
    {
        $result = [];
        foreach ($data as $key => $da) {
            if ($da['id'] == $id) {
                $result[] = $da;// 这种结果是从到自己祖父节点
                if ($da['parent_id']) {
                    $result = array_merge($result, self::spanningParentTree($da['parent_id'], $data));
                }
                // $result[] = $da;// 这种结果是从祖父节点到自己
            }
        }

        return $result;
    }
}