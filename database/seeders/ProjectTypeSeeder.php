<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\ProjectPropertyGroup;
use App\Models\ProjectType;

class ProjectTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        ProjectType::truncate();
        
        $groups = ProjectPropertyGroup::all()->keyBy('code');

        $types = [
            // Căn hộ
            ['group_code' => 'apartment', 'code' => 'can_ho_chung_cu', 'name' => 'Căn hộ chung cư', 'transaction_type' => 'both'],
            ['group_code' => 'apartment', 'code' => 'chung_cu_mini', 'name' => 'Chung cư mini, căn hộ dịch vụ', 'transaction_type' => 'both'],
            ['group_code' => 'apartment', 'code' => 'condotel', 'name' => 'Condotel', 'transaction_type' => 'sale'],
            
            // Nhà ở
            ['group_code' => 'house', 'code' => 'nha_rieng', 'name' => 'Nhà riêng', 'transaction_type' => 'both'],
            ['group_code' => 'house', 'code' => 'biet_thu_lien_ke', 'name' => 'Biệt thự, liền kề', 'transaction_type' => 'both'],
            ['group_code' => 'house', 'code' => 'nha_mat_pho', 'name' => 'Nhà mặt phố', 'transaction_type' => 'both'],
            ['group_code' => 'house', 'code' => 'shophouse', 'name' => 'Shophouse, nhà phố thương mại', 'transaction_type' => 'both'],

            // Đất
            ['group_code' => 'land', 'code' => 'dat_nen_du_an', 'name' => 'Đất nền dự án', 'transaction_type' => 'sale'],
            ['group_code' => 'land', 'code' => 'dat', 'name' => 'Đất', 'transaction_type' => 'sale'],
            ['group_code' => 'land', 'code' => 'trang_trai_nghi_duong', 'name' => 'Trang trại, khu nghỉ dưỡng', 'transaction_type' => 'sale'],

            // Thương mại / Dịch vụ
            ['group_code' => 'commercial', 'code' => 'van_phong', 'name' => 'Văn phòng', 'transaction_type' => 'rent'],
            ['group_code' => 'commercial', 'code' => 'cua_hang_ki_ot', 'name' => 'Cửa hàng, ki ốt', 'transaction_type' => 'rent'],
            ['group_code' => 'commercial', 'code' => 'mat_bang', 'name' => 'Mặt bằng', 'transaction_type' => 'rent'],
            ['group_code' => 'commercial', 'code' => 'kho_xuong', 'name' => 'Kho, nhà xưởng', 'transaction_type' => 'both'],

            // Phòng trọ
            ['group_code' => 'room', 'code' => 'nha_tro_phong_tro', 'name' => 'Nhà trọ, phòng trọ', 'transaction_type' => 'rent'],

            // Dự án
            ['group_code' => 'project', 'code' => 'du_an_can_ho', 'name' => 'Dự án căn hộ', 'transaction_type' => 'sale'],
            ['group_code' => 'project', 'code' => 'khu_do_thi_moi', 'name' => 'Khu đô thị mới', 'transaction_type' => 'sale'],
        ];

        foreach ($types as $type) {
            $group = $groups[$type['group_code']] ?? null;
            if ($group) {
                ProjectType::create([
                    'group_id' => $group->id,
                    'code' => $type['code'],
                    'name' => $type['name'],
                    'transaction_type' => $type['transaction_type'],
                    'publish' => 2,
                ]);
            }
        }
    }
}
