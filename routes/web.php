<?php

declare(strict_types=1);

Route::livewire('/', 'pages::dashboard.index')->name('dashboard');

Route::prefix('items')->as('items:')->group(function (): void {
    Route::livewire('create', 'pages::items.create')->name('create');
});
