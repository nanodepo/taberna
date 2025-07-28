<?php

use Illuminate\Database\Eloquent\Model;
use Livewire\Attributes\Validate;
use Livewire\Volt\Component;
use NanoDepo\Nexus\Traits\HasAlert;
use NanoDepo\Taberna\Models\Meta;

new class extends Component {
    use HasAlert;

    public string $id;
    public string $type;

    #[Validate(['nullable', 'string', 'max:90'])]
    public string $title = '';
    #[Validate(['nullable', 'string', 'max:200'])]
    public string $description = '';
    #[Validate(['nullable', 'string', 'max:150'])]
    public string $canonical = '';

    public function mount(Model $subject): void
    {
        $this->id = $subject->id;
        $this->type = $subject::class;
        $this->init();
    }

    public function init(): void
    {
        if ($meta = Meta::query()->where('subject_id', $this->id)->first()) {
            $this->title = $meta->title ?? '';
            $this->description = $meta->description ?? '';
            $this->canonical = $meta->canonical ?? '';
        }
    }

    public function submit(): void
    {
        $this->validate();

        Meta::query()->updateOrCreate([
            'subject_id' => $this->id,
            'subject_type' => $this->type,
        ], [
            'title' => $this->title,
            'description' => $this->description,
            'canonical' => $this->canonical,
        ]);

        $this->alert('Сохранено');
    }
} ?>

<x-ui::section header="SEO" hint="Нужно для браузера и поисковых систем">
    <form wire:submit="submit" class="flex flex-col gap-3">
        <x-ui::field wire:model="title" label="Заголовок страницы" max="90" hint="Старайтесь уложиться в 50-70 символов">
            <x-ui::input x-model="field" max="90" maxlength="90" />
        </x-ui::field>

        <x-ui::field wire:model="description" label="Описание" max="200" hint="Не делайте длинных описаний, в гугле видно около 100-120 символов">
            <x-ui::input.textarea x-model="field" rows="3" max="200" maxlength="200" />
        </x-ui::field>

        <x-ui::field wire:model="canonical" label="Каноническая ссылка" max="150" hint="Не заполняйте, если не знаете что это">
            <x-ui::input x-model="field" max="150" maxlength="150" />
        </x-ui::field>

        <div class="flex flex-row justify-end">
            <x-ui::button type="submit" wire:dirty>Сохранить</x-ui::button>
        </div>
    </form>
</x-ui::section>
