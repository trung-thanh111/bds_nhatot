<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Traits\HasQuery;

class Floorplan extends Model
{
    use HasFactory, Notifiable, SoftDeletes, HasQuery;

    protected $fillable = [
        'property_id',
        'floor_number',
        'floor_label',
        'plan_image',
        'publish',
        'user_id',
    ];

    protected $casts = [];

    protected $relationable = ['users', 'properties'];

    public function getRelationable()
    {
        return $this->relationable;
    }

    public function users(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function properties(): BelongsTo
    {
        return $this->belongsTo(Property::class, 'property_id', 'id');
    }

    public function rooms(): HasMany
    {
        return $this->hasMany(FloorplanRoom::class, 'floorplan_id', 'id')->orderBy('sort_order');
    }
}
