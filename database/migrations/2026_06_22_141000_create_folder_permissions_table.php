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
        Schema::create('folder_permissions', function (Blueprint $table) {
            $table->id();

            $table->foreignId('folder_id')
                ->constrained('file_folders')
                ->cascadeOnDelete();

            $table->string('principal_type');

            $table->unsignedBigInteger('principal_id');

            $table->enum('role', [
                'viewer',
                'contributor',
                'manager',
            ]);

            $table->timestamps();

            $table->unique([
                'folder_id',
                'principal_type',
                'principal_id',
            ], 'folder_permissions_unique');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('folder_permissions');
    }
};
