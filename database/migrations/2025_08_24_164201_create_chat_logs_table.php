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
        Schema::create('chat_logs', function (Blueprint $table) {
            $table->id();
            $table->string('room_code'); // ルーム識別コード
            $table->string('name');             // 投稿者名
            $table->text('message')->nullable();// 本文（入室・退室時はnullでも可）
            $table->string('name_color', 100)->default('#000000');
            $table->string('log_color', 100)->default('#000000');
            $table->enum('status', ['enter', 'leave', 'log'])->default('log');
            $table->boolean('visible')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('chat_logs');
    }
};
