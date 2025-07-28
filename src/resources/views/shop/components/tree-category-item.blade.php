@props(['category', 'level' => 0])

<div x-data="{ show: true }" class="flex flex-col divide-y divide-section-separator">
    <x-ui::list.item
        :description="$category->slug"
        :href="route('category.show', $category->id)"
    >
        <x-slot name="before">
            <div class="flex flex-row items-center flex-none gap-3">
                @for ($i = 0; $i < $level; $i++)
                    <div class="flex flex-col justify-center items-center w-9 h-9 text-hint">
                        @if(!($i < ($level - 1)))
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="size-5">
                                <path fill-rule="evenodd" d="M3.75 3a.75.75 0 0 1 .75.75v7.5h10.94l-1.97-1.97a.75.75 0 0 1 1.06-1.06l3.25 3.25a.75.75 0 0 1 0 1.06l-3.25 3.25a.75.75 0 1 1-1.06-1.06l1.97-1.97H3.75A.75.75 0 0 1 3 12V3.75A.75.75 0 0 1 3.75 3Z" clip-rule="evenodd" />
                            </svg>
                        @endif
                    </div>
                @endfor

                @if($category->is_virtual)
                    <x-icon::folder type="mini" />
                @else
                    <x-icon::folder-open type="mini" />
                @endif
            </div>
        </x-slot>

        <x-slot name="title">
            <div class="flex flex-row items-center gap-3 overflow-hidden">
                <div class="truncate">{{ $category->name }}</div>
                @if($category->is_virtual)
                    <div class="text-decoration-none">
                        <x-ui::label title="Virtual" color="var(--ndn-secondary)" />
                    </div>
                @endif
            </div>
        </x-slot>

        <x-slot name="after">
            <div class="flex flex-row items-center gap-3">
                @if($category->is_virtual)
                    <x-ui::meta icon="cube" :text="$category->virtual->count() > 0 ? $category->virtual->count() : 'O'" />
                @else
                    <x-ui::meta icon="folder-plus" :text="$category->children->count() > 0 ? $category->children->count() : 'O'" />
                    <x-ui::meta icon="cube" :text="$category->products->count() > 0 ? $category->products->count() : 'O'" />
                @endif
            </div>
        </x-slot>
    </x-ui::list.item>

    @foreach($category->children as $cat)
        @include('taberna::components.tree-category-item', ['category' => $cat, 'level' => $level + 1])
    @endforeach
</div>
