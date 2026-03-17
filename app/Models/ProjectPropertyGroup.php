<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\QueryScopes;

class ProjectPropertyGroup extends Model
{
    use HasFactory, QueryScopes;

    protected $table = 'project_property_groups';

    protected $fillable = [
        'code',
        'name',
        'description',
        'icon_url',
        'sort_order',
        'publish',
    ];

    public function types()
    {
        return $this->hasMany(ProjectType::class, 'group_id');
    }

    public function catalogues()
    {
        return $this->hasMany(ProjectCatalogue::class, 'property_group_id');
    }
}
