<?php

declare(strict_types=1);

Route::livewire('/', 'pages::dashboard.index')->name('dashboard');

Route::prefix('items')->as('items:')->group(function (): void {
    Route::livewire('/', 'pages::items.index')->name('index');
    Route::livewire('create', 'pages::items.form')->name('create');
    Route::livewire('{item}', 'pages::items.show')->name('show');
    Route::livewire('{item}/edit', 'pages::items.form')->name('edit');

    Route::livewire('{item}/services', 'pages::services.index')->name('services:index');
    Route::livewire('{item}/services/create', 'pages::services.create')->name('services:create');
});

Route::livewire('settings', 'pages::settings.index')->name('settings:index');
