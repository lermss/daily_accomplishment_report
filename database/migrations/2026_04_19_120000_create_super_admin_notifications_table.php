<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('super_admin_notifications', function (Blueprint $table) {
            $table->id();
            $table->string('source_key')->nullable()->unique();
            $table->string('title');
            $table->text('message');
            $table->string('type', 20);
            $table->boolean('read_status')->default(false);
            $table->timestamp('read_at')->nullable();
            $table->string('action_label')->nullable();
            $table->string('action_url')->nullable();
            $table->json('meta')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('super_admin_notifications');
    }
};
