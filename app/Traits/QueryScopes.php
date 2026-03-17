<?php

namespace App\Traits;

trait QueryScopes
{
    public function scopeKeyword($query, $keyword = null, array $fieldSearch = [], array $whereHas = [])
    {
        if (empty($keyword)) {
            return $query;
        }

        $query->where(function ($subQuery) use ($keyword, $fieldSearch, $whereHas) {
            if (!empty($fieldSearch)) {
                foreach ($fieldSearch as $field) {
                    $subQuery->orWhere($field, 'LIKE', '%' . $keyword . '%');
                }
            } else {
                $subQuery->where('name', 'LIKE', '%' . $keyword . '%')
                    ->orWhere('code', 'LIKE', '%' . $keyword . '%');
            }

            if (!empty($whereHas) && isset($whereHas['relation'], $whereHas['field'])) {
                $field = $whereHas['field'];
                $subQuery->orWhereHas($whereHas['relation'], function ($relationQuery) use ($field, $keyword) {
                    $relationQuery->where($field, 'LIKE', '%' . $keyword . '%');
                });
            }
        });

        return $query;
    }

    public function scopePublish($query, $publish)
    {
        if (!empty($publish)) {
            $query->where('publish', '=', $publish);
        }
        return $query;
    }

    public function scopeCustomWhere($query, $where = [])
    {
        if (!empty($where)) {
            foreach ($where as $val) {
                $query->where($val[0], $val[1], $val[2]);
            }
        }
        return $query;
    }

    public function scopeCustomWhereRaw($query, $rawQuery)
    {
        if (is_array($rawQuery) && !empty($rawQuery)) {
            foreach ($rawQuery as $raw) {
                $query->whereRaw($raw[0], $raw[1] ?? []);
            }
        }
        return $query;
    }

    public function scopeRelationCount($query, $relation)
    {
        if (!empty($relation)) {
            foreach ($relation as $item) {
                $query->withCount($item);
                $query->with($item);
            }
        }
        return $query;
    }

    public function scopeRelation($query, $relation)
    {
        if (!empty($relation)) {
            foreach ($relation as $rel) {
                $query->with($rel);
            }
        }
        return $query;
    }

    public function scopeCustomJoin($query, $join)
    {
        if (!empty($join)) {
            foreach ($join as $val) {
                $query->join($val[0], $val[1], $val[2], $val[3]);
            }
        }
        return $query;
    }

    public function scopeCustomGroupBy($query, $groupBy)
    {
        if (!empty($groupBy)) {
            $query->groupBy($groupBy);
        }
        return $query;
    }

    public function scopeCustomOrderBy($query, $orderBy)
    {
        if (!empty($orderBy)) {
            $query->orderBy($orderBy[0], $orderBy[1]);
        }
        return $query;
    }

    public function scopeCustomDropdownFilter($query, $condition)
    {
        if (!empty($condition)) {
            foreach ($condition as $key => $val) {
                if ($val === null || $val === '' || $val === 'none') {
                    continue;
                }
                $query->where($key, '=', $val);
            }
        }
        return $query;
    }

    public function scopeCustomerCreatedAt($query, $condition)
    {
        if (!empty($condition)) {
            $explode    = array_map('trim', explode('-', $condition));
            $startDate  = convertDateTime($explode[0], 'Y-m-d 00:00:00', 'm/d/Y');
            $endDate    = convertDateTime($explode[1], 'Y-m-d 23:59:59', 'm/d/Y');

            $query->whereDate('created_at', '>=', $startDate);
            $query->whereDate('created_at', '<=', $endDate);
        }
        return $query;
    }
}
