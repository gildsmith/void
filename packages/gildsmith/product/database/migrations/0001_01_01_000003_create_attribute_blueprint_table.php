<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('attribute_blueprint', function (Blueprint $table) {
            $table->id();
            $table->foreignId('attribute_id')->constrained()->cascadeOnUpdate()->cascadeOnDelete();
            $table->foreignId('blueprint_id')->constrained()->cascadeOnUpdate()->cascadeOnDelete();
            $table->boolean('required')->default(false);
            $table->unique(['attribute_id', 'blueprint_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('attribute_blueprint');
    }
};
