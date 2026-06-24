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
        Schema::create('file_folders', function (Blueprint $table) {
            $table->id();

            $table->foreignId('parent_id')
                ->nullable()
                ->constrained('file_folders')
                ->nullOnDelete();

            $table->string('name');
            $table->text('description')->nullable();

            $table->foreignId('owner_id')
                ->constrained('users')
                ->cascadeOnDelete();

            $table->enum('visibility', [
                'private',
                'public',
                'link',
            ])->default('private');

            $table->string('share_token')
                ->nullable()
                ->unique();

            $table->timestamps();
            $table->softDeletes();

            $table->index('parent_id');
            $table->index('owner_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('file_folders');
    }
};
