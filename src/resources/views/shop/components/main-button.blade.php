<button {{ $attributes->merge(['class' => 'flex flex-col items-center justify-center h-12 -mx-6 -mb-3 bg-primary text-on-primary font-medium tracking-wide cursor-pointer']) }}>
    {{ $slot }}
</button>
