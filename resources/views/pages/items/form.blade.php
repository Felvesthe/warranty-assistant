<?php

use App\Enums\Category;
use App\Enums\Warranty;
use App\Livewire\Forms\ItemForm;
use App\Models\Item;
use Carbon\Carbon;
use Livewire\Attributes\On;
use Livewire\Component;
use Native\Mobile\Attributes\OnNative;
use Native\Mobile\Events\Alert\ButtonPressed;
use Native\Mobile\Events\Camera\PermissionDenied;
use Native\Mobile\Events\Camera\PhotoCancelled;
use Native\Mobile\Events\Camera\PhotoTaken;
use Native\Mobile\Events\Gallery\MediaSelected;
use Native\Mobile\Facades\Camera;
use Native\Mobile\Facades\Dialog;
use Native\Mobile\Facades\File;
use Native\Mobile\Facades\System;
use Symfony\Component\Mime\MimeTypes;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

new class extends Component {
    public ?Item $item = null;
    public ItemForm $form;

    public function mount(): void
    {
        if ($this->item !== null) {
            $this->form->setItem($this->item);
        }
    }

    public function save(): void
    {
        $this->item === null
            ? $this->form->store()
            : $this->form->update();

        $this->redirectRoute('items:index');
    }

    public function takePhoto(): void
    {
        Camera::getPhoto();
    }

    public function choosePhoto(): void
    {
        Camera::pickImages(
            media_type: 'image',
        );
    }

    #[OnNative(PhotoTaken::class)]
    public function handlePhotoTaken(string $path): void
    {
        $this->processImage($path);
    }

    #[OnNative(MediaSelected::class)]
    public function handleMediaSelected(bool $success, array $files): void
    {
        foreach ($files as $key => $file) {
            $fileExtension = MimeTypes::getDefault()->getExtensions($file['mimeType'])[0];
            $this->processImage($file['path'], $fileExtension);
        }
    }

    #[OnNative(PermissionDenied::class)]
    public function permissionDenied(): void
    {
        Dialog::alert(
            __('permissions.no_permission'),
            __('permissions.grant_camera_access'),
            [__('Cancel'), __('Settings')]
        )
            ->id('open-app-settings')
            ->show();
    }

    #[OnNative(ButtonPressed::class)]
    public function handleButton(int $index, string $label, ?string $id = null): void
    {
        if ($id === 'open-app-settings' && $label === __('Settings')) {
            System::appSettings();
        }
    }

    public function deleteUploadedImage(): void
    {
        if (Storage::delete($this->form->proofOfPurchase)) {
            $this->form->proofOfPurchase = null;
        }
    }

    private function processImage(string $path, ?string $extension = null): void
    {
        $fileName = now()->format('Ymd') . '_' . Str::random(10) . '.' . $extension ?? pathinfo($path, PATHINFO_EXTENSION);
        $this->form->proofOfPurchase = Storage::putFileAs('documents', $path, $fileName);
    }
};
?>

<div>
    @if ($item !== null)
        <div class="flex items-center gap-4">
            <a href="{{ route('items:show', $item) }}" wire:navigate>
                <x-lucide-chevron-left class="w-6"/>
            </a>
            <x-page-heading>
                {{ __('items.form.edit_item') }}
            </x-page-heading>
        </div>
    @else
        <x-page-heading>
            {{ __('items.form.add_item') }}
        </x-page-heading>
    @endif

    <form wire:submit="save" class="grid grid-cols-2 gap-x-2.5 gap-y-5">
        @csrf

        <x-ui.fieldset class="col-span-full shadow">
            @if ($form->proofOfPurchase && Storage::exists($form->proofOfPurchase))
                <div class="relative flex justify-center items-center">
                    <img src="{{ Storage::url($form->proofOfPurchase) }}" alt="" class="max-w-32">
                    <button wire:click="deleteUploadedImage" class="absolute top-0 right-0 p-2 bg-primary text-primary-content rounded-lg cursor-pointer">
                        <x-ui.icon name="x-circle"/>
                    </button>
                </div>
            @else
                <x-ui.field class="grid grid-cols-2 gap-x-2.5" required>
                    <x-ui.label class="col-span-full text-xs uppercase">
                        {{ __('validation.attributes.proof_of_purchase') }}
                    </x-ui.label>

                    <x-ui.card size="xl" class="flex justify-center items-center flex-col text-center"
                               wire:click="takePhoto">
                        <x-lucide-camera class="mb-3 p-3 w-12 text-indigo-600 bg-indigo-100 dark:text-indigo-400 dark:bg-indigo-900/30 rounded-full"/>
                        <p class="mb-2 text-sm font-semibold">{{ __('items.form.take_photo') }}</p>
                        <p class="text-xs">{{ __('items.form.touch_to_use_camera') }}</p>
                    </x-ui.card>

                    <x-ui.card size="xl" class="flex justify-center items-center flex-col text-center"
                               wire:click="choosePhoto">
                        <x-lucide-images class="mb-3 p-3 w-12 text-indigo-600 bg-indigo-100 dark:text-indigo-400 dark:bg-indigo-900/30 rounded-full"/>
                        <p class="mb-2 text-sm font-semibold">{{ __('items.form.choose_photo') }}</p>
                        <p class="text-xs">{{ __('items.form.touch_to_choose_photo') }}</p>
                    </x-ui.card>

                    <x-ui.error name="form.proofOfPurchase" class="col-span-full"/>
                </x-ui.field>
            @endif
        </x-ui.fieldset>

        <x-ui.field class="col-span-full" required>
            <x-ui.label class="text-xs uppercase">{{ __('items.item_name') }}</x-ui.label>
            <x-ui.input
                wire:model.live.debounce="form.name"
                :placeholder="__('items.form.item_name_placeholder')"
                leftIcon="document"
            />
            <x-ui.error name="form.name"/>
        </x-ui.field>

        <x-ui.field required>
            <x-ui.label class="text-xs uppercase">{{ __('Category') }}</x-ui.label>
            <x-ui.select
                wire:model.live.debounce="form.category"
                icon="tag"
                :placeholder="__('items.form.choose_category')"
                class="text-sm"
            >
                @foreach(Category::cases() as $key => $value)
                    <x-ui.select.option :$value :wire:key="$key">
                        {{ $value->label() }}
                    </x-ui.select.option>
                @endforeach
            </x-ui.select>
            <x-ui.error name="form.category"/>
        </x-ui.field>

        <x-ui.field required>
            <x-ui.label class="text-xs uppercase">{{ __('Price') }}</x-ui.label>
            <x-ui.input
                wire:model.live.debounce="form.price"
                x-mask:dynamic="$money($input, ',', ' ')"
                placeholder="0,00"
                leftIcon="currency-dollar"
            />
            <x-ui.error name="form.price"/>
        </x-ui.field>

        <x-ui.field required>
            <x-ui.label class="text-xs uppercase">{{ __('validation.attributes.date_of_purchase') }}</x-ui.label>
            <x-ui.input
                wire:model.live.debounce="form.dateOfPurchase"
                x-mask="99/99/9999"
                :placeholder="__('items.form.date_placeholder')"
                leftIcon="calendar"
            />
            <x-ui.error name="form.dateOfPurchase"/>
        </x-ui.field>

        <x-ui.field required>
            <x-ui.label class="text-xs uppercase">{{ __('Warranty') }}</x-ui.label>
            <x-ui.select
                wire:model.live.debounce="form.warranty"
                icon="calendar-date-range"
                :placeholder="__('items.form.choose_warranty')"
                class="text-sm"
            >
                @foreach(Warranty::cases() as $key => $value)
                    <x-ui.select.option :$value :wire:key="$key">
                        {{ $value->label() }}
                    </x-ui.select.option>
                @endforeach
            </x-ui.select>
            <x-ui.error name="form.warranty"/>
        </x-ui.field>

        <x-ui.field class="col-span-full">
            <x-ui.label class="text-xs uppercase">{{ __('validation.attributes.serial_number') }}</x-ui.label>
            <x-ui.input
                wire:model.live.debounce="form.serialNumber"
                placeholder="20260417-PRUP"
                leftIcon="identification"
            />
            <x-ui.error name="form.serialNumber"/>
        </x-ui.field>

        <x-ui.field class="col-span-full">
            <x-ui.label class="text-xs uppercase">{{ __('items.form.additional_notes') }}</x-ui.label>
            <x-ui.textarea
                wire:model.live.debounce="form.notes"
                :placeholder="__('items.form.notes')"
                resize="none"
                class="text-sm"
            />
            <x-ui.error name="form.notes"/>
        </x-ui.field>

        <x-ui.button type="submit" size="lg" icon="plus-circle" color="indigo" class="col-span-full shadow">
            {{ __('Save') }}
        </x-ui.button>
    </form>
</div>
