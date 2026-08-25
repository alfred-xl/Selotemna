<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('contact_enquiries', function (Blueprint $table) {
            $table->id();
            $table->uuid('submission_token')->unique();
            $table->string('reference', 20)->unique();
            $table->string('status', 30)->default('new')->index();
            $table->string('full_name', 120);
            $table->string('phone', 40);
            $table->string('email', 160)->nullable();
            $table->string('whatsapp', 40)->nullable();
            $table->string('preferred_contact_method', 20);
            $table->string('enquiry_type', 80)->index();
            $table->string('project_type', 160)->nullable();
            $table->string('proposed_location', 160)->nullable();
            $table->string('project_stage', 160)->nullable();
            $table->text('scope_summary')->nullable();
            $table->text('message');
            $table->timestamp('consented_at');
            $table->timestamp('staff_notified_at')->nullable();
            $table->timestamp('notification_failed_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('contact_enquiries');
    }
};
