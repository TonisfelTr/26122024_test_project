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
        Schema::create('user_setting_changes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_setting_id')->constrained('user_settings')->cascadeOnDelete();
            $table->string('new_value');
            $table->string('confirmation_code');
            $table->enum('method', ['sms', 'email', 'telegram']);
            $table->timestamp('confirmed_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_setting_changes');
    }
};
