@props([
    'icon' => 'circle-question-mark',
    'iconTextColor' => 'text-blue-600',
    'iconBgColor' => 'bg-blue-100',
    'title',
    'value',
])

<x-ui.card class="flex flex-col p-3 tracking-wide shadow rounded-xl">
    <x-dynamic-component
        :component="'lucide-' . $icon"
        :class="'mb-3 p-2 w-10 rounded-full ' . $iconTextColor . ' ' . $iconBgColor"
    />
    <p class="mb-1 text-neutral-500 dark:text-neutral-400 text-sm font-semibold uppercase">{{ $title }}</p>
    <p class="text-base font-bold">
        {{ $value }}
    </p>
</x-ui.card>
