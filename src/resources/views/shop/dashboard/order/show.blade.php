<?php

use Livewire\Volt\Component;
use NanoDepo\Nexus\Traits\HasAlert;
use NanoDepo\Nexus\Traits\WithModal;
use NanoDepo\Taberna\Enums\OrderStatus;
use NanoDepo\Taberna\Models\Addon;
use NanoDepo\Taberna\Models\Order;
use NanoDepo\Taberna\Models\OrderItem;

new class extends Component {
    use HasAlert;
    use WithModal;

    public Order $order;

    public ?string $itemId = null;
    public array $selected = [];

    public function changeStatus(OrderStatus $status): void
    {
        $this->order->update(['status' => $status]);
        $this->alert('Статус изменен');
    }

    public function recalculate(): void
    {
        $price = 0;
        foreach ($this->order->items as $item) {
            $itemPrice = is_null($item->variant) ? ($item->product->price - $item->product->discount) : ($item->variant->price - ($item->variant->discount ?? $item->product->discount));
            $itemPrice = $itemPrice * $item->quantity;
            if (is_iterable($item->addons)) {
                $itemPrice = $itemPrice + $item->addons->sum('price');
            }
            $price += $itemPrice;
        }

        $this->order->update(['price' => $price]);
        $this->alert('Произведен перерасчет стоимости');
    }

    public function deleteItem(OrderItem $item): void
    {
        $item->delete();
        $this->alert('Товар удален из заказа');
    }

    public function addAddon(OrderItem $item): void
    {
        $this->itemId = $item->id;
        $this->selected = $item->addons()->pluck('addons.id')->toArray();
        $this->open();
    }

    public function saveAddons(): void
    {
        $item = OrderItem::find($this->itemId);
        $item->addons()->detach();

        Addon::query()
            ->whereIn('id', $this->selected)
            ->get()
            ->map(function (Addon $addon) use ($item) {
                $item->addons()->attach($addon->id, [
                    'addon_name_at_purchase' => $addon->name,
                    'price_at_purchase' => $addon->price,
                    'quantity' => 1,
                ]);
            });

        $this->close();
        $this->reset('selected', 'itemId');
    }

    public function deleteAddon(OrderItem $item, Addon $addon): void
    {
        $item->addons()->detach($addon->id);
        $this->alert('Вы удалили дополнение');
    }

    public function with(): array
    {
        return [
            'addons' => is_null($this->itemId) ? [] : OrderItem::with('product.addons')->find($this->itemId)->product->addons,
        ];
    }
} ?>

<x-ui::layout.double>
    <x-slot name="breadcrumbs">
        <x-ui::breadcrumbs>
            <x-ui::breadcrumbs.item :href="route('dashboard')">Главная</x-ui::breadcrumbs.item>
            <x-ui::breadcrumbs.divider />
            <x-ui::breadcrumbs.item :href="route('order.index')">Заказы</x-ui::breadcrumbs.item>
            <x-ui::breadcrumbs.divider />
            <x-ui::breadcrumbs.item active>№{{ $order->created_at->format('ymdHis') }}</x-ui::breadcrumbs.item>
        </x-ui::breadcrumbs>
    </x-slot>

    <x-slot name="left">
        <x-ui::section title="Заказ №{{ $order->created_at->format('ymdHis') }}">
            <x-ui::list>
                <x-ui::list.value
                    icon="bolt"
                    :title="$order->status->title()"
                    subtitle="Статус"
                >
                    <x-ui::dropdown>
                        <x-slot name="trigger">
                            <x-ui::chip title="Изменить" after="chevron-down" />
                        </x-slot>

                        <x-slot name="content">
                            @foreach(OrderStatus::cases() as $status)
                                <x-ui::dropdown.item wire:click="changeStatus('{{ $status->value }}')">{{ $status->title() }}</x-ui::dropdown.item>
                            @endforeach
                        </x-slot>
                    </x-ui::dropdown>
                </x-ui::list.value>

                <x-ui::list.double
                    before="hashtag"
                    :title="$order->created_at->format('ymdHis')"
                    subtitle="Идентификатор"
                    after="square-2-stack"
                    x-clipboard.raw="{{ $order->created_at->format('ymdHis') }}"
                />

                <x-ui::list.value
                    icon="banknotes"
                    title="Сумма"
                    subtitle="{{ $order->payment_method == 'post' ? 'Наложенный платеж' : 'Перевод на карту' }}"
                >
                    <div class="flex flex-row items-center gap-3">
                        <div>{{ price($order->price)->formatted() }}</div>
                        <x-ui::circle wire:click="recalculate" icon="arrow-path" color="secondary" />
                    </div>
                </x-ui::list.value>

                <x-ui::list.icon
                    icon="calendar-days"
                    :title="$order->created_at->locale('uk')->isoFormat('D MMMM YYYY')"
                    subtitle="Дата заказа"
                />

                <x-ui::list.double
                    before="user-circle"
                    :title="$order->user->name"
                    :subtitle="$order->user->phone"
                    :description="$order->user->email"
                    after="phone-arrow-up-right"
                    href="tel:{{ $order->user->phone }}"
                />

                <x-ui::list.icon
                    icon="map-pin"
                    :title="$order->shipping_address"
                    subtitle="Адрес"
                    :truncate="false"
                />

                <x-ui::list.icon
                    icon="chat-bubble-bottom-center-text"
                    title="Комментарий"
                    :subtitle="$order->comment"
                    :truncate="false"
                />
            </x-ui::list>
        </x-ui::section>
    </x-slot>

    <x-slot name="right">
        @foreach($order->items as $item)
            <x-ui::section>
                <div class="flex flex-row justify-between items-center mb-3">
                    <x-ui::title
                        title="Товар #{{ $loop->iteration }}"
                    />

                    <x-ui::circle
                        wire:click="deleteItem('{{ $item->id }}')"
                        wire:confirm="Вы действительно хотите удалить товар из этого заказа? Отменить это действие будет невозможно."
                        icon="trash"
                        color="destructive"
                    />
                </div>

                <x-ui::list>
                    <x-ui::list.double
                        before="cube"
                        :title="$item->product->prefix.' '.$item->product->name"
                        :subtitle="$item->product->sku"
                        :href="route('product.show', $item->product->id)"
                        after="arrow-long-right"
                    />

                    @if($item->variant)
                        <x-ui::list.icon
                            icon="cube-transparent"
                            :title="$item->product->name .' (' . $item->variant->options->pluck('name')->join('/').')'"
                            :subtitle="$item->variant->sku"
                            :href="route('product.variant', [$item->product->id, $item->variant->id])"
                            after="arrow-long-right"
                        />
                    @endif

                    <x-ui::list.value
                        icon="square-3-stack-3d"
                        title="Кількість"
                    >
                        {{ $item->quantity }}
                    </x-ui::list.value>

                    <x-ui::list.value
                        icon="banknotes"
                        title="Ціна"
                    >
                        {{ price($item->price_at_purchase)->formatted() }}
                    </x-ui::list.value>

                    <x-ui::list.value
                        icon="receipt-percent"
                        title="Знижка"
                    >
                        {{ price($item->discount_at_purchase)->formatted() }}
                    </x-ui::list.value>

                </x-ui::list>

                <x-ui::title title="Доповнення" class="my-3" />

                <x-ui::list>
                    <x-ui::list.button
                        icon="plus"
                        title="Добавить опцию"
                        wire:click="addAddon('{{ $item->id }}')"
                    />

                    @if($item->addons->isNotEmpty())
                        @foreach($item->addons as $addon)
                            <x-ui::list.value
                                icon="squares-plus"
                                :title="$addon->pivot->addon_name_at_purchase"
                                :subtitle="price($addon->pivot->price_at_purchase)->formatted()"
                            >
                                <x-ui::circle
                                    wire:click="deleteAddon('{{ $item->id }}', '{{ $addon->id }}')"
                                    wire:confirm="Вы действительно хотите открепить дополнение?"
                                    icon="trash"
                                    color="destructive"
                                />
                            </x-ui::list.value>
                        @endforeach
                    @endif
                </x-ui::list>
            </x-ui::section>
        @endforeach

        <x-ui::dialog wire:model.live="opened">
            <x-slot name="header">
                <x-ui::title
                    title="Настройка дополнений"
                    subtitle="Выберите опции которые хотите добавить к этому товару"
                />

                <x-ui::circle icon="x-mark" x-on:click="close" />
            </x-slot>

            <x-slot name="content">
                <x-ui::list>
                    @foreach($addons as $addon)
                        <x-ui::list.checkbox
                            :title="$addon->name"
                            :description="$addon->description"
                            wire:model="selected"
                            :value="$addon->id"
                        >
                            <x-slot name="after">
                                {{ price($addon->price)->formatted() }}
                            </x-slot>
                        </x-ui::list.checkbox>
                    @endforeach
                </x-ui::list>
            </x-slot>

            <x-slot name="footer">
                <x-ui::button x-on:click="close" variant="text" color="secondary">Отмена</x-ui::button>
                <x-ui::button wire:click="saveAddons">Сохранить</x-ui::button>
            </x-slot>
        </x-ui::dialog>
    </x-slot>
</x-ui::layout.double>
