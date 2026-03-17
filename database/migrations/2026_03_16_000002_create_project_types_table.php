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
        Schema::create('project_types', function (Blueprint $table) {
            $table->id();
            $table->foreignId('group_id')->constrained('project_property_groups')->onDelete('restrict');
            $table->string('code', 80)->unique();
            $table->string('name', 200);
            $table->string('name_short', 80)->nullable();
            $table->enum('transaction_type', ['sale', 'rent', 'both'])->default('both');
            $table->unsignedSmallInteger('sort_order')->default(0);
            $table->tinyInteger('publish')->default(2);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('project_types');
    }
};
