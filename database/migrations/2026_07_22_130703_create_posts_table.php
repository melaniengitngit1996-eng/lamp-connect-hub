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
        Schema::create('posts', function (Blueprint $table) {
            $table->id();

            $table->foreignId('user_id')->constrained()->cascadeOnDelete();

            $table->enum('type', [
                'normal',
                'announcement',
                'event',
                'prayer',
                'testimony',
                'devotional',
            ])->default('normal');

            $table->longText('content');

            $table->enum('visibility', [
                'everyone',
                'local_church',
                'cluster',
                'ministry',
                'members',
            ])->default('everyone');

            $table->boolean('allow_comments')->default(true);

            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('posts');
    }
};
