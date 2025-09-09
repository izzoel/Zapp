<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('akses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_role')->constrained('roles')->onDelete('cascade');
            $table->foreignId('id_menu')->constrained('menus')->onDelete('cascade');

            $table->boolean('create')->default(false);
            $table->boolean('read')->default(false);
            $table->boolean('update')->default(false);
            $table->boolean('delete')->default(false);
            $table->timestamps();
        });

        DB::table('akses')->insert(
            [
                'id' => 1,
                'id_menu' => 2,
                'id_role' => 1,
                'create' => true,
                'read' => true,
                'update' => true,
                'delete' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => 2,
                'id_menu' => 3,
                'id_role' => 1,
                'create' => true,
                'read' => true,
                'update' => true,
                'delete' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => 3,
                'id_menu' => 4,
                'id_role' => 1,
                'create' => true,
                'read' => true,
                'update' => true,
                'delete' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => 4,
                'id_menu' => 5,
                'id_role' => 1,
                'create' => true,
                'read' => true,
                'update' => true,
                'delete' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        );
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('akses');
    }
};
