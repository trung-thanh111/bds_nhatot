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
        Schema::table('visit_requests', function (Blueprint $table) {
            // Check if the constraint exists before dropping to be safe, 
            // but since we know it exists from tinker, we drop it.
            $table->dropForeign('visit_requests_property_id_foreign');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('visit_requests', function (Blueprint $table) {
            $table->foreign('project_id', 'visit_requests_property_id_foreign')->references('id')->on('properties')->onDelete('cascade');
        });
    }
};
