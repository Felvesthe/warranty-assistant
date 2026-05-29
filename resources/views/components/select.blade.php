@props([
    'name' => $attributes->whereStartsWith('wire:model')->first() ?? $attributes->whereStartsWith('x-model')->first(),
    'icon' => null,
    'placeholder' => null,
    'invalid' => false,
])

@php
    $invalid ??= $name && $errors->has($name);
    
    $selectClasses = \Illuminate\Support\Arr::toCssClasses([
        'w-full inline-block border p-2 text-sm text-neutral-800 disabled:text-neutral-500 placeholder-neutral-400 dark:text-neutral-300 dark:disabled:text-neutral-400 dark:placeholder-neutral-400',
        'bg-white dark:bg-neutral-900 dark:disabled:bg-neutral-800',
        'disabled:cursor-not-allowed transition-colors duration-200',
        'shadow-none dark:shadow-sm disabled:shadow-none rounded-box',
        'focus:ring-2 focus:ring-offset-0 focus:outline-none appearance-none cursor-pointer',
        'border-black/10 focus:border-black/15 focus:ring-neutral-900/15 dark:border-white/15 dark:focus:border-white/20 dark:focus:ring-neutral-100/15' => !$invalid,
        'border-red-600/30 border-2 focus:border-red-600/30 focus:ring-red-600/20 dark:border-red-400/30 dark:focus:border-red-400/30 dark:focus:ring-red-400/20' => $invalid,
        'pl-10' => filled($icon),
    ]);

    $wrapperClasses = \Illuminate\Support\Arr::toCssClasses([
        'relative isolate',
        'w-full' => !str_contains($attributes->get('class', ''), 'flex-') && !str_contains($attributes->get('class', ''), 'w-'),
        $attributes->get('class')
    ]);
@endphp

<div class="{{ $wrapperClasses }}">
    @if (filled($icon))
        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-neutral-500 dark:text-neutral-500 z-20">
            <x-ui.icon :name="$icon" class="!size-[1.15rem]" />
        </div>
    @endif

    <select
        @if($name) name="{{ $name }}" @endif
        class="{{ $selectClasses }}"
        @if($invalid) invalid @endif
        {{ $attributes->except('class') }}
    >
        @if ($placeholder)
            <option value="" hidden>{{ $placeholder }}</option>
        @endif
        
        {{ $slot }}
    </select>

    <div class="absolute inset-y-0 right-0 pr-2 flex items-center pointer-events-none text-neutral-500 dark:text-neutral-500 z-10">
        <x-ui.icon name="chevron-up-down" class="size-5" />
    </div>
</div>
