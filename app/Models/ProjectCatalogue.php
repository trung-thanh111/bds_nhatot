<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Traits\QueryScopes;
use App\Enums\RealEstate\TransactionTypeEnum;

class ProjectCatalogue extends Model
{
    use HasFactory, SoftDeletes, QueryScopes;

    protected $table = 'project_catalogues';

    protected $fillable = [
        'parent_id',
        'property_group_id',
        'type_code',
        'name',
        'slug',
        'transaction_type',
        'icon_url',
        'lft',
        'rgt',
        'level',
        'path',
        'sort_order',
        'publish',
        'meta_title',
        'meta_desc',
    ];

    protected $casts = [
        'transaction_type' => TransactionTypeEnum::class,
    ];

    public function propertyGroup()
    {
        return $this->belongsTo(ProjectPropertyGroup::class, 'property_group_id');
    }

    public function parent()
    {
        return $this->belongsTo(self::class, 'parent_id');
    }

    public function children()
    {
        return $this->hasMany(self::class, 'parent_id')->orderBy('sort_order', 'asc');
    }

    public function projects()
    {
        return $this->hasMany(Project::class, 'catalogue_id');
    }
}
