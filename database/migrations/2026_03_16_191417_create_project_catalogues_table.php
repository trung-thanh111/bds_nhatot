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
        Schema::create('project_catalogues', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('parent_id')->nullable();
            $table->foreignId('property_group_id')->nullable()->constrained('project_property_groups')->onDelete('set null');
            
            $table->string('name', 200);
            $table->string('slug', 200)->unique();
            $table->enum('transaction_type', ['sale', 'rent', 'both'])->default('both')->nullable();
            $table->string('icon_url', 300)->nullable();
            
            $table->integer('lft')->default(0);
            $table->integer('rgt')->default(0);
            $table->unsignedTinyInteger('level')->default(1);
            $table->string('path', 100)->nullable();
            
            $table->unsignedSmallInteger('sort_order')->default(0);
            $table->tinyInteger('publish')->default(2);
            
            $table->string('meta_title', 200)->nullable();
            $table->string('meta_desc', 400)->nullable();
            
            $table->softDeletes();
            $table->timestamps();

            $table->foreign('parent_id')->references('id')->on('project_catalogues')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('project_catalogues');
    }
};
