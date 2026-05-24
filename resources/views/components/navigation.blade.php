<?php

use App\Settings\UserSettings;
use Livewire\Attributes\On;
use Livewire\Component;

return new class extends Component {
    public ?string $theme;

    public function mount(UserSettings $userSettings): void
    {
        $this->setThemeProperty($userSettings);
    }

    #[On('persist-theme-change')]
    public function handleThemeChange(UserSettings $userSettings): void
    {
        $this->setThemeProperty($userSettings);
    }

    private function setThemeProperty(UserSettings $userSettings): void
    {
        $this->theme = match ($userSettings->theme) {
            'light' => false,
            'dark' => true,
            default => null,
        };
    }
}
?>

<div>
    <native:bottom-nav label-visibility="labeled" :dark="$theme" active-color="#6366f1">
        <native:bottom-nav-item
            id="dashboard"
            icon="home"
            :label="__('Dashboard')"
            :url="route('dashboard')"
            :active="Route::is('dashboard')"
        />
        <native:bottom-nav-item
            id="items"
            icon="products"
            :label="__('Items')"
            :url="route('items:index')"
            :active="Route::is('items:index')"
        />
        <native:bottom-nav-item
            id="add-item"
            icon="add"
            :label="__('Add')"
            :url="route('items:create')"
            :active="Route::is('items:create')"
        />
        <native:bottom-nav-item
            id="settings"
            icon="settings"
            :label="__('Settings')"
            :url="route('settings:index')"
            :active="Route::is('settings:*')"
        />
    </native:bottom-nav>

</div>
