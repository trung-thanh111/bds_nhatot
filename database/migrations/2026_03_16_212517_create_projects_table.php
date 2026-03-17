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
        Schema::create('projects', function (Blueprint $blueprint) {
            $blueprint->id();
            $blueprint->string('code', 20)->unique()->nullable();
            $blueprint->string('name');
            $blueprint->string('slug')->unique();
            $blueprint->integer('catalogue_id')->default(0);
            $blueprint->string('type_code', 50)->nullable();
            $blueprint->string('property_group', 50)->nullable();
            $blueprint->string('transaction_type', 20)->default('sale');
            $blueprint->tinyInteger('is_project')->default(0);

            // Nội dung & SEO
            $blueprint->text('summary')->nullable();
            $blueprint->longText('description')->nullable();
            $blueprint->text('meta_title')->nullable();
            $blueprint->text('meta_desc')->nullable();
            $blueprint->string('focus_keyword')->nullable();

            // Giá cả & Diện tích
            $blueprint->decimal('price', 15, 2)->nullable();
            $blueprint->string('price_unit', 20)->default('total');
            $blueprint->decimal('price_vnd', 15, 2)->nullable();
            $blueprint->tinyInteger('price_negotiable')->default(0);
            $blueprint->decimal('area', 10, 2)->nullable();
            $blueprint->decimal('area_use', 10, 2)->nullable();
            $blueprint->decimal('area_land', 10, 2)->nullable();
            $blueprint->decimal('length', 10, 2)->nullable();
            $blueprint->decimal('width', 10, 2)->nullable();

            // Đặc điểm chi tiết
            $blueprint->integer('bedrooms')->nullable();
            $blueprint->integer('bathrooms')->nullable();
            $blueprint->integer('floors')->nullable();
            $blueprint->string('floor_number', 50)->nullable();
            $blueprint->string('direction', 20)->nullable();
            $blueprint->string('balcony_direction', 20)->nullable();
            $blueprint->string('legal_status', 50)->nullable();
            $blueprint->string('furniture_status', 50)->nullable();

            // Tiện ích (Boolean)
            $blueprint->tinyInteger('has_elevator')->default(0);
            $blueprint->tinyInteger('has_pool')->default(0);
            $blueprint->tinyInteger('has_parking')->default(0);
            $blueprint->tinyInteger('has_security')->default(0);
            $blueprint->tinyInteger('has_balcony')->default(0);
            $blueprint->tinyInteger('has_rooftop')->default(0);
            $blueprint->tinyInteger('has_basement')->default(0);
            $blueprint->tinyInteger('has_gym')->default(0);
            $blueprint->tinyInteger('has_ac')->default(0);
            $blueprint->tinyInteger('has_wifi')->default(0);

            // Địa chỉ CŨ (trước 01/07/2025)
            $blueprint->string('province_code', 50)->nullable();
            $blueprint->string('province_name', 100)->nullable();
            $blueprint->string('district_code', 50)->nullable();
            $blueprint->string('district_name', 100)->nullable();
            $blueprint->string('ward_code', 50)->nullable();
            $blueprint->string('ward_name', 100)->nullable();

            // Địa chỉ MỚI (sau sáp nhập)
            $blueprint->string('province_new_code', 50)->nullable();
            $blueprint->string('province_new_name', 100)->nullable();
            $blueprint->string('ward_new_code', 50)->nullable();
            $blueprint->string('ward_new_name', 100)->nullable();

            $blueprint->string('address')->nullable();
            $blueprint->decimal('latitude', 10, 7)->nullable();
            $blueprint->decimal('longitude', 10, 7)->nullable();

            // Hình ảnh & Truyền thông
            $blueprint->string('image')->nullable();
            $blueprint->text('album')->nullable();
            $blueprint->tinyInteger('has_video')->default(0);
            $blueprint->string('video_url', 255)->nullable();
            $blueprint->text('video_embed')->nullable();
            $blueprint->tinyInteger('has_virtual_tour')->default(0);
            $blueprint->string('virtual_tour_url', 255)->nullable();

            // Metadata & Trạng thái
            $blueprint->json('extra_fields')->nullable();
            $blueprint->string('status', 20)->default('active');
            $blueprint->tinyInteger('publish')->default(2);
            $blueprint->tinyInteger('is_featured')->default(0);
            $blueprint->tinyInteger('is_hot')->default(0);
            $blueprint->tinyInteger('is_urgent')->default(0);
            $blueprint->integer('sort_order')->default(0);
            $blueprint->bigInteger('view_count')->default(0);
            $blueprint->timestamp('published_at')->nullable();

            $blueprint->softDeletes();
            $blueprint->timestamps();

            // Fulltext index for search
            $blueprint->fullText(['name', 'address', 'summary']);
            
            $blueprint->index('catalogue_id');
            $blueprint->index('type_code');
            $blueprint->index('property_group');
            $blueprint->index('transaction_type');
            $blueprint->index('publish');
            $blueprint->index('status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('projects');
    }
};
