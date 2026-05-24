<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('office_reminder_schedules', function (Blueprint $table) {
            $table->id();
            $table->string('office');
            $table->text('message')->nullable();
            $table->time('send_time');
            $table->boolean('is_enabled')->default(true);
            $table->date('last_sent_on')->nullable();
            $table->unsignedBigInteger('created_by');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('office_reminder_schedules');
    }
};
