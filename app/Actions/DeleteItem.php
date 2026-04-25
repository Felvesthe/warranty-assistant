<?php

declare(strict_types=1);

namespace App\Actions;

use App\Models\Item;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

final class DeleteItem
{
    /**
     * @throws \Throwable
     */
    public function execute(Item $item): void
    {
        DB::transaction(function () use ($item): void {
            if ($item->file !== null) {
                Storage::delete($item->file->path);
                $item->file->delete();
            }

            $item->delete();
        });
    }
}
