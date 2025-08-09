<div class="flex flex-col w-full max-w-2xl mx-auto gap-6 mt-3 p-3 text-xs text-subtitle font-medium">
    <div class="flex flex-row justify-between">
        <div class="flex flex-col w-1/2 items-start gap-1 overflow-hidden">
            <div class="font-bold text-on-section">Contacts</div>
            @foreach(taberna()->phones as $link => $number)
                <a href="tel:{{ $link }}" class="link">{{ $number }}</a>
            @endforeach
            <a href="mailto:{{ taberna()->email }}" class="link">{{ taberna()->email }}</a>
        </div>

        <div class="flex flex-col w-1/2 items-end gap-1 overflow-hidden">
            <div class="text-right font-bold text-on-section">Other</div>
            <a href="{{ route('delivery') }}" class="text-right link" wire:navigate>Payments and Delivery</a>
            <a href="{{ route('return-policy') }}" class="text-right link" wire:navigate>Return policy</a>
            <a href="{{ route('privacy-policy') }}" class="text-right link" wire:navigate>Privacy policy</a>
        </div>
    </div>
</div>
