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
        Schema::create('file_permissions', function (Blueprint $table) {
            $table->id();

            $table->foreignId('file_id')
                ->constrained()
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
                'file_id',
                'principal_type',
                'principal_id',
            ], 'file_permissions_unique');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('file_permissions');
    }
};
