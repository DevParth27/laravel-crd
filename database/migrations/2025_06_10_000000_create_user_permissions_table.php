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
        Schema::create('user_permissions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('excel_file_id')->constrained('excel_files')->onDelete('cascade');
            $table->boolean('can_view')->default(false);
            $table->boolean('can_delete')->default(false);
            $table->timestamps();
            
            // Ensure each user has only one permission record per file
            $table->unique(['user_id', 'excel_file_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_permissions');
    }
};