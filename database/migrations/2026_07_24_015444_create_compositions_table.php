<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('compositions', function (Blueprint $table) {
            $table->id();

            $table->foreignId('user_id')
                ->constrained()
                ->cascadeOnDelete();

            $table->string('title');

            $table->text('description')->nullable();

            $table->enum('type', [
                'song',
                'setlist',
                'chord_chart',
                'lead_sheet',
                'lyrics',
                'sheet_music',
                'audio',
                'backing_track',
            ]);

            $table->string('file_path');
            $table->string('file_name');
            $table->string('mime_type')->nullable();
            $table->unsignedBigInteger('file_size')->nullable();

            $table->boolean('is_featured')->default(false);

            $table->enum('status', [
                'draft',
                'published',
                'archived',
            ])->default('draft');

            $table->unsignedInteger('downloads')->default(0);

            $table->timestamp('published_at')->nullable();

            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('compositions');
    }
};
