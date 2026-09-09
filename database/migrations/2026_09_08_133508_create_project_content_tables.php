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
        Schema::create('projects', function (Blueprint $table) {
            $table->id();
            $table->string('name', 160);
            $table->string('slug', 180)->unique();
            $table->string('division', 80)->index();
            $table->string('project_type', 120)->nullable();
            $table->string('status', 24)->index();
            $table->string('publication_status', 24)->default('draft')->index();
            $table->boolean('is_featured')->default(false)->index();
            $table->text('summary');
            $table->longText('overview')->nullable();
            $table->text('marketing_summary')->nullable();
            $table->string('location_summary')->nullable();
            $table->string('land_title')->nullable();
            $table->text('title_information')->nullable();
            $table->unsignedBigInteger('price_per_sqm')->nullable();
            $table->char('currency', 3)->default('NGN');
            $table->longText('allocation_details')->nullable();
            $table->longText('construction_details')->nullable();
            $table->text('disclaimer')->nullable();
            $table->string('canonical_path')->nullable()->unique();
            $table->string('seo_title', 70)->nullable();
            $table->string('seo_description', 170)->nullable();
            $table->timestamp('published_at')->nullable()->index();
            $table->unsignedInteger('sort_order')->default(0);
            $table->timestamps();
            $table->softDeletes();
            $table->index(['publication_status', 'published_at']);
        });

        Schema::create('project_locations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('project_id')->constrained()->cascadeOnDelete();
            $table->string('name', 160);
            $table->string('region', 160)->nullable();
            $table->string('country', 100)->default('Nigeria');
            $table->unsignedInteger('sort_order')->default(0);
            $table->timestamps();
            $table->unique(['project_id', 'name']);
        });

        Schema::create('project_media', function (Blueprint $table) {
            $table->id();
            $table->foreignId('project_id')->constrained()->cascadeOnDelete();
            $table->string('kind', 24)->index();
            $table->string('role', 40)->index();
            $table->string('disk', 40)->default('public');
            $table->string('path', 1024)->nullable();
            $table->text('external_url')->nullable();
            $table->string('mime_type', 120)->nullable();
            $table->string('alt_text')->nullable();
            $table->text('caption')->nullable();
            $table->string('credit')->nullable();
            $table->unsignedInteger('sort_order')->default(0);
            $table->timestamps();
            $table->index(['project_id', 'role', 'sort_order']);
        });

        Schema::create('project_plot_options', function (Blueprint $table) {
            $table->id();
            $table->foreignId('project_id')->constrained()->cascadeOnDelete();
            $table->string('label', 120);
            $table->decimal('size_sqm', 10, 2);
            $table->unsignedBigInteger('price');
            $table->char('currency', 3)->default('NGN');
            $table->unsignedInteger('sort_order')->default(0);
            $table->timestamps();
        });

        Schema::create('project_payment_plans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('project_id')->unique()->constrained()->cascadeOnDelete();
            $table->unsignedBigInteger('initial_deposit')->nullable();
            $table->char('currency', 3)->default('NGN');
            $table->string('balance_period', 120)->nullable();
            $table->text('note')->nullable();
            $table->timestamps();
        });

        Schema::create('project_charges', function (Blueprint $table) {
            $table->id();
            $table->foreignId('project_id')->constrained()->cascadeOnDelete();
            $table->string('label', 160);
            $table->string('value', 160);
            $table->unsignedInteger('sort_order')->default(0);
            $table->timestamps();
        });

        Schema::create('project_document_stages', function (Blueprint $table) {
            $table->id();
            $table->foreignId('project_id')->constrained()->cascadeOnDelete();
            $table->string('stage', 200);
            $table->unsignedInteger('sort_order')->default(0);
            $table->timestamps();
        });

        Schema::create('project_documents', function (Blueprint $table) {
            $table->id();
            $table->foreignId('project_document_stage_id')->constrained()->cascadeOnDelete();
            $table->string('name', 180);
            $table->unsignedInteger('sort_order')->default(0);
            $table->timestamps();
        });

        Schema::create('project_infrastructure', function (Blueprint $table) {
            $table->id();
            $table->foreignId('project_id')->constrained()->cascadeOnDelete();
            $table->string('name', 180);
            $table->unsignedInteger('sort_order')->default(0);
            $table->timestamps();
        });

        Schema::create('project_policies', function (Blueprint $table) {
            $table->id();
            $table->foreignId('project_id')->constrained()->cascadeOnDelete();
            $table->string('heading', 180);
            $table->longText('body');
            $table->unsignedInteger('sort_order')->default(0);
            $table->timestamps();
        });

        Schema::create('project_faq_groups', function (Blueprint $table) {
            $table->id();
            $table->foreignId('project_id')->constrained()->cascadeOnDelete();
            $table->string('slug', 160);
            $table->string('label', 200);
            $table->unsignedInteger('sort_order')->default(0);
            $table->timestamps();
            $table->unique(['project_id', 'slug']);
        });

        Schema::create('project_faqs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('project_faq_group_id')->constrained()->cascadeOnDelete();
            $table->string('key', 180);
            $table->string('question');
            $table->longText('answer');
            $table->json('points')->nullable();
            $table->text('note')->nullable();
            $table->boolean('is_featured')->default(false)->index();
            $table->unsignedInteger('sort_order')->default(0);
            $table->timestamps();
            $table->unique(['project_faq_group_id', 'key']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('project_faqs');
        Schema::dropIfExists('project_faq_groups');
        Schema::dropIfExists('project_policies');
        Schema::dropIfExists('project_infrastructure');
        Schema::dropIfExists('project_documents');
        Schema::dropIfExists('project_document_stages');
        Schema::dropIfExists('project_charges');
        Schema::dropIfExists('project_payment_plans');
        Schema::dropIfExists('project_plot_options');
        Schema::dropIfExists('project_media');
        Schema::dropIfExists('project_locations');
        Schema::dropIfExists('projects');
    }
};
