<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('project_enquiries', function (Blueprint $table) {
            $table->id();
            $table->foreignId('project_id')->nullable()->constrained()->nullOnDelete();
            $table->uuid('submission_token')->unique();
            $table->string('reference', 20)->unique();
            $table->string('status', 30)->default('new')->index();
            $table->string('project_slug', 160)->index();
            $table->string('project_name', 160);
            $table->unsignedInteger('plot_size_sqm')->nullable();
            $table->string('plot_label', 120);
            $table->unsignedBigInteger('price_snapshot')->nullable();
            $table->char('currency', 3)->default('NGN');
            $table->string('payment_preference', 30);
            $table->string('purchase_timeline', 30);
            $table->string('full_name', 120);
            $table->string('phone', 40);
            $table->string('email', 160)->nullable();
            $table->string('whatsapp', 40)->nullable();
            $table->string('preferred_contact_method', 20);
            $table->text('message')->nullable();
            $table->text('admin_notes')->nullable();
            $table->dateTime('handled_at')->nullable();
            $table->dateTime('consented_at');
            $table->dateTime('staff_notified_at')->nullable();
            $table->dateTime('notification_failed_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('project_enquiries');
    }
};
