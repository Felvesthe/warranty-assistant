@props([
    'title',
    'icon',
])

<x-ui.heading level="h2" size="md" class="flex items-center my-6">
    @isset($icon)
        <x-dynamic-component
            :component="'lucide-' . $icon"
            class="mr-2 w-5"
        />
    @endisset
    <span class="font-semibold">{{ $title }}</span>
</x-ui.heading>
