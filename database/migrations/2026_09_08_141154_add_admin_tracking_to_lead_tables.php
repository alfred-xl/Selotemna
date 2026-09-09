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
        Schema::table('inspection_requests', function (Blueprint $table) {
            $table->text('admin_notes')->nullable()->after('message');
            $table->timestamp('handled_at')->nullable()->after('admin_notes')->index();
        });

        Schema::table('contact_enquiries', function (Blueprint $table) {
            $table->text('admin_notes')->nullable()->after('message');
            $table->timestamp('handled_at')->nullable()->after('admin_notes')->index();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('inspection_requests', function (Blueprint $table) {
            $table->dropColumn(['admin_notes', 'handled_at']);
        });

        Schema::table('contact_enquiries', function (Blueprint $table) {
            $table->dropColumn(['admin_notes', 'handled_at']);
        });
    }
};
