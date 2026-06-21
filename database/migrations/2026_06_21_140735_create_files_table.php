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
        Schema::create('files', function (Blueprint $table) {
            $table->id();

            $table->foreignId('folder_id')
                ->nullable()
                ->constrained('file_folders')
                ->nullOnDelete();

            $table->string('name');
            $table->string('original_name');

            $table->string('mime_type');
            $table->string('extension', 20)->nullable();

            $table->unsignedBigInteger('size');

            $table->string('disk');
            $table->string('path');

            $table->foreignId('uploaded_by')
                ->constrained('users')
                ->cascadeOnDelete();

            $table->timestamps();
            $table->softDeletes();

            $table->index('folder_id');
            $table->index('uploaded_by');
            $table->index('mime_type');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('files');
    }
};
