<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProjectPropertyGroupSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $groups = [
            [
                'code' => 'apartment',
                'name' => 'Căn hộ',
                'description' => 'Các loại hình căn hộ chung cư, căn hộ mini, officetel...',
                'sort_order' => 1,
                'publish' => 2,
            ],
            [
                'code' => 'house',
                'name' => 'Nhà ở',
                'description' => 'Nhà mặt tiền, biệt thự, nhà phố, nhà hẻm...',
                'sort_order' => 2,
                'publish' => 2,
            ],
            [
                'code' => 'land',
                'name' => 'Đất',
                'description' => 'Đất nền dự án, đất thổ cư, đất nông nghiệp...',
                'sort_order' => 3,
                'publish' => 2,
            ],
            [
                'code' => 'commercial',
                'name' => 'Thương mại',
                'description' => 'Mặt bằng, văn phòng, kho xưởng...',
                'sort_order' => 4,
                'publish' => 2,
            ],
            [
                'code' => 'room',
                'name' => 'Phòng trọ',
                'description' => 'Phòng trọ, căn hộ mini cho thuê...',
                'sort_order' => 5,
                'publish' => 2,
            ],
            [
                'code' => 'project',
                'name' => 'Dự án',
                'description' => 'Các dự án bất động sản lớn...',
                'sort_order' => 6,
                'publish' => 2,
            ],
        ];

        foreach ($groups as $group) {
            DB::table('project_property_groups')->updateOrInsert(
                ['code' => $group['code']],
                $group
            );
        }
    }
}
