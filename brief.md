## 1. SƠ ĐỒ QUAN HỆ

```
━━━ ĐỊA LÝ — JSON file (KHÔNG có DB table) ━━━━━━━━━━━━━━━━━━━
  /resources/json/vie_address_before_1_7.json  ← 63 tỉnh + quận/huyện/phường cũ
  /resources/json/vie_address_after_1_7.json   ← ~34 tỉnh mới + phường mới (bỏ cấp huyện)

  Cấu trúc JSON (tham khỏa 2 base trên)

  Lưu trong bảng projects:
  ┌────────────────────────────────────┬────────────────────────────────┐
  │ ĐỊA CHỈ CŨ (trước 01/07/2025)      │ ĐỊA CHỈ MỚI (sau 01/07/2025)  │
  ├────────────────────────────────────┼────────────────────────────────┤
  │ province_code   VARCHAR(80)        │ province_new_code  VARCHAR(80) │
  │ province_name   VARCHAR(150)       │ province_new_name  VARCHAR(150)│
  │ district_code   VARCHAR(80)        │ (không có district mới)        │
  │ district_name   VARCHAR(150)       │                                │
  │ ward_code       VARCHAR(80)        │ ward_new_code      VARCHAR(80) │
  │ ward_name       VARCHAR(150)       │ ward_new_name      VARCHAR(150)│
  └────────────────────────────────────┴────────────────────────────────┘
  address  VARCHAR(500) → số nhà, tên đường, ngõ, hẻm (dùng chung)

  Chiến lược filter:
  - Filter theo tỉnh:   WHERE province_code = ? OR province_new_code = ?
  - Filter theo huyện:  WHERE district_code = ? (chỉ tin đăng trước 01/07/2025)
  - Tin mới (sau sáp nhập): district_code = NULL, province_new_code có giá trị

━━━ LOOKUP TABLES ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
  project_property_groups (1) ──── (N) project_types
  project_property_groups (1) ──── (N) project_catalogues
  project_types           (N) ──── (1) project_property_groups

━━━ CATALOGUE TREE ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
  project_catalogues (self-ref parent_id) -> triển khai như post_catalogue nhé

━━━ CORE ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
  projects (N) ──── (1) project_catalogues
  projects (N) ──── (1) project_property_groups   [denorm]
  projects (N) ──── (1) project_types             [denorm]
  projects (1) ──── (N) project_items             [khi is_project=1]
  projects (1) ──── (N) project_views
  projects (1) ──── (N) contact_requests

━━━ TỔNG: 7 bảng chính + 2 phụ ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
```

---

## 2. SAMPLE DATA

### project_property_groups

| id  | code       | name       | sort_order |
| --- | ---------- | ---------- | ---------- |
| 1   | apartment  | Căn hộ     | 1          |
| 2   | house      | Nhà ở      | 2          |
| 3   | land       | Đất        | 3          |
| 4   | commercial | Thương mại | 4          |
| 5   | room       | Phòng trọ  | 5          |
| 6   | project    | Dự án      | 6          |

### project_types

| id  | group_id | code            | name            | transaction_type |
| --- | -------- | --------------- | --------------- | ---------------- |
| 1   | 1        | can_ho_chung_cu | Căn hộ chung cư | both             |
| 2   | 1        | penthouse       | Penthouse       | sale             |
| 3   | 1        | condotel        | Condotel        | sale             |
| 4   | 1        | officetel       | Officetel       | both             |
| 5   | 1        | nha_o_xa_hoi    | Nhà ở xã hội    | sale             |
| 6   | 2        | nha_mat_tien    | Nhà mặt tiền    | both             |
| 7   | 2        | biet_thu        | Biệt thự        | both             |
| 8   | 2        | nha_pho_lien_ke | Nhà phố liên kế | both             |
| 9   | 3        | dat_nen_du_an   | Đất nền dự án   | sale             |
| 10  | 3        | dat_tho_cu      | Đất thổ cư      | sale             |
| 11  | 3        | dat_nong_nghiep | Đất nông nghiệp | sale             |
| 12  | 4        | mat_bang        | Mặt bằng        | rent             |
| 13  | 4        | van_phong       | Văn phòng       | rent             |
| 14  | 4        | kho_xuong       | Kho / Xưởng     | rent             |
| 15  | 5        | phong_tro       | Phòng trọ       | rent             |
| 16  | 5        | can_ho_mini     | Căn hộ mini     | rent             |
| 17  | 6        | du_an_can_ho    | Dự án căn hộ    | sale             |
| 18  | 6        | du_an_nha_o     | Dự án nhà ở     | sale             |

### project_catalogues

| id  | parent_id | name       | slug       | group_id | level |
| --- | --------- | ---------- | ---------- | -------- | ----- |
| 1   | NULL      | Mua bán    | mua-ban    | NULL     | 1     |
| 2   | NULL      | Cho thuê   | cho-thue   | NULL     | 1     |
| 10  | NULL      | Căn hộ     | can-ho     | 1        | 1     |
| 11  | NULL      | Nhà ở      | nha-o      | 2        | 1     |
| 12  | NULL      | Đất        | dat        | 3        | 1     |
| 13  | NULL      | Thương mại | thuong-mai | 4        | 1     |
| 14  | NULL      | Phòng trọ  | phong-tro  | 5        | 1     |
| 15  | NULL      | Dự án      | du-an      | 6        | 1     |

### projects — record mẫu

```
id: 1 | code: BDS-000001
slug: ban-can-ho-2pn-vinhomes-q-binh-thanh-bds-000001
catalogue_id: 10 | property_group: apartment
type_code: can_ho_chung_cu | transaction_type: sale | is_project: 0

title:        Bán căn hộ 2PN Vinhomes Central Park - Quận Bình Thạnh
price:        4500000000 | price_vnd: 4500000000 | price_unit: total
area: 68.50 | bedrooms: 2 | bathrooms: 2 | floor_number: 15
direction: dong_nam | balcony_direction: nam
legal_status: so_hong | furniture_status: full

── Địa chỉ CŨ ─────────────────────────────
province_code: thanh_pho_ho_chi_minh
province_name: Thành phố Hồ Chí Minh
district_code: quan_binh_thanh
district_name: Quận Bình Thạnh
ward_code:     phuong_22
ward_name:     Phường 22

── Địa chỉ MỚI (HCM không sáp nhập) ───────
province_new_code: thanh_pho_ho_chi_minh
province_new_name: Thành phố Hồ Chí Minh
ward_new_code:     phuong_22
ward_new_name:     Phường 22

address: 208 Nguyễn Hữu Cảnh

has_elevator: 1 | has_pool: 1 | has_parking: 1 | has_gym: 1 | has_security: 1

image: /ckfinder/userfiles/projects/BDS-000001/cover.jpg
album: projects/BDS-000001

extra_fields: {
  "block_name": "Park 1", "apartment_code": "P1-15.08",
  "year_built": 2018, "management_fee": 16500,
  "view_type": "river", "area_balcony": 8.5
}

status: active | publish: 2 | is_featured: 1 | sort_order: 0
```

---

## 3. MIGRATIONS — 8 MODULE

---

### MODULE 1 — `project_property_groups`

> Nhóm loại BĐS. Quyết định form field nào render khi admin tạo tin. Admin mở rộng được (không dùng ENUM).

```php
Schema::create('project_property_groups', function (Blueprint $table) {
    $table->id();

    $table->string('code', 50)->unique();
    // Mã dùng trong code: 'apartment','house','land','commercial','room','project'
    // Lưu DENORM vào projects.property_group để tránh JOIN khi filter

    $table->string('name', 150);
    // Tên hiển thị: "Căn hộ", "Nhà ở", "Đất"

    $table->string('description', 500)->nullable();
    // Mô tả ngắn trong admin UI

    $table->string('icon_url', 300)->nullable();
    // URL icon — hiện trong form chọn loại và trang chủ

    $table->unsignedSmallInteger('sort_order')->default(0);

    $table->tinyInteger('publish')->default(2);
    // 2 = hiện trong form, 1 = ẩn

    $table->timestamps();
});
```

---

### MODULE 2 — `project_types`

```php
Schema::create('project_types', function (Blueprint $table) {
    $table->id();

    $table->foreignId('group_id')
          ->constrained('project_property_groups')
          ->onDelete('restrict');
    // FK về nhóm — restrict: không xóa nhóm khi còn loại đang dùng

    $table->string('code', 80)->unique();
    // Mã: 'can_ho_chung_cu', 'nha_mat_tien', 'dat_tho_cu'
    // Lưu DENORM vào projects.type_code để filter không JOIN

    $table->string('name', 200);
    // Tên đầy đủ: "Căn hộ chung cư", "Nhà mặt tiền"

    $table->string('name_short', 80)->nullable();
    // Tên ngắn cho badge trên card: "Chung cư", "Nhà phố"

    $table->enum('transaction_type', ['sale', 'rent', 'both'])->default('both');
    // sale = chỉ mua bán | rent = chỉ cho thuê | both = cả hai

    $table->unsignedSmallInteger('sort_order')->default(0);

    $table->tinyInteger('publish')->default(2);
    // 2 = hiện trong form, 1 = ẩn

    $table->timestamps();
});
```

---

### MODULE 3 — `project_catalogues` -> tham khảo post_catalogue để triển khai đúng khi có dùng tree nhé -> chỉnh sửa field đúng nhé

```php
Schema::create('project_catalogues', function (Blueprint $table) {
    $table->id();

    $table->unsignedBigInteger('parent_id')->nullable();
    // Self-reference — NULL = cấp gốc

    $table->foreignId('property_group_id')
          ->nullable()
          ->constrained('project_property_groups')
          ->onDelete('set null');
    // Nhóm BĐS → form field nào render. NULL = danh mục điều hướng menu

    $table->string('name', 200);
    $table->string('slug', 200)->unique();

    $table->enum('transaction_type', ['sale', 'rent', 'both'])->default('both')->nullable();
    // Preset giao dịch trong form đăng tin

    $table->string('icon_url', 300)->nullable();

    $table->unsignedTinyInteger('level')->default(1);
    // 1 = gốc, 2 = con — không vượt quá 2 cấp

    $table->string('path', 100)->nullable();
    // "10", "10/101" — dùng WHERE path LIKE '10/%' để lấy toàn bộ con

    $table->unsignedSmallInteger('sort_order')->default(0);
    $table->tinyInteger('publish')->default(2);
    // 2 = hiện trong form, 1 = ẩn

    $table->string('meta_title', 200)->nullable();
    $table->string('meta_desc', 400)->nullable();

    $table->timestamps();

    $table->foreign('parent_id')
          ->references('id')
          ->on('project_catalogues')
          ->onDelete('set null');
});
```

---

### MODULE 4 — `projects` ← BẢNG TRUNG TÂM

```php
Schema::create('projects', function (Blueprint $table) {

    // ── ĐỊNH DANH ───────────────────────────────────────────────
    $table->id();

    $table->string('code', 20)->unique()->nullable();
    // Auto-gen bằng Observer: "BDS-000001"
    // Dùng để đặt tên album CKFinder: "projects/BDS-000001"

    $table->string('slug', 600)->unique();
    // Khi đổi slug → lưu slug cũ vào slug_redirects để 301 redirect

    // ── PHÂN LOẠI ──────────────────────────────────────────────
    $table->foreignId('catalogue_id')
          ->constrained('project_catalogues')
          ->onDelete('restrict');

    $table->string('property_group', 50)->index();
    // DENORM từ catalogue.propertyGroup.code
    // Observer tự đồng bộ khi catalogue_id thay đổi

    $table->string('type_code', 80)->index();
    // DENORM từ project_types.code

    $table->enum('transaction_type', ['sale', 'rent'])->index();

    $table->tinyInteger('is_project')->default(0);
    // 0 = tin lẻ | 1 = dự án lớn (có project_items)

    // ── NỘI DUNG ───────────────────────────────────────────────
    $table->string('title', 500);
    $table->text('description')->nullable();  // HTML từ CKEditor
    $table->string('summary', 500)->nullable();

    // ── GIÁ ────────────────────────────────────────────────────
    $table->decimal('price', 18, 2)->nullable();
    $table->tinyInteger('price_negotiable')->default(0);  // 1 = Giá thỏa thuận
    $table->enum('price_unit', ['total','per_m2','per_month','per_m2_month'])->default('total');

    $table->decimal('price_vnd', 18, 2)->nullable()->index();
    // Giá quy đổi VND để filter range — Observer tự tính:
    // per_m2/per_m2_month → price × area | còn lại → price

    // ── DIỆN TÍCH ──────────────────────────────────────────────
    $table->decimal('area', 10, 2)->nullable()->index();
    $table->decimal('area_use', 10, 2)->nullable();    // DT sử dụng (căn hộ)
    $table->decimal('area_land', 10, 2)->nullable();   // DT đất (nhà phố)
    $table->decimal('length', 8, 2)->nullable();       // Chiều dài (m)
    $table->decimal('width', 8, 2)->nullable();        // Mặt tiền (m)

    // ── ĐỊA CHỈ CŨ — trước sáp nhập 01/07/2025 ────────────────
    $table->string('province_code', 80)->nullable()->index();
    // Code tỉnh cũ: "thanh_pho_ho_chi_minh" — slug-style gạch dưới

    $table->string('province_name', 150)->nullable();
    // Tên tỉnh cũ: "Thành phố Hồ Chí Minh"

    $table->string('district_code', 80)->nullable()->index();
    // Code quận/huyện cũ: "quan_binh_thanh"
    // NULL với tin đăng sau 01/07/2025

    $table->string('district_name', 150)->nullable();
    // Tên quận/huyện cũ: "Quận Bình Thạnh"

    $table->string('ward_code', 80)->nullable();
    // Code phường/xã cũ: "phuong_22"

    $table->string('ward_name', 150)->nullable();
    // Tên phường/xã cũ: "Phường 22"

    // ── ĐỊA CHỈ MỚI — sau sáp nhập 01/07/2025 ─────────────────
    $table->string('province_new_code', 80)->nullable()->index();
    // Code tỉnh mới: "hue", "quang_nam_da_nang"
    // Load từ vie_address_after_1_7.json
    // NULL với tin đăng trước sáp nhập (chưa cần điền)

    $table->string('province_new_name', 150)->nullable();
    // Tên tỉnh mới: "Huế"

    // Không có district_new — sau sáp nhập bỏ cấp huyện

    $table->string('ward_new_code', 80)->nullable();
    // Code phường/xã mới (tên có thể đổi sau sáp nhập)

    $table->string('ward_new_name', 150)->nullable();
    // Tên phường/xã mới

    // ── ĐỊA CHỈ CHI TIẾT (dùng chung) ──────────────────────────
    $table->string('address', 500)->nullable();
    // Số nhà, tên đường, ngõ, hẻm: "208 Nguyễn Hữu Cảnh"

    $table->decimal('latitude', 10, 7)->nullable();
    $table->decimal('longitude', 11, 7)->nullable();

    // ── THÔNG TIN NHÀ Ở ────────────────────────────────────────
    $table->unsignedSmallInteger('bedrooms')->nullable()->index();
    // 0=studio, 1,2,3,4,5 (5+ lưu là 5)
    $table->unsignedSmallInteger('bathrooms')->nullable();
    $table->unsignedSmallInteger('floors')->nullable();
    $table->unsignedSmallInteger('floor_number')->nullable();
    // Căn hộ ở tầng mấy trong tòa (chỉ apartment)

    // ── HƯỚNG ──────────────────────────────────────────────────
    $table->enum('direction',['dong','tay','nam','bac','dong_nam','dong_bac','tay_nam','tay_bac'])->nullable()->index();
    $table->enum('balcony_direction',['dong','tay','nam','bac','dong_nam','dong_bac','tay_nam','tay_bac'])->nullable();

    // ── PHÁP LÝ & NỘI THẤT ─────────────────────────────────────
    $table->enum('legal_status',[
        'so_hong','so_do','hop_dong_mua_ban','so_chung_cu',
        'dat_nen_du_an','du_an','dang_cho_so','chua_co_so',
    ])->nullable()->index();

    $table->enum('furniture_status',['none','basic','full','negotiable'])->default('none')->index();
    // Label hiển thị theo property_group:
    // none: apartment="Bàn giao thô" | room="Phòng trống" | commercial="Nguyên bản"
    // full: apartment="Full nội thất" | room="Full đồ" | commercial="Đã hoàn thiện"

    // ── TIỆN ÍCH ───────────────────────────────────────────────
    $table->tinyInteger('has_elevator')->default(0);
    $table->tinyInteger('has_pool')->default(0);
    $table->tinyInteger('has_parking')->default(0);
    $table->tinyInteger('has_security')->default(0);
    $table->tinyInteger('has_balcony')->default(0);
    $table->tinyInteger('has_rooftop')->default(0);
    $table->tinyInteger('has_basement')->default(0);
    $table->tinyInteger('has_gym')->default(0);
    $table->tinyInteger('has_ac')->default(0);
    $table->tinyInteger('has_wifi')->default(0);

    // ── EXTRA FIELDS JSON ───────────────────────────────────────
    $table->json('extra_fields')->nullable();
    // apartment: { block_name, apartment_code, year_built, management_fee,
    //              handover_date, view_type, area_balcony, virtual_tour_url }
    // house:     { year_built, renovation_year, road_type, alley_width,
    //              electricity_type, has_mezzanine }
    // land:      { land_type, zoning, road_width, infrastructure[],
    //              current_use, is_corner, quy_hoach_url }
    // commercial:{ space_type, height_clear, electricity_capacity,
    //              security_deposit, min_lease_term, parking_slots, allowed_business }
    // room:      { electricity_price, water_price, max_people,
    //              gender_restriction, pet_allowed, deposit_months, available_from }
    // project:   { developer_name, developer_logo, distributor_name, total_units,
    //              total_blocks, area_total, started_at, expected_handover,
    //              progress_pct, price_per_m2_min, price_per_m2_max,
    //              ownership_type, brochure_url, highlights }

    // ── MEDIA (CKFinder) ────────────────────────────────────────
    $table->string('image', 500)->nullable();
    // URL ảnh bìa: "/ckfinder/userfiles/projects/BDS-000001/cover.jpg"

    $table->string('album', 200)->nullable();
    // Path thư mục CKFinder: "projects/BDS-000001"
    // Format: "projects/{code}" — auto-gen bằng Observer sau khi có code
    // Frontend dùng để gọi CKFinder API lấy danh sách ảnh trong thư mục

    $table->tinyInteger('has_video')->default(0);
    $table->string('video_url', 500)->nullable();    // YouTube URL gốc
    $table->string('video_embed', 500)->nullable();  // Observer tự extract embed URL

    $table->tinyInteger('has_virtual_tour')->default(0);
    $table->string('virtual_tour_url', 500)->nullable();

    // ── TRẠNG THÁI ─────────────────────────────────────────────
    $table->enum('status',['draft','active','hidden','sold','rented'])->default('draft')->index();
    $table->tinyInteger('is_featured')->default(0)->index();
    $table->tinyInteger('is_hot')->default(0);
    $table->tinyInteger('is_urgent')->default(0);
    $table->integer('sort_order')->default(0)->index();
    $table->tinyInteger('publish')->default(2);
    // 2 = hiển thị | 1 = ẩn — đồng bộ convention toàn project

    // ── SEO ─────────────────────────────────────────────────────
    $table->string('meta_title', 200)->nullable();   // NULL = tự gen từ title
    $table->string('meta_desc', 400)->nullable();    // NULL = tự gen từ summary
    $table->string('focus_keyword', 200)->nullable();

    // ── ANALYTICS CACHE ─────────────────────────────────────────
    $table->integer('view_count')->default(0);
    // Sync định kỳ từ project_views qua Queue job

    // ── THỜI GIAN ───────────────────────────────────────────────
    $table->timestamp('published_at')->nullable();
    // Thời điểm admin publish (draft → active)

    // ── TIMESTAMPS & SOFT DELETE ────────────────────────────────
    $table->timestamps();
    $table->softDeletes();

    // ── INDEXES ─────────────────────────────────────────────────
    $table->index(['status','transaction_type','province_code','catalogue_id'], 'idx_projects_filter_main');
    $table->index(['province_new_code','status','transaction_type'], 'idx_projects_filter_new');
    $table->index(['is_featured','sort_order','published_at'], 'idx_projects_featured');
    $table->fullText(['title','summary','address'], 'ft_projects_search');
});
```

---

### MODULE 5 — `project_items`

```php
Schema::create('project_items', function (Blueprint $table) {
    $table->id();

    $table->foreignId('project_id')
          ->constrained('projects')
          ->onDelete('cascade');
    // Xóa dự án → xóa hết items

    $table->string('name', 200);
    // apartment: "Căn 2PN loại A" | land: "Lô A1-05" | commercial: "Shophouse SH-01"

    $table->string('item_code', 50)->nullable();
    // Mã ngắn: "2PN-A", "A1-05", "SH-01"

    $table->unsignedSmallInteger('bedrooms')->nullable();
    $table->unsignedSmallInteger('bathrooms')->nullable();

    $table->decimal('area_min', 8, 2)->nullable();
    $table->decimal('area_max', 8, 2)->nullable();
    $table->decimal('price_min', 18, 2)->nullable();
    $table->decimal('price_max', 18, 2)->nullable();
    $table->decimal('price_per_m2', 12, 2)->nullable();

    $table->integer('total_units')->default(0);
    $table->integer('available_units')->default(0);

    $table->enum('item_status',['available','limited','sold_out'])->default('available');
    // available = Còn | limited = Sắp hết | sold_out = Hết

    $table->string('floor_plan_image', 500)->nullable();
    // Ảnh mặt bằng căn/lô (CKFinder, 1 ảnh)

    $table->json('extra_fields')->nullable();
    // apartment: { floor_number_min, floor_number_max, direction, area_balcony, view_type }
    // land:      { width, length, road_width, is_corner, legal_status }
    // commercial:{ floor_number, width, height_clear, space_type }

    $table->text('description')->nullable();
    $table->unsignedSmallInteger('sort_order')->default(0);

    $table->tinyInteger('publish')->default(2);
    // 2 = hiển thị | 1 = ẩn

    $table->timestamps();
});
```

---

### MODULE 6 — `contact_requests` _(rename visit_requests)_

```php
// Thực tế: Schema::rename('visit_requests', 'contact_requests') + addColumn
Schema::create('contact_requests', function (Blueprint $table) {
    $table->id();

    $table->foreignId('project_id')->nullable()
          ->constrained('projects')->onDelete('set null');

    $table->enum('request_type',['listing','project','general','phone_click'])->default('listing');
    $table->string('source_url', 600)->nullable();

    $table->string('full_name', 200)->nullable();
    $table->string('phone', 20)->nullable();
    $table->string('email', 200)->nullable();
    $table->text('message')->nullable();

    $table->enum('interested_in',['buy','rent','consult','invest'])->default('consult');
    $table->enum('preferred_contact',['phone','zalo','email','any'])->default('any');
    $table->string('contact_time', 100)->nullable();

    $table->enum('status',['new','read','contacted','done','spam'])->default('new');
    $table->text('admin_note')->nullable();

    $table->string('ip_address', 45)->nullable();
    $table->decimal('recaptcha_score', 3, 2)->nullable();
    $table->tinyInteger('email_sent')->default(0);
    $table->timestamp('email_sent_at')->nullable();

    $table->timestamps();
    $table->index(['status','created_at'], 'idx_contacts_status');
    $table->index('project_id', 'idx_contacts_project');
});
```

---

### MODULE 7 — `project_views`

```php
Schema::create('project_views', function (Blueprint $table) {
    $table->id();

    $table->foreignId('project_id')
          ->constrained('projects')
          ->onDelete('cascade');

    $table->string('ip_address', 45)->nullable();
    $table->tinyInteger('is_bot')->default(0);
    $table->tinyInteger('is_phone_click')->default(0);
    $table->string('referer', 500)->nullable();
    $table->date('view_date')->index();
    $table->timestamp('viewed_at')->useCurrent();

    $table->unique(['project_id','ip_address','view_date'], 'idx_views_dedup');
    $table->index(['project_id','view_date'], 'idx_views_by_date');
});
```

---

### MODULE 8 — `site_settings` + `slug_redirects`

```php
Schema::create('site_settings', function (Blueprint $table) {
    $table->id();
    $table->string('key', 100)->unique();
    $table->text('value')->nullable();
    $table->enum('type',['text','number','boolean','json','color','url'])->default('text');
    $table->string('group_name', 50)->default('general');
    $table->string('label', 200)->nullable();
    $table->tinyInteger('is_public')->default(0);
    $table->timestamp('updated_at')->useCurrent()->useCurrentOnUpdate();
});

Schema::create('slug_redirects', function (Blueprint $table) {
    $table->id();
    $table->string('old_slug', 600)->unique();
    $table->string('new_slug', 600)->nullable(); // NULL = 410 Gone
    $table->unsignedSmallInteger('redirect_type')->default(301);
    $table->integer('hit_count')->default(0);
    $table->timestamp('created_at')->useCurrent();
    $table->index('old_slug');
});
```

---

## 4. OBSERVER (MySQL)

```php
// app/Observers/ProjectObserver.php

class ProjectObserver
{
    public function created(Project $project): void
    {
        $code = 'BDS-' . str_pad($project->id, 6, '0', STR_PAD_LEFT);
        $project->updateQuietly([
            'code'  => $code,
            'album' => 'projects/' . $code,
        ]);
    }

    public function saving(Project $project): void
    {
        // Tính price_vnd để filter range đồng nhất
        $project->price_vnd = match (true) {
            is_null($project->price) => null,
            in_array($project->price_unit, ['per_m2', 'per_m2_month'])
                => $project->price * ($project->area ?? 1),
            default => $project->price,
        };

        // Denorm property_group khi đổi catalogue
        if ($project->isDirty('catalogue_id')) {
            $catalogue = ProjectCatalogue::with('propertyGroup')->find($project->catalogue_id);
            $project->property_group = $catalogue?->propertyGroup?->code;
        }

        // Extract YouTube embed
        if ($project->isDirty('video_url') && $project->video_url) {
            preg_match('/(?:v=|youtu\.be\/)([^&?\/]+)/', $project->video_url, $m);
            $id = $m[1] ?? null;
            $project->video_embed = $id ? "https://www.youtube.com/embed/{$id}" : null;
            $project->has_video   = $id ? 1 : 0;
        }

        // Auto slug
        if (empty($project->slug) && $project->title) {
            $project->slug = \Str::slug($project->title) . '-' . time();
        }
    }
}
```

---

## 5. MODELS

```php
// app/Models/Project.php
class Project extends Model
{
    use SoftDeletes;
    protected $table = 'projects';
    protected $casts = [
        'extra_fields' => 'array',
        'price'        => 'decimal:2',
        'price_vnd'    => 'decimal:2',
        'area'         => 'decimal:2',
        'published_at' => 'datetime',
    ];

    public function catalogue()   { return $this->belongsTo(ProjectCatalogue::class,'catalogue_id'); }
    public function type()        { return $this->belongsTo(ProjectType::class,'type_code','code'); }
    public function items()       { return $this->hasMany(ProjectItem::class,'project_id'); }
    public function contacts()    { return $this->hasMany(ContactRequest::class,'project_id'); }
    public function views()       { return $this->hasMany(ProjectView::class,'project_id'); }

    public function scopeActive($q)              { return $q->where('status','active')->where('publish',2); }
    public function scopeFeatured($q)            { return $q->where('is_featured',1); }
    public function scopeByGroup($q,string $g)   { return $q->where('property_group',$g); }
    public function scopeByProvince($q,string $c){ return $q->where(fn($q2) =>
        $q2->where('province_code',$c)->orWhere('province_new_code',$c));
    }
}

// app/Models/ProjectType.php
class ProjectType extends Model
{
    protected $table = 'project_types';
    public function group() { return $this->belongsTo(ProjectPropertyGroup::class,'group_id'); }
}

// app/Models/ProjectCatalogue.php
class ProjectCatalogue extends Model
{
    protected $table = 'project_catalogues';
    public function parent()        { return $this->belongsTo(self::class,'parent_id'); }
    public function children()      { return $this->hasMany(self::class,'parent_id'); }
    public function propertyGroup() { return $this->belongsTo(ProjectPropertyGroup::class,'property_group_id'); }
    public function projects()      { return $this->hasMany(Project::class,'catalogue_id'); }
}

// app/Models/ProjectPropertyGroup.php
class ProjectPropertyGroup extends Model
{
    protected $table = 'project_property_groups';
    public function types()      { return $this->hasMany(ProjectType::class,'group_id'); }
    public function catalogues() { return $this->hasMany(ProjectCatalogue::class,'property_group_id'); }
}

// app/Models/ProjectItem.php
class ProjectItem extends Model
{
    protected $table = 'project_items';
    protected $casts = ['extra_fields' => 'array'];
    public function project() { return $this->belongsTo(Project::class,'project_id'); }
}
```

---

## 6. THỨ TỰ THỰC HIỆN MODULE

```
MODULE 1 → project_property_groups
  ├── Migration
  ├── Model: ProjectPropertyGroup
  └── Seeder: ProjectPropertyGroupSeeder (6 records)

MODULE 2 → project_types
  ├── Migration
  ├── Model: ProjectType
  └── Seeder: ProjectTypeSeeder (18 records, lookup group_id by code)

MODULE 3 → project_catalogues
  ├── Migration
  ├── Model: ProjectCatalogue
  └── Seeder: ProjectCatalogueSeeder (cây 2 cấp, dùng firstOrCreate)

MODULE 4 → projects
  ├── Migration (bảng trung tâm, đầy đủ indexes + FULLTEXT)
  ├── Model: Project (SoftDeletes, casts, relationships, scopes)
  ├── Observer: ProjectObserver (register trong AppServiceProvider)
  └── LocationService (load/cache JSON file địa lý)

MODULE 5 → project_items
  ├── Migration
  └── Model: ProjectItem

MODULE 6 → contact_requests
  ├── Migration: rename visit_requests + addColumn
  └── Model: ContactRequest

MODULE 7 → project_views
  ├── Migration
  └── Model: ProjectView


data example: /data_example.md -> đọc để hiểu thêm về dữ liệu cách triển khai admin nhé
```
