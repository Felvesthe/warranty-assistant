@props(['src', 'alt' => ''])

<div x-data="{ open: false }" class="relative">
    <img
        src="{{ $src }}"
        alt="{{ $alt }}"
        @click="open = true"
        {{ $attributes->merge(['class' => 'transition-opacity rounded-lg']) }}
    >

    <template x-teleport="body">
        <div
            x-show="open"
            x-transition:enter="transition ease-out duration-300"
            x-transition:enter-start="opacity-0"
            x-transition:enter-end="opacity-100"
            x-transition:leave="transition ease-in duration-200"
            x-transition:leave-start="opacity-100"
            x-transition:leave-end="opacity-0"
            @click="open = false"
            class="fixed inset-0 flex justify-center items-center p-4 bg-black/80 backdrop-blur-sm"
        >
            <x-lucide-x class="absolute top-6 right-3 w-10 h-10 text-white/70" />

            <img
                :src="open ? '{{ $src }}' : ''"
                alt="{{ $alt }}"
                class="max-w-full max-h-full object-contain shadow-2xl rounded-sm"
                @click.stop
            >
        </div>
    </template>
</div>
