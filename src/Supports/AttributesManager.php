<?php

namespace NanoDepo\Taberna\Supports;

use Illuminate\Support\Collection;
use NanoDepo\Taberna\Data\ProductData;
use NanoDepo\Taberna\Data\ProductPriceData;
use NanoDepo\Taberna\Models\Product;
use NanoDepo\Taberna\Models\Variant;

class AttributesManager
{
    public function __construct(public Product $product, public ?Variant $variant = null)
    {
        $this->product->load(
            'variants',
            'attributes.group',
            'brand:id,name',
            'category:id,slug,name,is_active',
            'images',
            'addons:id,name,description,price',
            'reviews'
        );

        $this->init();
    }

    public function init(): void
    {
        if (is_null($this->variant) && $this->product->has_variants && $this->product->variants->isNotEmpty()) {
            $ids = $this->product->attributes->where('is_variant_defining', true)->pluck('pivot.option_id')->toArray();
            $this->variant = Variant::query()
                ->whereHas('options', function ($q) use ($ids) {
                    $q->whereIn('options.id', $ids);
                }, '=', count($ids))
                ->first();

            if (is_null($this->variant)) {
                $this->variant = $this->product->variants()->first();
            }
        }
    }

    public function handle(): ProductData
    {
        return new ProductData(
            id: $this->product->id,
            vid: $this->variant?->id,
            sku: $this->product->sku,
            name: $this->getName(),
            image: $this->product->image,
            intro: $this->product->intro,
            description: $this->product->description,
            price: $this->getPrice(),
            quantity: $this->product->quantity,
            is_active: $this->product->is_active,
            has_variants: $this->product->has_variants && $this->product->variants->isNotEmpty(),
            is_main: $this->product->is_main,
            link: $this->getLink(),
            category: $this->product->category,
            brand: $this->product->brand,
            images: $this->getImages(),
            attributes: $this->getAttributes(),
            addons: $this->product->addons,
            reviews: $this->product->reviews,
            variants: $this->getVariants(),
        );
    }

    public function getName(): string
    {
        return is_null($this->variant)
            ? $this->product->prefix.' '.$this->product->name
            : $this->product->prefix.' '.$this->product->name .' (' . $this->variant->options->pluck('name')->join('/').')';
    }

    public function getPrice(): ProductPriceData
    {
        $price = $this->variant?->price ?? $this->product->price;
        $discount = (!is_null($this->variant) && $this->variant->discount > 0) ? $this->variant->discount : $this->product->discount;

        return new ProductPriceData(
            amount: price($price - $discount),
            base: price($price),
            discount: price($discount),
            has_sale: $discount > 0,
            percentage: intval(100 / $price * $discount)
        );
    }

    public function getImages(): Collection
    {
        return $this->product->images->map(fn ($item) => $item->thumbnail());
    }

    public function getVariants(): Collection
    {
        return $this->product->has_variants && $this->product->variants->isNotEmpty() ? $this->product->attributes()
            ->withWhereHas('options', function ($q) {
                $q->withWhereHas('variants', function ($query) {
                    $query->where('product_id', $this->product->id);
                });
            })
            ->where('is_variant_defining', true)
            ->get()
            // Мы перебираем аттрибуты чтобы определить ссылки для каждой опции с учетом выбранных в данный момент аттрибутов
            ->map(function ($attr) {
                return literal(
                    id: $attr->id,
                    type: $attr->type,
                    code: $attr->code,
                    name: $attr->name,
                    // Перебираем каждую опцию и пытаемся понять на какой вариант товара она будет вести
                    options: $attr->options->map(function ($option) use ($attr) {
                        // Определяем идентификаторы опций которые нужны
                        $ids = $this->variant?->options->where('attribute_id', '!=', $attr->id)->pluck('id')->toArray();
                        $ids[] = $option->id;

                        // Получаем вариант который в себе содержит все нужные нам опции
                        $variant = Variant::query()
                            ->whereHas('options', function ($q) use ($ids) {
                                $q->whereIn('options.id', $ids);
                            }, '=', count($ids))
                            ->first();

                        // Генерируем простые std классы для максимально сжатого результата
                        return literal(
                            id: $option->id,
                            code: $option->code,
                            name: $option->name,
                            variant: $variant?->sku,
                            active: $option->id == $this->variant?->options->where('attribute_id', $attr->id)->first()?->id,
                        );
                    }),
                );
            }) : collect();
    }

    public function getLink(): string
    {
        if (!is_null($this->variant)) {
            return route('variant', [$this->product->category->slug, $this->product->sku, $this->variant->sku]);
        }
        return route('product', [$this->product->category->slug, $this->product->sku]);
    }

    public function getAttributes(): Collection
    {
        $groups = collect();
        $this->product->attributes->each(function ($item) use (&$groups) {
            $groups->add(literal(
                title: $item->group->title,
                description: $item->group->description,
                attributes: collect()
            ));
        });

        return $groups->sortBy('title')
            ->unique()
            ->map(function ($group) {
                $this->product->attributes->each(function ($item) use ($group) {
                    if ($item->group->title == $group->title) {
                        $group->attributes->add(literal(
                            name: $item->name,
                            code: $item->code,
                            value: $item->pivot->value,
                        ));
                    }
                });

                return $group;
            });
    }
}
