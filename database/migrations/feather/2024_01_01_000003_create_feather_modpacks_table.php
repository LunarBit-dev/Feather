<?php

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
        Schema::create('feather_modpacks', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('description')->nullable();
            $table->string('game'); // minecraft, valheim, etc.
            $table->string('version');
            $table->string('source'); // curseforge, modrinth, manual
            $table->string('external_id')->nullable(); // ID from external source
            $table->json('metadata')->nullable(); // Additional data
            $table->string('download_url')->nullable();
            $table->bigInteger('file_size')->nullable();
            $table->string('file_hash')->nullable();
            $table->json('requirements')->nullable(); // Server requirements
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->index(['game', 'is_active']);
            $table->index(['source', 'external_id']);
            $table->unique(['source', 'external_id', 'version']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('feather_modpacks');
    }
};
