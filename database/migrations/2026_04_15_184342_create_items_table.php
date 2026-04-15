<?php

declare(strict_types=1);

use App\Enums\Category;
use App\Enums\Warranty;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('items', function (Blueprint $table) {
            $table->id();

            $table->string('name');

            $table->enum('category', Category::cases());
            $table->enum('warranty_period', Warranty::cases());

            $table->date('date_of_purchase');

            $table->integer('price');

            $table->string('serial_number')->nullable();
            $table->string('notes', 500)->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('items');
    }
};
