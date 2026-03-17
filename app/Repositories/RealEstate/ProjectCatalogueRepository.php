<?php

namespace App\Repositories\RealEstate;

use App\Models\ProjectCatalogue;
use App\Repositories\BaseRepository;

class ProjectCatalogueRepository extends BaseRepository
{
    protected $model;

    public function __construct(ProjectCatalogue $model)
    {
        $this->model = $model;
    }

    public function getBaseQuery()
    {
        return $this->model->newQuery();
    }
}
