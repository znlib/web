<?php

namespace ZnLib\Web\Controller\Helpers;

use ZnDomain\Query\Entities\Query;
use ZnDomain\Query\Entities\Where;

/**
 *
 */
class WebQueryHelper
{


    public static function getAllParams($params = [], Query $query = null)
    {
        $query = Query::forge($query);
        if (empty($params)) {
            return $query;
        }
        $params = self::convertParams($params);

        if (isset($params['expand'])) {
            $query->with($params['expand']);
        }
        if (isset($params['fields'])) {
            $query->select($params['fields']);
        }
        if (isset($params['sort'])) {
            $query->addOrderBy($params['sort']);
        }
        if (isset($params['page'])) {
            $query->page($params['page']);
        }
        if (isset($params['per-page'])) {
            $query->perPage($params['per-page']);
        }
        if (isset($params['offset'])) {
            $query->offset($params['offset']);
        }
        if (isset($params['limit'])) {
            $query->limit($params['limit']);
        }
        if (isset($params['where'])) {
            foreach ($params['where'] as $name => $value) {
                $query->whereNew(new Where($name, $value));
            }
        }
        return $query;
    }

    private static function convertParams($params = [])
    {
        $result = [];
        if (isset($params['expand'])) {
            $result['expand'] = self::splitStringParam($params['expand']);
            unset($params['expand']);
        }
        if (isset($params['fields'])) {
            $result['fields'] = self::splitStringParam($params['fields']);
            unset($params['fields']);
        }
        if (isset($params['sort'])) {
            $params['sort'] = self::splitStringParam($params['sort']);
            $result['sort'] = self::splitSortParam($params['sort']);
            unset($params['sort']);
        }
        if (isset($params['page'])) {
            $result['page'] = $params['page'];
            unset($params['page']);
        }
        if (isset($params['per-page'])) {
            $result['per-page'] = $params['per-page'];
            unset($params['per-page']);
        }
        if (isset($params['offset'])) {
            $result['offset'] = $params['offset'];
            unset($params['offset']);
        }
        if (isset($params['limit'])) {
            $result['limit'] = $params['limit'];
            unset($params['limit']);
        }
        if (isset($params)) {
            $result['where'] = $params;
        }
        return $result;
    }

    public static function splitStringParam($value)
    {
        if (empty($value) || !is_string($value)) {
            return [];
        }
        $values = preg_split('/\s*,\s*/', $value, -1, PREG_SPLIT_NO_EMPTY);
        return $values;
    }

    public static function splitSortParam($params)
    {
        $sortParams = [];
        foreach ($params as $sort) {
            if (strpos($sort, '-') !== false) {
                $name = substr($sort, 1);
                $direction = SORT_DESC;
            } else {
                $name = $sort;
                $direction = SORT_ASC;
            }
            $sortParams[$name] = $direction;
        }
        return $sortParams;
    }
}
