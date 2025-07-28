@props(['value' => 0, 'min' => 0, 'max' => 100])

<div
    x-data="{ current: @js($value), minVal: @js($min), maxVal: @js($max), calcPercentage(min, max, val) { return ((val-min)/(max-min))*100 } }"
    x-modelable="current"
    {{ $attributes->merge(['class' => 'flex flex-row h-2.5 w-full overflow-hidden rounded-full bg-section-separator']) }}
>
    <div x-bind:style="`width: ${calcPercentage(minVal, maxVal, current)}%`" class="h-full rounded-full bg-primary"></div>
</div>
