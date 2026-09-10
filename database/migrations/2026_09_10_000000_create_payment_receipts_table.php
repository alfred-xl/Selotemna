<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('payment_receipts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('project_enquiry_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('project_id')->nullable()->constrained()->nullOnDelete();
            $table->string('receipt_number', 32)->nullable()->unique();
            $table->string('status', 20)->default('draft')->index();
            $table->string('customer_name', 120);
            $table->string('customer_phone', 40);
            $table->string('customer_email', 160)->nullable();
            $table->string('project_name', 160);
            $table->string('project_slug', 160)->nullable();
            $table->unsignedInteger('plot_size_sqm')->nullable();
            $table->string('plot_label', 120)->nullable();
            $table->char('currency', 3)->default('NGN');
            $table->unsignedBigInteger('amount_received');
            $table->string('payment_purpose', 160);
            $table->string('payment_method', 40);
            $table->date('payment_date');
            $table->string('transaction_reference', 120)->nullable()->index();
            $table->unsignedBigInteger('balance_remaining')->nullable();
            $table->text('notes')->nullable();
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('issued_by')->nullable()->constrained('users')->nullOnDelete();
            $table->string('issuer_name', 120)->nullable();
            $table->dateTime('issued_at')->nullable();
            $table->foreignId('voided_by')->nullable()->constrained('users')->nullOnDelete();
            $table->dateTime('voided_at')->nullable();
            $table->text('void_reason')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('payment_receipts');
    }
};
