<?php

use Livewire\Attributes\On;
use Livewire\Attributes\Validate;
use Livewire\Volt\Component;
use NanoDepo\Nexus\Traits\HasAlert;
use NanoDepo\Nexus\Traits\WithModal;
use NanoDepo\Taberna\Models\Product;
use NanoDepo\Taberna\Models\Review;

new class extends Component {
    use HasAlert;
    use WithModal;

    public string $productId;

    #[Validate(['required', 'int', 'min:1', 'max:5'])]
    public int $rating = 5;
    #[Validate(['required', 'string', 'max:32'])]
    public string $name = '';
    #[Validate(['nullable', 'string', 'max:32'])]
    public string $contacts = '';
    #[Validate(['required', 'string', 'max:1000'])]
    public string $text = '';

    public function mount(Product $product): void
    {
        $this->productId = $product->id;
    }

    #[On('add-review')]
    public function init(): void
    {
        $this->open();
    }

    public function submit(): void
    {
        $this->validate();

        Review::create([
            'product_id' => $this->productId,
            'name' => $this->name,
            'contacts' => $this->contacts,
            'text' => $this->text,
            'value' => $this->rating,
        ]);

        $this->alert('Дякуємо за відгук! Для нас це дуже цінно.');
        $this->close();
        $this->reset('rating', 'name', 'contacts', 'text');
    }
} ?>

<x-ui::dialog wire:model.live="opened">
    <x-slot name="header">
        <div class="text-xl font-medium">
            Додавання відгуку
        </div>

        <x-ui::circle icon="x-mark" x-on:click="close" />
    </x-slot>

    <x-slot name="content">
        <div class="flex flex-col gap-3">
            <div class="flex flex-col items-center">
                <x-ui::input.stars wire:model="rating" />
            </div>

            <x-ui::field wire:model="name" label="Ім'я та прізвище" hint="Використовуйте те ім'я, що вказували при замовленні" max="32">
                <x-ui::input
                    x-model="field"
                    maxlength="32"
                    max="32"
                    required
                />
            </x-ui::field>

            <x-ui::field wire:model="contacts" label="Телефон або Email" hint="Для зв'язку з вами" max="32">
                <x-ui::input
                    x-model="field"
                    maxlength="32"
                    max="32"
                />
            </x-ui::field>

            <x-ui::field wire:model="text" label="Відгук" hint="Розкажіть про свої враження" max="1000">
                <x-ui::input.textarea
                    x-model="field"
                    maxlength="1000"
                    max="1000"
                    rows="5"
                    required
                />
            </x-ui::field>
        </div>
    </x-slot>

    <x-slot name="footer">
        <x-ui::button x-on:click="close" variant="text" color="secondary">
            Закрити
        </x-ui::button>

        <x-ui::button wire:click="submit">
            Надіслати
        </x-ui::button>
    </x-slot>
</x-ui::dialog>
