<?php

namespace NanoDepo\Taberna\Models;

use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use NanoDepo\Taberna\Enums\AttributeType;

class Attribute extends Model
{
    use HasUlids;
    use SoftDeletes;

    protected $fillable = [
        'group_id',
        'name',
        'code',
        'type',
        'is_variant_defining',
        'is_filterable',
        'is_required',
    ];

    protected function casts(): array
    {
        return [
            'type' => AttributeType::class,
        ];
    }

    public function group(): BelongsTo
    {
        return $this->belongsTo(Group::class);
    }

    public function options(): HasMany
    {
        return $this->hasMany(Option::class);
    }
}
