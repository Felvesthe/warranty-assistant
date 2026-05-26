@props(['src', 'alt' => ''])

<div x-data="{ open: false }" class="relative">
    <img
        src="{{ $src }}"
        alt="{{ $alt }}"
        @click="open = true"
        {{ $attributes->merge(['class' => 'transition-opacity duration-200 active:opacity-70 cursor-pointer rounded-lg']) }}
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
            <button type="button" @click="open = false" 
                    class="absolute p-2.5 bg-white/10 active:scale-95 active:bg-white/30 border border-white/10 backdrop-blur-md rounded-full text-white transition cursor-pointer z-50"
                    style="top: calc(env(safe-area-inset-top, 0px) + 1rem); right: calc(env(safe-area-inset-right, 0px) + 1rem);">
                <x-lucide-x class="w-7 h-7" />
            </button>

            <img
                :src="open ? '{{ $src }}' : ''"
                alt="{{ $alt }}"
                class="max-w-full max-h-full object-contain shadow-2xl rounded-sm"
                @click.stop
            >
        </div>
    </template>
</div>
