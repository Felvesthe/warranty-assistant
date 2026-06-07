@props([
    'variant' => 'dropdown',
    'darkIcon'=>'moon',
    'lightIcon'=>'sun',
    'systemIcon'=>'computer-desktop',
    'iconVariant' => "mini"
])

@if ($variant === 'dropdown')
    <x-ui.theme-switcher.variants.dropdown/>
@elseif($variant === 'stacked')
    <x-ui.theme-switcher.variants.stacked/>
@elseif($variant === 'inline')
    <x-ui.theme-switcher.variants.inline/>
@endif
