<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('avatar');
            $table->string('name');
            $table->string('password');
            $table->foreignId('id_role')->constrained('roles')->onDelete('cascade');
            $table->timestamps();
        });

        DB::table('users')->insert([
            'id' => 1,
            'avatar' => rand(0, 11) . '.png',
            'name' => 'admin',
            'password' => Hash::make('nimda'),
            'id_role' => 1,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
