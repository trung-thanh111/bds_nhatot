<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\Project;
use App\Models\User;
use App\Models\ProjectCatalogue;
use Illuminate\Support\Str;

class ProjectDataTest extends TestCase
{
    use RefreshDatabase;

    protected $user;
    protected $catalogue;

    protected function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->create();
        $this->actingAs($this->user);
        
        // Tạo catalogue mẫu
        $this->catalogue = ProjectCatalogue::create([
            'name' => 'Bất động sản',
            'slug' => 'bat-dong-san',
            'lft' => 1,
            'rgt' => 2,
            'publish' => 2
        ]);
    }

    /**
     * Test Record 1 — Căn hộ | Mua bán | HCM
     */
    public function test_record_1_apartment_sale_hcm()
    {
        $data = [
            'name' => 'Bán căn hộ 2PN Vinhomes Central Park - Quận Bình Thạnh',
            'slug' => 'ban-can-ho-2pn-vinhomes-central-park-q-binh-thanh-bds-000001',
            'catalogue_id' => $this->catalogue->id,
            'property_group' => 'apartment',
            'type_code' => 'can_ho_chung_cu',
            'transaction_type' => 'sale',
            'price' => '4.500.000.000',
            'price_unit' => 'total',
            'area' => 68.5,
            'area_use' => 62.0,
            'bedrooms' => 2,
            'bathrooms' => 2,
            'province_code' => 'thanh_pho_ho_chi_minh',
            'province_name' => 'Thành phố Hồ Chí Minh',
            'district_code' => 'quan_binh_thanh',
            'district_name' => 'Quận Bình Thạnh',
            'ward_code' => 'phuong_22',
            'ward_name' => 'Phường 22',
            'address' => '208 Nguyễn Hữu Cảnh',
            'has_elevator' => 1,
            'has_pool' => 1,
            'publish' => 2,
            'extra_fields' => [
                'block_name' => 'Park 1',
                'apartment_code' => 'P1-15.08',
                'management_fee' => 16500
            ],
            'meta_title' => 'Bán căn hộ 2PN Vinhomes Central Park Quận Bình Thạnh 68.5m²',
            'meta_desc' => 'Căn hộ 2PN tầng 15, view sông, full nội thất, sổ hồng. Giá 4.5 tỷ.'
        ];

        $response = $this->post(route('realestate.project.store'), $data);
        
        $response->assertStatus(302);
        $this->assertDatabaseHas('projects', [
            'slug' => $data['slug'],
            'price_vnd' => 4500000000,
            'property_group' => 'apartment'
        ]);
        
        $project = Project::where('slug', $data['slug'])->first();
        $this->assertEquals('Park 1', $project->extra_fields['block_name']);
    }

    /**
     * Test Record 2 — Nhà phố | Huế | Giá thỏa thuận
     */
    public function test_record_2_house_sale_hue_negotiable()
    {
        $data = [
            'name' => 'Bán nhà mặt tiền 3 tầng đường Lê Lợi - TP. Huế',
            'slug' => 'ban-nha-mat-tien-tp-hue-bds-000002',
            'catalogue_id' => $this->catalogue->id,
            'property_group' => 'house',
            'transaction_type' => 'sale',
            'price' => '',
            'price_negotiable' => 1,
            'price_unit' => 'total',
            'area' => 120,
            'area_land' => 120,
            'province_code' => 'thua_thien_hue',
            'province_name' => 'Thừa Thiên Huế',
            'province_new_code' => 'hue',
            'province_new_name' => 'Huế',
            'publish' => 2,
            'extra_fields' => [
                'road_type' => 'nhua'
            ]
        ];

        $response = $this->post(route('realestate.project.store'), $data);
        $response->assertStatus(302);
        
        $this->assertDatabaseHas('projects', [
            'slug' => $data['slug'],
            'price_negotiable' => 1,
            'province_new_code' => 'hue'
        ]);
    }

    /**
     * Test Record 4 — Mặt bằng | Thuê | per_m2_month
     */
    public function test_record_4_commercial_rent_per_m2()
    {
        $data = [
            'name' => 'Cho thuê mặt bằng mặt tiền Nguyễn Trãi - Quận 1',
            'slug' => 'cho-thue-mat-bang-mat-tien-quan-1-bds-000004',
            'catalogue_id' => $this->catalogue->id,
            'property_group' => 'commercial',
            'transaction_type' => 'rent',
            'price' => '350.000',
            'price_unit' => 'per_m2_month',
            'area' => 100,
            'province_code' => 'thanh_pho_ho_chi_minh',
            'publish' => 2,
            'extra_fields' => [
                'space_type' => 'shop',
                'height_clear' => 4.2
            ]
        ];

        $response = $this->post(route('realestate.project.store'), $data);
        
        // price_vnd = 350,000 * 100 = 35,000,000
        $this->assertDatabaseHas('projects', [
            'slug' => $data['slug'],
            'price_vnd' => 35000000
        ]);
    }
}
