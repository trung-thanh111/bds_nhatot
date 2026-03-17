<?php

namespace App\Repositories\RealEstate;

use App\Models\ProjectType;
use App\Repositories\BaseRepository;

class ProjectTypeRepository extends BaseRepository
{
    protected $model;

    public function __construct(
        ProjectType $model
    ){
        $this->model = $model;
        parent::__construct($model);
    }

    public function getProjectTypes(array $column = ['*'], array $condition = [], int $perPage = 20){
        $query = $this->model->select($column)->with('group');
        return $query
            ->keyword($condition['keyword'] ?? null)
            ->publish($condition['publish'] ?? null)
            ->CustomWhere($condition['where'] ?? null)
            ->orderBy('sort_order', 'asc')
            ->paginate($perPage);
    }
}
