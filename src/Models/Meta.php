<?php

namespace NanoDepo\Taberna\Models;

use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Model;

class Meta extends Model
{
    use HasUlids;

    public $timestamps = false;

    protected $fillable = [
        'subject_id',
        'subject_type',
        'title',
        'description',
        'canonical',
    ];
}
