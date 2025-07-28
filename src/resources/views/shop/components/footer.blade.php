<div class="flex flex-col w-full max-w-2xl mx-auto gap-6 mt-3 p-3 text-xs text-subtitle font-medium">

    <div class="flex flex-row justify-between">
        <div class="flex flex-col w-1/2 gap-1 overflow-hidden">
            <div class="font-bold text-on-section">Контакты</div>
            @foreach(config('taberna.phones') as $link => $number)
                <a href="tel:{{ $link }}" class="link">{{ $number }}</a>
            @endforeach
            <a href="mailto:{{ config('taberna.email') }}" class="link">{{ config('taberna.email') }}</a>
        </div>

        <div class="flex flex-col w-1/2 items-end gap-1 overflow-hidden">
            <div class="text-right font-bold text-on-section">Дополнительно</div>
            <a href="{{ route('delivery') }}" class="text-right link">Оплата і доставка</a>
            <a href="{{ route('return-policy') }}" class="text-right link">Умови повернення</a>
            <a href="{{ route('privacy-policy') }}" class="text-right link">Політика конфіденційності</a>
        </div>
    </div>

</div>
