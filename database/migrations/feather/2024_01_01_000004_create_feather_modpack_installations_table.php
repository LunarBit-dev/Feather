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
        Schema::create('feather_modpack_installations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('server_id')->constrained()->cascadeOnDelete();
            $table->foreignId('modpack_id')->constrained('feather_modpacks')->cascadeOnDelete();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete(); // Who installed it
            $table->string('version_installed');
            $table->enum('status', ['pending', 'installing', 'completed', 'failed', 'rolled_back']);
            $table->text('installation_log')->nullable();
            $table->json('installation_data')->nullable(); // Custom data
            $table->timestamp('installed_at')->nullable();
            $table->timestamps();

            $table->index(['server_id', 'status']);
            $table->index(['modpack_id']);
            $table->index(['user_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('feather_modpack_installations');
    }
};
