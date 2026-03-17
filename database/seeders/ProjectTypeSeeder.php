<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\ProjectPropertyGroup;

class ProjectTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $types = [
            // Căn hộ (group_id = lookup 'apartment')
            ['group_code' => 'apartment', 'code' => 'can_ho_chung_cu', 'name' => 'Căn hộ chung cư', 'transaction_type' => 'both'],
            ['group_code' => 'apartment', 'code' => 'penthouse', 'name' => 'Penthouse', 'transaction_type' => 'sale'],
            ['group_code' => 'apartment', 'code' => 'condotel', 'name' => 'Condotel', 'transaction_type' => 'sale'],
            ['group_code' => 'apartment', 'code' => 'officetel', 'name' => 'Officetel', 'transaction_type' => 'both'],
            ['group_code' => 'apartment', 'code' => 'nha_o_xa_hoi', 'name' => 'Nhà ở xã hội', 'transaction_type' => 'sale'],

            // Nhà ở (group_id = lookup 'house')
            ['group_code' => 'house', 'code' => 'nha_mat_tien', 'name' => 'Nhà mặt tiền', 'transaction_type' => 'both'],
            ['group_code' => 'house', 'code' => 'biet_thu', 'name' => 'Biệt thự', 'transaction_type' => 'both'],
            ['group_code' => 'house', 'code' => 'nha_pho_lien_ke', 'name' => 'Nhà phố liên kế', 'transaction_type' => 'both'],

            // Đất (group_id = lookup 'land')
            ['group_code' => 'land', 'code' => 'dat_nen_du_an', 'name' => 'Đất nền dự án', 'transaction_type' => 'sale'],
            ['group_code' => 'land', 'code' => 'dat_tho_cu', 'name' => 'Đất thổ cư', 'transaction_type' => 'sale'],
            ['group_code' => 'land', 'code' => 'dat_nong_nghiep', 'name' => 'Đất nông nghiệp', 'transaction_type' => 'sale'],

            // Thương mại (group_id = lookup 'commercial')
            ['group_code' => 'commercial', 'code' => 'mat_bang', 'name' => 'Mặt bằng', 'transaction_type' => 'rent'],
            ['group_code' => 'commercial', 'code' => 'van_phong', 'name' => 'Văn phòng', 'transaction_type' => 'rent'],
            ['group_code' => 'commercial', 'code' => 'kho_xuong', 'name' => 'Kho / Xưởng', 'transaction_type' => 'rent'],

            // Phòng trọ (group_id = lookup 'room')
            ['group_code' => 'room', 'code' => 'phong_tro', 'name' => 'Phòng trọ', 'transaction_type' => 'rent'],
            ['group_code' => 'room', 'code' => 'can_ho_mini', 'name' => 'Căn hộ mini', 'transaction_type' => 'rent'],

            // Dự án (group_id = lookup 'project')
            ['group_code' => 'project', 'code' => 'du_an_can_ho', 'name' => 'Dự án căn hộ', 'transaction_type' => 'sale'],
            ['group_code' => 'project', 'code' => 'du_an_nha_o', 'name' => 'Dự án nhà ở', 'transaction_type' => 'sale'],
        ];

        foreach ($types as $type) {
            $group = ProjectPropertyGroup::where('code', $type['group_code'])->first();
            if ($group) {
                DB::table('project_types')->updateOrInsert(
                    ['code' => $type['code']],
                    [
                        'group_id' => $group->id,
                        'code' => $type['code'],
                        'name' => $type['name'],
                        'transaction_type' => $type['transaction_type'],
                        'publish' => 2,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]
                );
            }
        }
    }
}
