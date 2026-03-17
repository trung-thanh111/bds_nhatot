<?php

namespace App\Repositories\RealEstate;

use App\Models\ProjectPropertyGroup;
use App\Repositories\BaseRepository;

class ProjectPropertyGroupRepository extends BaseRepository
{
    protected $model;

    public function __construct(
        ProjectPropertyGroup $model
    ){
        $this->model = $model;
        parent::__construct($model);
    }
}
