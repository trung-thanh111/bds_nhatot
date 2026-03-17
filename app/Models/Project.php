<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Traits\QueryScopes;
use App\Enums\RealEstate\TransactionTypeEnum;
use App\Enums\RealEstate\ProjectStatusEnum;
use App\Enums\RealEstate\PriceUnitEnum;
use App\Enums\RealEstate\DirectionEnum;
use App\Enums\RealEstate\LegalStatusEnum;
use App\Enums\RealEstate\FurnitureStatusEnum;

class Project extends Model
{
    use HasFactory, SoftDeletes, QueryScopes;

    protected $fillable = [
        'code',
        'name',
        'slug',
        'catalogue_id',
        'agent_id',
        'type_code',
        'property_group',
        'transaction_type',
        'is_project',
        'summary',
        'description',
        'meta_title',
        'meta_desc',
        'focus_keyword',
        'price',
        'price_unit',
        'price_vnd',
        'price_negotiable',
        'area',
        'area_use',
        'area_land',
        'length',
        'width',
        'bedrooms',
        'bathrooms',
        'floors',
        'floor_number',
        'direction',
        'balcony_direction',
        'legal_status',
        'furniture_status',
        'has_elevator',
        'has_pool',
        'has_parking',
        'has_security',
        'has_balcony',
        'has_rooftop',
        'has_basement',
        'has_gym',
        'has_ac',
        'has_wifi',
        'province_code',
        'province_name',
        'district_code',
        'district_name',
        'ward_code',
        'ward_name',
        'province_new_code',
        'province_new_name',
        'ward_new_code',
        'ward_new_name',
        'address',
        'latitude',
        'longitude',
        'image',
        'iframe_map',
        'album',
        'has_video',
        'video_url',
        'video_embed',
        'has_virtual_tour',
        'virtual_tour_url',
        'extra_fields',
        'status',
        'publish',
        'is_featured',
        'is_hot',
        'is_urgent',
        'sort_order',
        'view_count',
        'published_at'
    ];

    protected $casts = [
        'extra_fields' => 'array',
        'album' => 'array',
        'transaction_type' => TransactionTypeEnum::class,
        'status' => ProjectStatusEnum::class,
        'price_unit' => PriceUnitEnum::class,
        'direction' => DirectionEnum::class,
        'balcony_direction' => DirectionEnum::class,
        'legal_status' => LegalStatusEnum::class,
        'furniture_status' => FurnitureStatusEnum::class,
        'published_at' => 'datetime',
        'is_project' => 'integer',
        'publish' => 'integer',
        'is_featured' => 'integer',
        'is_hot' => 'integer',
        'is_urgent' => 'integer',
        'price' => 'decimal:2',
        'price_vnd' => 'decimal:2',
        'area' => 'decimal:2',
    ];

    public function catalogue()
    {
        return $this->belongsTo(ProjectCatalogue::class, 'catalogue_id');
    }

    public function agent()
    {
        return $this->belongsTo(Agent::class, 'agent_id');
    }

    public function propertyGroup()
    {
        return $this->belongsTo(ProjectPropertyGroup::class, 'property_group', 'code');
    }

    public function projectType()
    {
        return $this->belongsTo(ProjectType::class, 'type_code', 'code');
    }

    public function visitRequests()
    {
        return $this->hasMany(VisitRequest::class, 'project_id');
    }

    // public function items()
    // {
    //     return $this->hasMany(ProjectItem::class, 'project_id');
    // }
}
