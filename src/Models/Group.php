<?php

namespace NanoDepo\Taberna\Models;

use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Group extends Model
{
    use HasUlids;
    use SoftDeletes;

    protected $fillable = [
        'title',
        'description',
    ];

    public function attributes(): HasMany
    {
        return $this->hasMany(Attribute::class);
    }
}
