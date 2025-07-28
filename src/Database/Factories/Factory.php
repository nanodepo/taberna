<?php

namespace NanoDepo\Taberna\Database\Factories;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use Spatie\LaravelData\Data;
use stdClass;

abstract class Factory
{
    public ?array $data = [];

    public static function new(): static
    {
        return (new static)->extra();
    }

    abstract public function definition(): array;

    abstract public function create(): Model;

    public function toDto(): stdClass|Data
    {
        return (object) $this->data;
    }

    public function toArray(): array
    {
        return $this->data;
    }

    public function extra(array $extra = []): static
    {
        $this->data = array_merge(
            $this->definition(),
            $extra
        );

        return $this;
    }

    public function times(int $times, array $extra = []): Collection
    {
        return collect()
            ->times($times)
            ->map(fn() => $this->extra($extra)->create());
    }

}
