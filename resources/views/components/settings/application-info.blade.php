<div>
    <x-ui.heading level="h2" size="xs" class="mb-1 uppercase font-bold">{{ __('Application') }}</x-ui.heading>
    <x-ui.card class="p-0 text-sm shadow">
        <div class="flex items-center gap-5 m-3">
            <x-lucide-smartphone class="p-2.5 w-10 text-indigo-600 bg-indigo-100 rounded-full"/>
            <div>
                <x-ui.heading level="h2">{{ __('Version') }}</x-ui.heading>
                <p class="text-xs">{{ config()->string('app.version') }}</p>
            </div>
        </div>

        <x-ui.separator/>

        <div class="flex items-center gap-5 m-3">
            <x-lucide-user class="p-2.5 w-10 text-indigo-600 bg-indigo-100 rounded-full"/>
            <div>
                <x-ui.heading level="h2">{{ __('Author') }}</x-ui.heading>
                <p class="text-xs">Sebastian Bobiński</p>
            </div>
        </div>

        <x-ui.separator/>

        <a href="https://github.com/Felvesthe/warranty-assistant" class="flex items-center gap-5 m-3">
            <x-lucide-code class="p-2.5 w-10 text-indigo-600 bg-indigo-100 rounded-full"/>
            <div>
                <x-ui.heading level="h2">{{ __('Source code') }}</x-ui.heading>
                <p class="text-xs">github.com/Felvesthe/warranty-assistant</p>
            </div>
        </a>
    </x-ui.card>
</div>
