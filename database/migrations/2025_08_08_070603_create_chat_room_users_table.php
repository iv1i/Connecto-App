<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('chat_room_users', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('chat_room_id')->constrained()->onDelete('cascade');
            $table->string('joined_via')->nullable()->comment('Метод присоединения: invite_code, direct_join и т.д.');
            $table->timestamp('joined_at')->useCurrent();
            $table->timestamp('last_read_at')->nullable();
            $table->timestamps();

            $table->unique(['user_id', 'chat_room_id']); // Уникальная пара пользователь-комната
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('chat_room_user');
    }
};
