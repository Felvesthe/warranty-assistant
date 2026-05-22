@props(['icon'])

<button {{ $attributes->merge(['class' => 'flex items-center gap-4 m-3 w-full active:opacity-70 transition-opacity']) }}>
    <x-dynamic-component
        :component="'lucide-' . $icon"
        class="p-2.5 w-10 text-indigo-600 bg-indigo-100 dark:text-indigo-400 dark:bg-indigo-900/30 rounded-full"
    />
    <div class="text-left">
        <x-ui.heading level="h2">{{ $heading }}</x-ui.heading>
        <p class="text-xs">{{ $text }}</p>
    </div>
</button>
