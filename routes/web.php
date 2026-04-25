<?php

declare(strict_types=1);

Route::livewire('/', 'pages::dashboard.index')->name('dashboard');

Route::prefix('items')->as('items:')->group(function (): void {
    Route::livewire('/', 'pages::items.index')->name('index');
    Route::livewire('create', 'pages::items.create')->name('create');
    Route::livewire('{item}', 'pages::items.show')->name('show');
});
