<?php

namespace NanoDepo\Taberna\Traits;

use Illuminate\Database\Eloquent\Relations\MorphOne;
use NanoDepo\Taberna\Models\Meta;

trait HasMeta
{
    public function meta(): MorphOne
    {
        return $this->morphOne(Meta::class, 'subject')
            ->withDefault([
                'title' => $this->name,
                'description' => null,
                'canonical' => null,
            ]);
    }

}

