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
        Schema::create('admin_infos', function (Blueprint $table) {
            $table->id(); // AUTO INCREMENT
            $table->string('title')->nullable();
            $table->string('image')->nullable(); // Bisa null kalau admin nggak upload gambar
            $table->text('content')->nullable();
            $table->timestamps(); // created_at & updated_at
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('admin_infos');
    }
};
