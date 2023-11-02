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
        Schema::create('role_permisos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('role_id')->unsigned()->index()->references('id')->on('roles');
            $table->foreignId('permiso_id')->unsigned()->index()->references('id')->on('permisos');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('role_permisos');
    }
};