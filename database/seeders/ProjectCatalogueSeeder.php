<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\ProjectCatalogue;
use App\Models\ProjectPropertyGroup;
use Illuminate\Support\Str;

class ProjectCatalogueSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $groups = ProjectPropertyGroup::all()->keyBy('code');

        $catalogues = [
            [
                'name' => 'Bất động sản bán',
                'slug' => 'bat-dong-san-ban',
                'property_group_id' => $groups['apartment']->id ?? null,
                'transaction_type' => 'sale',
                'children' => [
                    ['name' => 'Căn hộ bán', 'slug' => 'can-ho-ban'],
                    ['name' => 'Nhà phố bán', 'slug' => 'nha-pho-ban'],
                    ['name' => 'Đất nền bán', 'slug' => 'dat-nen-ban'],
                ]
            ],
            [
                'name' => 'Bất động sản cho thuê',
                'slug' => 'bat-dong-san-cho-thue',
                'property_group_id' => $groups['apartment']->id ?? null,
                'transaction_type' => 'rent',
                'children' => [
                    ['name' => 'Căn hộ cho thuê', 'slug' => 'can-ho-cho-thue'],
                    ['name' => 'Văn phòng cho thuê', 'slug' => 'van-phong-cho-thue'],
                    ['name' => 'Phòng trọ cho thuê', 'slug' => 'phong-tro-cho-thue'],
                ]
            ],
        ];

        foreach ($catalogues as $cat) {
            $parent = ProjectCatalogue::create([
                'name' => $cat['name'],
                'slug' => $cat['slug'],
                'property_group_id' => $cat['property_group_id'],
                'transaction_type' => $cat['transaction_type'],
                'publish' => 2,
            ]);

            if (isset($cat['children'])) {
                foreach ($cat['children'] as $child) {
                    ProjectCatalogue::create([
                        'name' => $child['name'],
                        'slug' => $child['slug'],
                        'parent_id' => $parent->id,
                        'property_group_id' => $cat['property_group_id'],
                        'transaction_type' => $cat['transaction_type'],
                        'publish' => 2,
                    ]);
                }
            }
        }
    }
}
