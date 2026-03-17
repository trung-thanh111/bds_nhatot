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
            'realestate.property_group' => 'Nhóm bất động sản',
            'realestate.project_type' => 'Loại hình bất động sản',
            'realestate.project_catalogue' => 'Danh mục bất động sản',
            'realestate.project' => 'Danh sách dự án',
            'realestate.project_item' => 'Sản phẩm dự án',
            'realestate.contact_request' => 'Liên hệ bất động sản',
            'realestate.project_view' => 'Lượt xem bất động sản',
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

                \App\Models\Permission::updateOrCreate(
                    ['canonical' => $canonical],
                    [
                        'name' => $name,
                        'canonical' => $canonical,
                    ]
                );
            }
        }
    }
}
