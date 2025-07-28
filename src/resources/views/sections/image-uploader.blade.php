<?php

use Illuminate\Database\Eloquent\Model;
use Livewire\Attributes\Validate;
use Livewire\Volt\Component;
use Livewire\WithFileUploads;
use NanoDepo\Nexus\Traits\HasAlert;
use NanoDepo\Nexus\Traits\HasImageWriter;
use NanoDepo\Taberna\Models\Image;

new class extends Component {
    use HasAlert;
    use HasImageWriter;
    use WithFileUploads;

    public string $subjectId;
    public string $subjectType;
    public bool $multiple = false;

    #[Validate(['files.*' => ['image', 'max:5120']])]
    public array $files = [];

    public function mount(Model $subject): void
    {
        $this->subjectId = $subject->id;
        $this->subjectType = $subject::class;
    }

    public function updatedFiles($val): void
    {
        $dir = 'marketplace';
        foreach ($this->files as $file) {
            Image::query()->create([
                'subject_type' => $this->subjectType,
                'subject_id' => $this->subjectId,
                'disk' => $dir,
                'path' => $this->writeImage($file, $dir),
            ]);
        }

        $this->alert('Изображения загружены');
        $this->reset('files');
    }

    public function primary(Image $image): void
    {
        Image::query()
            ->where('subject_id', $image->subject_id)
            ->update(['is_primary' => false]);

        $image->update(['is_primary' => true]);
    }

    public function delete(Image $image): void
    {
        if ($image->is_primary) {
            Image::query()
                ->where('subject_id', $image->subject_id)
                ->whereNot('id', $image->id)
                ->first()
                ?->update(['is_primary' => true]);
        }

        $image->delete();
    }

    public function with(): array
    {
        return [
            'images' => Image::query()
                ->where('subject_id', $this->subjectId)
                ->where('subject_type', $this->subjectType)
                ->get(),
        ];
    }
} ?>

<x-ui::section header="Изображение">
    <div
        x-data="{ uploading: false, progress: 0 }"
        x-on:livewire-upload-start="uploading = true"
        x-on:livewire-upload-finish="uploading = false"
        x-on:livewire-upload-cancel="uploading = false"
        x-on:livewire-upload-error="uploading = false"
        x-on:livewire-upload-progress="progress = $event.detail.progress"
    >
        <x-ui::field label="Выберите файл" hint="Можно выбрать любое изображение размером до 5 МБ">
            <input type="file" wire:model="files" class="input file" multiple />
        </x-ui::field>

        <div x-show="uploading" class="mt-3">
            <x-taberna::progress x-model="progress" />
        </div>

        <div class="flex flex-row flex-wrap gap-3 mt-3">
            @foreach($images as $image)
                <div class="relative flex flex-col w-28 h-28 rounded-lg border border-section-separator bg-hint bg-cover bg-center" style="background-image: url('{{ $image?->thumbnail() }}')">
                    <div class="flex flex-row flex-auto justify-end p-1 bg-section/70 opacity-0 hover:opacity-100 transition">
                        <x-ui::dropdown>
                            <x-slot name="trigger">
                                <x-ui::circle icon="ellipsis-vertical" />
                            </x-slot>

                            <x-slot name="content">
                                <x-ui::dropdown.item wire:click="primary('{{ $image->id }}')" icon="check">Выбрать главной</x-ui::dropdown.item>
                                <x-ui::dropdown.item wire:click="delete('{{ $image->id }}')" icon="trash">Удалить</x-ui::dropdown.item>
                            </x-slot>
                        </x-ui::dropdown>
                    </div>

                    @if($image->is_primary)
                        <div class="absolute top-1 -left-1">
                            <x-ui::label title="Главная" color="var(--ndn-secondary)" />
                        </div>
                    @endif
                </div>
            @endforeach
        </div>
    </div>
</x-ui::section>
