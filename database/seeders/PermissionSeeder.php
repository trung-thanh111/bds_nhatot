<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $modules = [
            'realestate.property_group' => 'Nhóm BĐS',
            'realestate.project_type' => 'Loại hình BĐS',
            'realestate.project_catalogue' => 'Danh mục BĐS',
            'realestate.project' => 'Dự án / Tin đăng',
            'realestate.project_item' => 'Sản phẩm dự án',
            'realestate.contact_request' => 'Liên hệ BĐS',
            'realestate.project_view' => 'Lượt xem BĐS',
        ];

        $permissions = [
            'index' => 'Xem danh sách',
            'create' => 'Thêm mới',
            'update' => 'Cập nhật',
            'destroy' => 'Xóa',
        ];

        foreach ($modules as $moduleCanonical => $moduleName) {
            foreach ($permissions as $permissionCanonical => $permissionName) {
                $canonical = $moduleCanonical . '.' . $permissionCanonical;
                $name = $permissionName . ' ' . $moduleName;

                DB::table('permissions')->updateOrInsert(
                    ['canonical' => $canonical],
                    [
                        'name' => $name,
                        'canonical' => $canonical,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]
                );
            }
        }
    }
}
