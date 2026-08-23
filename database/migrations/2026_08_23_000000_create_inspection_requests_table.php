<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('inspection_requests', function (Blueprint $table) {
            $table->id();
            $table->uuid('submission_token')->unique();
            $table->string('reference', 20)->unique();
            $table->string('status', 30)->default('new')->index();
            $table->string('project_slug', 80)->index();
            $table->string('project_name', 160);
            $table->string('full_name', 120);
            $table->string('phone', 40);
            $table->string('whatsapp', 40)->nullable();
            $table->string('email', 160)->nullable();
            $table->string('preferred_contact_method', 20);
            $table->date('preferred_date')->index();
            $table->string('preferred_time', 30)->nullable();
            $table->text('message')->nullable();
            $table->timestamp('consented_at');
            $table->timestamp('staff_notified_at')->nullable();
            $table->timestamp('notification_failed_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('inspection_requests');
    }
};
