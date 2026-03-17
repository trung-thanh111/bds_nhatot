<?php

use App\Models\ProjectCatalogue;
use App\Models\ProjectPropertyGroup;
use App\Enums\RealEstate\TransactionTypeEnum;
use Illuminate\Support\Str;

require 'vendor/autoload.php';
$app = include_once 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

// Truncate existing data to start fresh
ProjectCatalogue::truncate();

$groups = ProjectPropertyGroup::all()->pluck('id', 'code')->toArray();

function createCatalogue($name, $parentId = null, $type = TransactionTypeEnum::SALE, $groupCode = 'house', $typeCode = null, $groups = []) {
    return ProjectCatalogue::create([
        'name' => $name,
        'slug' => Str::slug($name),
        'parent_id' => $parentId,
        'transaction_type' => $type,
        'property_group_id' => $groups[$groupCode] ?? null,
        'type_code' => $typeCode,
        'publish' => 2,
        'level' => ($parentId === null || $parentId === 0) ? 1 : 2,
    ]);
}

// 1. NHÀ ĐẤT BÁN
$saleRoot = createCatalogue('Nhà đất bán', null, TransactionTypeEnum::SALE, 'house', null, $groups);
$saleItems = [
    ['Bán căn hộ chung cư', 'apartment', 'can_ho_chung_cu'],
    ['Bán chung cư mini, căn hộ dịch vụ', 'apartment', 'can_ho_mini'],
    ['Bán nhà riêng', 'house', 'nha_mat_tien'],
    ['Bán nhà biệt thự, liền kề', 'house', 'biet_thu'],
    ['Bán nhà mặt phố', 'house', 'nha_mat_tien'],
    ['Bán shophouse, nhà phố thương mại', 'house', 'nha_pho_lien_ke'],
    ['Bán đất nền dự án', 'land', 'dat_nen_du_an'],
    ['Bán đất', 'land', 'dat_tho_cu'],
    ['Bán trang trại, khu nghỉ dưỡng', 'house', 'biet_thu'],
    ['Bán condotel', 'apartment', 'condotel'],
    ['Bán kho, nhà xưởng', 'commercial', 'kho_xuong'],
    ['Bán loại bất động sản khác', 'house', null],
];
foreach ($saleItems as $item) {
    createCatalogue($item[0], $saleRoot->id, TransactionTypeEnum::SALE, $item[1], $item[2], $groups);
}

// 2. NHÀ ĐẤT CHO THUÊ
$rentRoot = createCatalogue('Nhà đất cho thuê', null, TransactionTypeEnum::RENT, 'house', null, $groups);
$rentItems = [
    ['Cho thuê căn hộ chung cư', 'apartment', 'can_ho_chung_cu'],
    ['Cho thuê chung cư mini, căn hộ dịch vụ', 'apartment', 'can_ho_mini'],
    ['Cho thuê nhà riêng', 'house', 'nha_mat_tien'],
    ['Cho thuê nhà biệt thự, liền kề', 'house', 'biet_thu'],
    ['Cho thuê nhà mặt phố', 'house', 'nha_mat_tien'],
    ['Cho thuê shophouse, nhà phố thương mại', 'house', 'nha_pho_lien_ke'],
    ['Cho thuê nhà trọ, phòng trọ', 'apartment', 'phong_tro'],
    ['Cho thuê văn phòng', 'commercial', 'van_phong'],
    ['Cho thuê sang nhượng cửa hàng, ki ốt', 'commercial', 'mat_bang'],
    ['Cho thuê kho, nhà xưởng, đất', 'commercial', 'kho_xuong'],
    ['Cho thuê loại bất động sản khác', 'house', null],
];
foreach ($rentItems as $item) {
    createCatalogue($item[0], $rentRoot->id, TransactionTypeEnum::RENT, $item[1], $item[2], $groups);
}

// 3. DỰ ÁN
$projectRoot = createCatalogue('Dự án', null, TransactionTypeEnum::SALE, 'project', null, $groups);
$projectItems = [
    ['Căn hộ chung cư', 'apartment', 'can_ho_chung_cu'],
    ['Cao ốc văn phòng', 'commercial', 'van_phong'],
    ['Trung tâm thương mại', 'commercial', 'mat_bang'],
    ['Khu đô thị mới', 'project', 'du_an_nha_o'],
    ['Khu phức hợp', 'project', 'du_an_nha_o'],
    ['Nhà ở xã hội', 'apartment', 'nha_o_xa_hoi'],
    ['Khu nghỉ dưỡng, Sinh thái', 'project', 'du_an_nha_o'],
    ['Khu công nghiệp', 'project', 'kho_xuong'],
    ['Biệt thự, liền kề', 'house', 'biet_thu'],
    ['Shophouse', 'house', 'nha_pho_lien_ke'],
    ['Nhà mặt phố', 'house', 'nha_mat_tien'],
    ['Dự án khác', 'project', null],
];
foreach ($projectItems as $item) {
    createCatalogue($item[0], $projectRoot->id, TransactionTypeEnum::SALE, $item[1], $item[2], $groups);
}

echo "Setup catalogues successfully!\n";
