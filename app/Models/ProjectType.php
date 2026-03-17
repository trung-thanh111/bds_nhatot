<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\QueryScopes;
use App\Enums\RealEstate\TransactionTypeEnum;

class ProjectType extends Model
{
    use HasFactory, QueryScopes;

    protected $table = 'project_types';

    protected $casts = [
        'transaction_type' => TransactionTypeEnum::class,
    ];

    protected $fillable = [
        'group_id',
        'code',
        'name',
        'name_short',
        'transaction_type',
        'sort_order',
        'publish',
    ];

    public function group()
    {
        return $this->belongsTo(ProjectPropertyGroup::class, 'group_id');
    }
}
