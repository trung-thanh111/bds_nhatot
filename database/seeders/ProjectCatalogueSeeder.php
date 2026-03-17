<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\ProjectCatalogue;
use App\Models\ProjectPropertyGroup;
use App\Classes\NestedsetRealEstate;
use Illuminate\Support\Str;

class ProjectCatalogueSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. Xóa dữ liệu cũ để tránh trùng lặp
        ProjectCatalogue::truncate();

        $groups = ProjectPropertyGroup::all()->keyBy('code');

        $catalogues = [
            [
                'name' => 'Nhà đất bán',
                'slug' => 'nha-dat-ban',
                'transaction_type' => 'sale',
                'children' => [
                    ['name' => 'Bán căn hộ chung cư', 'property_group' => 'apartment'],
                    ['name' => 'Bán chung cư mini, căn hộ dịch vụ', 'property_group' => 'apartment'],
                    ['name' => 'Bán nhà riêng', 'property_group' => 'house'],
                    ['name' => 'Bán nhà biệt thự, liền kề', 'property_group' => 'house'],
                    ['name' => 'Bán nhà mặt phố', 'property_group' => 'house'],
                    ['name' => 'Bán shophouse, nhà phố thương mại', 'property_group' => 'house'],
                    ['name' => 'Bán đất nền dự án', 'property_group' => 'land'],
                    ['name' => 'Bán đất', 'property_group' => 'land'],
                    ['name' => 'Bán trang trại, khu nghỉ dưỡng', 'property_group' => 'land'],
                    ['name' => 'Bán condotel', 'property_group' => 'apartment'],
                    ['name' => 'Bán kho, nhà xưởng', 'property_group' => 'commercial'],
                    ['name' => 'Bán loại bất động sản khác', 'property_group' => 'land'],
                ]
            ],
            [
                'name' => 'Nhà đất cho thuê',
                'slug' => 'nha-dat-cho-thue',
                'transaction_type' => 'rent',
                'children' => [
                    ['name' => 'Cho thuê căn hộ chung cư', 'property_group' => 'apartment'],
                    ['name' => 'Cho thuê chung cư mini, căn hộ dịch vụ', 'property_group' => 'apartment'],
                    ['name' => 'Cho thuê nhà riêng', 'property_group' => 'house'],
                    ['name' => 'Cho thuê nhà biệt thự, liền kề', 'property_group' => 'house'],
                    ['name' => 'Cho thuê nhà mặt phố', 'property_group' => 'house'],
                    ['name' => 'Cho thuê shophouse, nhà phố thương mại', 'property_group' => 'house'],
                    ['name' => 'Cho thuê nhà trọ, phòng trọ', 'property_group' => 'room'],
                    ['name' => 'Cho thuê văn phòng', 'property_group' => 'commercial'],
                    ['name' => 'Cho thuê, sang nhượng cửa hàng, ki ốt', 'property_group' => 'commercial'],
                    ['name' => 'Cho thuê kho, nhà xưởng, đất', 'property_group' => 'commercial'],
                    ['name' => 'Cho thuê loại bất động sản khác', 'property_group' => 'land'],
                ]
            ],
            [
                'name' => 'Dự án',
                'slug' => 'du-an',
                'transaction_type' => 'sale',
                'children' => [
                    ['name' => 'Căn hộ chung cư', 'property_group' => 'project'],
                    ['name' => 'Cao ốc văn phòng', 'property_group' => 'project'],
                    ['name' => 'Trung tâm thương mại', 'property_group' => 'project'],
                    ['name' => 'Khu đô thị mới', 'property_group' => 'project'],
                    ['name' => 'Khu phức hợp', 'property_group' => 'project'],
                    ['name' => 'Nhà ở xã hội', 'property_group' => 'project'],
                    ['name' => 'Khu nghỉ dưỡng, Sinh thái', 'property_group' => 'project'],
                    ['name' => 'Khu công nghiệp', 'property_group' => 'project'],
                    ['name' => 'Biệt thự, liền kề', 'property_group' => 'project'],
                    ['name' => 'Shophouse', 'property_group' => 'project'],
                    ['name' => 'Nhà mặt phố', 'property_group' => 'project'],
                    ['name' => 'Dự án khác', 'property_group' => 'project'],
                ]
            ],
        ];

        foreach ($catalogues as $catData) {
            $parent = ProjectCatalogue::create([
                'name' => $catData['name'],
                'slug' => $catData['slug'] ?? Str::slug($catData['name']),
                'transaction_type' => $catData['transaction_type'],
                'publish' => 2,
            ]);

            if (isset($catData['children'])) {
                foreach ($catData['children'] as $child) {
                    ProjectCatalogue::create([
                        'name' => $child['name'],
                        'slug' => Str::slug($child['name']),
                        'parent_id' => $parent->id,
                        'property_group_id' => $groups[$child['property_group']]->id ?? null,
                        'transaction_type' => $catData['transaction_type'],
                        'publish' => 2,
                    ]);
                }
            }
        }

        $nestedSet = new NestedsetRealEstate([
            'table' => 'project_catalogues',
        ]);
        $nestedSet->Get();
        $arr = $nestedSet->Set();
        $nestedSet->Recursive(0, $arr);
        $nestedSet->Action();
    }
}
