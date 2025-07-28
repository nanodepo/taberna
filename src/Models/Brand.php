<?php

namespace NanoDepo\Taberna\Models;

use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use NanoDepo\Taberna\Traits\HasImageable;

class Brand extends Model
{
    use HasImageable;
    use HasUlids;
    use SoftDeletes;

    protected $fillable = [
        'name',
        'slug',
    ];
}
