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
        Schema::table('project_catalogues', function (Blueprint $table) {
            $table->string('type_code', 50)->nullable()->after('property_group_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('project_catalogues', function (Blueprint $table) {
            $table->dropColumn('type_code');
        });
    }
};
