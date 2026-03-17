# 📋 SAMPLE DATA — BẢNG `projects` (ĐẦY ĐỦ CASE)

> Bao gồm tất cả `property_group`, `transaction_type`, `is_project`, địa chỉ trước/sau sáp nhập, giá thỏa thuận, tin dự án...

---

## RECORD 1 — Căn hộ | Mua bán | Địa chỉ cũ (HCM không sáp nhập)

> **Case:** apartment · sale · địa chỉ cũ = mới (HCM giữ nguyên) · full field

```
id: 1 | code: BDS-000001
slug: ban-can-ho-2pn-vinhomes-central-park-q-binh-thanh-bds-000001
catalogue_id: 10   | property_group: apartment
type_code: can_ho_chung_cu
transaction_type: sale | is_project: 0

title:   Bán căn hộ 2PN Vinhomes Central Park - Quận Bình Thạnh
summary: Căn góc tầng cao, view sông, nội thất cao cấp CĐT
price:   4500000000 | price_negotiable: 0
price_unit: total   | price_vnd: 4500000000
area: 68.50 | area_use: 62.00 | area_land: NULL | length: NULL | width: NULL
bedrooms: 2 | bathrooms: 2 | floors: 25 | floor_number: 15
direction: dong_nam | balcony_direction: nam
legal_status: so_hong | furniture_status: full

── ĐỊA CHỈ CŨ (trước 01/07/2025) ─────────
province_code: thanh_pho_ho_chi_minh | province_name: Thành phố Hồ Chí Minh
district_code: quan_binh_thanh       | district_name: Quận Bình Thạnh
ward_code: phuong_22                 | ward_name: Phường 22

── ĐỊA CHỈ MỚI (HCM không sáp nhập → giữ nguyên) ─
province_new_code: thanh_pho_ho_chi_minh | province_new_name: Thành phố Hồ Chí Minh
ward_new_code: phuong_22                 | ward_new_name: Phường 22

address: 208 Nguyễn Hữu Cảnh

latitude: 10.7956700 | longitude: 106.7220500

has_elevator: 1 | has_pool: 1 | has_parking: 1 | has_security: 1
has_balcony: 1  | has_rooftop: 0 | has_basement: 0 | has_gym: 1
has_ac: 0 | has_wifi: 0

image: /ckfinder/userfiles/projects/BDS-000001/cover.jpg
album: projects/BDS-000001
has_video: 1 | video_url: https://www.youtube.com/watch?v=abc123
video_embed: https://www.youtube.com/embed/abc123
has_virtual_tour: 1 | virtual_tour_url: https://my.matterport.com/show/?m=xxx

extra_fields: {
  "block_name":     "Park 1",
  "apartment_code": "P1-15.08",
  "year_built":     2018,
  "management_fee": 16500,
  "handover_date":  "2019-06",
  "view_type":      "river",
  "area_balcony":   8.5,
  "virtual_tour_url": "https://my.matterport.com/show/?m=xxx"
}

status: active | publish: 2 | is_featured: 1 | is_hot: 0 | is_urgent: 0
sort_order: 10 | view_count: 1520
meta_title: Bán căn hộ 2PN Vinhomes Central Park Quận Bình Thạnh 68.5m²
meta_desc: Căn hộ 2PN tầng 15, view sông, full nội thất, sổ hồng. Giá 4.5 tỷ.
focus_keyword: căn hộ vinhomes central park quận bình thạnh
published_at: 2025-01-10 09:00:00
created_at: 2025-01-10 08:30:00 | updated_at: 2025-01-10 09:00:00
deleted_at: NULL
```

---

## RECORD 2 — Nhà phố | Mua bán | Địa chỉ tỉnh sáp nhập (Huế mới)

> **Case:** house · sale · tỉnh cũ = Thừa Thiên Huế, tỉnh mới = Huế · giá thỏa thuận

```
id: 2 | code: BDS-000002
slug: ban-nha-mat-tien-tp-hue-bds-000002
catalogue_id: 11   | property_group: house
type_code: nha_mat_tien
transaction_type: sale | is_project: 0

title:   Bán nhà mặt tiền 3 tầng đường Lê Lợi - TP. Huế
summary: Nhà mặt tiền trung tâm, kinh doanh tốt, sổ đỏ chính chủ
price:   NULL | price_negotiable: 1
price_unit: total | price_vnd: NULL
area: 120.00 | area_use: NULL | area_land: 120.00 | length: 20.00 | width: 6.00
bedrooms: 4 | bathrooms: 3 | floors: 3 | floor_number: NULL
direction: dong | balcony_direction: NULL
legal_status: so_do | furniture_status: basic

── ĐỊA CHỈ CŨ ─────────────────────────────
province_code: thua_thien_hue | province_name: Thừa Thiên Huế
district_code: thanh_pho_hue  | district_name: Thành phố Huế
ward_code: phuong_vinh_ninh   | ward_name: Phường Vĩnh Ninh

── ĐỊA CHỈ MỚI (Thừa Thiên Huế + Quảng Trị → Huế) ─
province_new_code: hue  | province_new_name: Huế
ward_new_code: phuong_vinh_ninh | ward_new_name: Phường Vĩnh Ninh

address: 45 Lê Lợi

latitude: 16.4674400 | longitude: 107.5905000

has_elevator: 0 | has_pool: 0 | has_parking: 1 | has_security: 0
has_balcony: 1  | has_rooftop: 1 | has_basement: 0 | has_gym: 0
has_ac: 1 | has_wifi: 0

image: /ckfinder/userfiles/projects/BDS-000002/cover.jpg
album: projects/BDS-000002
has_video: 0 | video_url: NULL | video_embed: NULL
has_virtual_tour: 0 | virtual_tour_url: NULL

extra_fields: {
  "year_built":       2010,
  "renovation_year":  2023,
  "road_type":        "nhua",
  "alley_width":      NULL,
  "electricity_type": "kinh_doanh",
  "has_mezzanine":    false
}

status: active | publish: 2 | is_featured: 0 | is_hot: 1 | is_urgent: 0
sort_order: 0 | view_count: 340
meta_title: NULL | meta_desc: NULL | focus_keyword: NULL
published_at: 2025-02-15 10:00:00
```

---

## RECORD 3 — Đất thổ cư | Mua bán | Tỉnh sáp nhập mới (chưa có địa chỉ cũ)

> **Case:** land · sale · tin đăng SAU sáp nhập 01/07/2025 → district_code = NULL

```
id: 3 | code: BDS-000003
slug: ban-dat-tho-cu-tinh-hue-bds-000003
catalogue_id: 12   | property_group: land
type_code: dat_tho_cu
transaction_type: sale | is_project: 0

title:   Bán đất thổ cư 2 mặt tiền - Tỉnh Huế (sau sáp nhập)
summary: Lô đất góc, 2 mặt tiền, đường nhựa 8m, sổ đỏ sẵn
price:   850000000 | price_negotiable: 0
price_unit: total  | price_vnd: 850000000
area: 180.00 | area_use: NULL | area_land: 180.00 | length: 15.00 | width: 12.00
bedrooms: NULL | bathrooms: NULL | floors: NULL | floor_number: NULL
direction: bac | balcony_direction: NULL
legal_status: so_do | furniture_status: none

── ĐỊA CHỈ CŨ ─────────────────────────────
province_code: NULL | province_name: NULL
district_code: NULL | district_name: NULL
ward_code: NULL     | ward_name: NULL
(Tin đăng sau 01/07/2025 — không có địa chỉ cũ)

── ĐỊA CHỈ MỚI ─────────────────────────────
province_new_code: hue | province_new_name: Huế
ward_new_code: phuong_thuy_xuan | ward_new_name: Phường Thủy Xuân

address: Đường Nguyễn Phúc Nguyên, gần trường THPT Nguyễn Huệ

latitude: 16.4320000 | longitude: 107.5880000

has_elevator: 0 | has_pool: 0 | has_parking: 0 | has_security: 0
has_balcony: 0  | has_rooftop: 0 | has_basement: 0 | has_gym: 0
has_ac: 0 | has_wifi: 0

image: /ckfinder/userfiles/projects/BDS-000003/cover.jpg
album: projects/BDS-000003
has_video: 0 | has_virtual_tour: 0

extra_fields: {
  "land_type":      "tho_cu",
  "zoning":         "dan_cu",
  "road_width":     8,
  "infrastructure": ["dien", "nuoc", "duong"],
  "current_use":    "dat_trong",
  "is_corner":      true,
  "quy_hoach_url":  "/ckfinder/userfiles/projects/BDS-000003/qh.pdf"
}

status: active | publish: 2 | is_featured: 0 | is_hot: 0 | is_urgent: 1
sort_order: 0 | view_count: 89
published_at: 2025-07-05 08:00:00
```

---

## RECORD 4 — Mặt bằng | Cho thuê | Giá per_m2_month

> **Case:** commercial · rent · price_unit=per_m2_month · price_vnd auto-calc

```
id: 4 | code: BDS-000004
slug: cho-thue-mat-bang-mat-tien-quan-1-bds-000004
catalogue_id: 13   | property_group: commercial
type_code: mat_bang
transaction_type: rent | is_project: 0

title:   Cho thuê mặt bằng mặt tiền Nguyễn Trãi - Quận 1
summary: Mặt bằng tầng trệt 100m², mặt tiền 8m, phù hợp F&B, thời trang
price:   350000 | price_negotiable: 0
price_unit: per_m2_month | price_vnd: 35000000
(price_vnd = 350,000 × 100m² = 35,000,000)
area: 100.00 | area_use: NULL | area_land: NULL | length: 12.50 | width: 8.00
bedrooms: NULL | bathrooms: NULL | floors: 1 | floor_number: 1
direction: nam | balcony_direction: NULL
legal_status: NULL | furniture_status: none

── ĐỊA CHỈ CŨ ─────────────────────────────
province_code: thanh_pho_ho_chi_minh | province_name: Thành phố Hồ Chí Minh
district_code: quan_1                | district_name: Quận 1
ward_code: phuong_nguyen_cu_trinh    | ward_name: Phường Nguyễn Cư Trinh

── ĐỊA CHỈ MỚI (HCM không sáp nhập) ─────
province_new_code: thanh_pho_ho_chi_minh | province_new_name: Thành phố Hồ Chí Minh
ward_new_code: phuong_nguyen_cu_trinh    | ward_new_name: Phường Nguyễn Cư Trinh

address: 123 Nguyễn Trãi

latitude: 10.7650000 | longitude: 106.6880000

has_elevator: 0 | has_pool: 0 | has_parking: 1 | has_security: 1
has_balcony: 0  | has_rooftop: 0 | has_basement: 0 | has_gym: 0
has_ac: 1 | has_wifi: 0

image: /ckfinder/userfiles/projects/BDS-000004/cover.jpg
album: projects/BDS-000004
has_video: 0 | has_virtual_tour: 0

extra_fields: {
  "space_type":           "shop",
  "height_clear":         4.2,
  "electricity_capacity": 30,
  "security_deposit":     3,
  "min_lease_term":       "1y",
  "parking_slots":        2,
  "allowed_business":     "Nhà hàng, Quán cà phê, Thời trang"
}

status: active | publish: 2 | is_featured: 1 | is_hot: 0 | is_urgent: 0
sort_order: 5 | view_count: 670
published_at: 2025-03-01 09:30:00
```

---

## RECORD 5 — Phòng trọ | Cho thuê | Giá per_month

> **Case:** room · rent · price_unit=per_month · amenities phòng trọ · giá thấp

```
id: 5 | code: BDS-000005
slug: cho-thue-phong-tro-full-do-binh-duong-bds-000005
catalogue_id: 14   | property_group: room
type_code: phong_tro
transaction_type: rent | is_project: 0

title:   Cho thuê phòng trọ full nội thất, WC riêng - Bình Dương
summary: Phòng mới xây, điều hòa, nước nóng, WC riêng, gần KCN VSIP
price:   3200000 | price_negotiable: 0
price_unit: per_month | price_vnd: 3200000
area: 25.00 | area_use: NULL | area_land: NULL | length: NULL | width: NULL
bedrooms: 1 | bathrooms: 1 | floors: 3 | floor_number: 2
direction: NULL | balcony_direction: NULL
legal_status: NULL | furniture_status: full

── ĐỊA CHỈ CŨ (Bình Dương không sáp nhập) ─
province_code: binh_duong | province_name: Bình Dương
district_code: thanh_pho_thu_dau_mot | district_name: Thành phố Thủ Dầu Một
ward_code: phuong_hiep_thanh         | ward_name: Phường Hiệp Thành

── ĐỊA CHỈ MỚI ─────────────────────────────
province_new_code: binh_duong | province_new_name: Bình Dương
ward_new_code: phuong_hiep_thanh | ward_new_name: Phường Hiệp Thành

address: Hẻm 12, đường Thủ Khoa Huân

latitude: 10.9800000 | longitude: 106.6520000

has_elevator: 0 | has_pool: 0 | has_parking: 1 | has_security: 1
has_balcony: 0  | has_rooftop: 0 | has_basement: 0 | has_gym: 0
has_ac: 1 | has_wifi: 1

image: /ckfinder/userfiles/projects/BDS-000005/cover.jpg
album: projects/BDS-000005
has_video: 0 | has_virtual_tour: 0

extra_fields: {
  "electricity_price":  3500,
  "water_price":        15000,
  "max_people":         2,
  "gender_restriction": "any",
  "pet_allowed":        false,
  "deposit_months":     2,
  "available_from":     "2025-04-01"
}

status: active | publish: 2 | is_featured: 0 | is_hot: 0 | is_urgent: 0
sort_order: 0 | view_count: 215
published_at: 2025-03-20 14:00:00
```

---

## RECORD 6 — Biệt thự | Cho thuê | Giá per_month · Nội thất cơ bản

> **Case:** house · rent · price_unit=per_month · furniture_status=basic

```
id: 6 | code: BDS-000006
slug: cho-thue-biet-thu-ho-boi-rieng-quan-2-bds-000006
catalogue_id: 11   | property_group: house
type_code: biet_thu
transaction_type: rent | is_project: 0

title:   Cho thuê biệt thự hồ bơi riêng, sân vườn - Quận 2 (Thủ Đức)
summary: Biệt thự 500m² sân vườn, hồ bơi riêng, 5PN, phù hợp gia đình nước ngoài
price:   80000000 | price_negotiable: 1
price_unit: per_month | price_vnd: 80000000
area: 500.00 | area_use: 350.00 | area_land: 500.00 | length: 25.00 | width: 20.00
bedrooms: 5 | bathrooms: 5 | floors: 3 | floor_number: NULL
direction: tay_nam | balcony_direction: NULL
legal_status: so_hong | furniture_status: basic

── ĐỊA CHỈ CŨ ─────────────────────────────
province_code: thanh_pho_ho_chi_minh | province_name: Thành phố Hồ Chí Minh
district_code: quan_2                | district_name: Quận 2
ward_code: phuong_an_phu             | ward_name: Phường An Phú

── ĐỊA CHỈ MỚI (Quận 2 → TP Thủ Đức) ────
province_new_code: thanh_pho_ho_chi_minh | province_new_name: Thành phố Hồ Chí Minh
ward_new_code: phuong_an_phu             | ward_new_name: Phường An Phú
(Quận 2 → TP Thủ Đức từ 2021, không liên quan sáp nhập 2025)

address: 88 Trần Não

latitude: 10.7890000 | longitude: 106.7480000

has_elevator: 0 | has_pool: 1 | has_parking: 1 | has_security: 1
has_balcony: 1  | has_rooftop: 1 | has_basement: 1 | has_gym: 0
has_ac: 1 | has_wifi: 0

image: /ckfinder/userfiles/projects/BDS-000006/cover.jpg
album: projects/BDS-000006
has_video: 0 | has_virtual_tour: 0

extra_fields: {
  "year_built":       2015,
  "renovation_year":  2022,
  "road_type":        "nhua",
  "alley_width":      NULL,
  "electricity_type": "sinh_hoat",
  "has_mezzanine":    false
}

status: active | publish: 2 | is_featured: 1 | is_hot: 0 | is_urgent: 0
sort_order: 0 | view_count: 890
published_at: 2025-01-25 11:00:00
```

---

## RECORD 7 — Đất nông nghiệp | Mua bán | Giá per_m2

> **Case:** land · sale · price_unit=per_m2 · price_vnd = price × area

```
id: 7 | code: BDS-000007
slug: ban-dat-nong-nghiep-lam-dong-bds-000007
catalogue_id: 12   | property_group: land
type_code: dat_nong_nghiep
transaction_type: sale | is_project: 0

title:   Bán đất nông nghiệp view đồi chè - Bảo Lộc, Lâm Đồng
summary: 5000m² đất nông nghiệp, đường ô tô vào tận nơi, thích hợp làm farmstay
price:   350000 | price_negotiable: 1
price_unit: per_m2 | price_vnd: 1750000000
(price_vnd = 350,000 × 5,000m² = 1,750,000,000)
area: 5000.00 | area_use: NULL | area_land: 5000.00 | length: 100.00 | width: 50.00
bedrooms: NULL | bathrooms: NULL | floors: NULL | floor_number: NULL
direction: NULL | balcony_direction: NULL
legal_status: so_do | furniture_status: none

── ĐỊA CHỈ CŨ ─────────────────────────────
province_code: lam_dong | province_name: Lâm Đồng
district_code: thanh_pho_bao_loc | district_name: Thành phố Bảo Lộc
ward_code: xa_loc_nga             | ward_name: Xã Lộc Nga

── ĐỊA CHỈ MỚI (Lâm Đồng không sáp nhập) ─
province_new_code: lam_dong | province_new_name: Lâm Đồng
ward_new_code: xa_loc_nga   | ward_new_name: Xã Lộc Nga

address: Thôn 4, đường vào Khu Du lịch Đồi Chè

latitude: 11.5400000 | longitude: 107.8100000

has_elevator: 0 | has_pool: 0 | has_parking: 0 | has_security: 0
has_balcony: 0  | has_rooftop: 0 | has_basement: 0 | has_gym: 0
has_ac: 0 | has_wifi: 0

image: /ckfinder/userfiles/projects/BDS-000007/cover.jpg
album: projects/BDS-000007
has_video: 0 | has_virtual_tour: 0

extra_fields: {
  "land_type":      "nong_nghiep",
  "zoning":         "nong_nghiep",
  "road_width":     4,
  "infrastructure": ["dien", "duong"],
  "current_use":    "co_cay",
  "is_corner":      false,
  "quy_hoach_url":  NULL
}

status: active | publish: 2 | is_featured: 0 | is_hot: 0 | is_urgent: 0
sort_order: 0 | view_count: 120
published_at: 2025-04-01 08:00:00
```

---

## RECORD 8 — Văn phòng | Cho thuê | Giá per_month · Tòa nhà

> **Case:** commercial · rent · văn phòng tầng cao · price_unit=per_month

```
id: 8 | code: BDS-000008
slug: cho-thue-van-phong-hang-a-bitexco-quan-1-bds-000008
catalogue_id: 13   | property_group: commercial
type_code: van_phong
transaction_type: rent | is_project: 0

title:   Cho thuê văn phòng hạng A Bitexco Financial Tower - Quận 1
summary: VP 200m² tầng 20, view sông Sài Gòn, nội thất hoàn thiện, sẵn sàng hoạt động
price:   180000000 | price_negotiable: 0
price_unit: per_month | price_vnd: 180000000
area: 200.00 | area_use: 185.00 | area_land: NULL | length: NULL | width: NULL
bedrooms: NULL | bathrooms: NULL | floors: 68 | floor_number: 20
direction: dong | balcony_direction: NULL
legal_status: NULL | furniture_status: full

── ĐỊA CHỈ CŨ ─────────────────────────────
province_code: thanh_pho_ho_chi_minh | province_name: Thành phố Hồ Chí Minh
district_code: quan_1                | district_name: Quận 1
ward_code: phuong_ben_nghe           | ward_name: Phường Bến Nghé

── ĐỊA CHỈ MỚI ─────────────────────────────
province_new_code: thanh_pho_ho_chi_minh | province_new_name: Thành phố Hồ Chí Minh
ward_new_code: phuong_ben_nghe           | ward_new_name: Phường Bến Nghé

address: 2 Hải Triều

latitude: 10.7714000 | longitude: 106.7046000

has_elevator: 1 | has_pool: 0 | has_parking: 1 | has_security: 1
has_balcony: 0  | has_rooftop: 0 | has_basement: 1 | has_gym: 0
has_ac: 1 | has_wifi: 1

image: /ckfinder/userfiles/projects/BDS-000008/cover.jpg
album: projects/BDS-000008
has_video: 0 | has_virtual_tour: 0

extra_fields: {
  "space_type":           "office",
  "height_clear":         3.2,
  "electricity_capacity": 100,
  "security_deposit":     2,
  "min_lease_term":       "1y",
  "parking_slots":        4,
  "allowed_business":     "Văn phòng, Công ty nước ngoài"
}

status: active | publish: 2 | is_featured: 1 | is_hot: 0 | is_urgent: 0
sort_order: 8 | view_count: 445
published_at: 2025-02-20 10:00:00
```

---

## RECORD 9 — Nhà ở xã hội | Mua bán | Pháp lý đang chờ sổ

> **Case:** apartment · sale · nha_o_xa_hoi · legal_status=dang_cho_so · không thỏa thuận

```
id: 9 | code: BDS-000009
slug: ban-nha-o-xa-hoi-binh-chanh-bds-000009
catalogue_id: 10   | property_group: apartment
type_code: nha_o_xa_hoi
transaction_type: sale | is_project: 0

title:   Bán căn hộ nhà ở xã hội 2PN dự án Ecohome - Bình Chánh
summary: Suất nội bộ nhà ở xã hội, đủ điều kiện mua, bàn giao hoàn thiện
price:   1200000000 | price_negotiable: 0
price_unit: total   | price_vnd: 1200000000
area: 65.00 | area_use: 60.00 | area_land: NULL | length: NULL | width: NULL
bedrooms: 2 | bathrooms: 1 | floors: 15 | floor_number: 8
direction: bac | balcony_direction: NULL
legal_status: dang_cho_so | furniture_status: none

── ĐỊA CHỈ CŨ ─────────────────────────────
province_code: thanh_pho_ho_chi_minh | province_name: Thành phố Hồ Chí Minh
district_code: huyen_binh_chanh      | district_name: Huyện Bình Chánh
ward_code: xa_binh_hung              | ward_name: Xã Bình Hưng

── ĐỊA CHỈ MỚI ─────────────────────────────
province_new_code: thanh_pho_ho_chi_minh | province_new_name: Thành phố Hồ Chí Minh
ward_new_code: xa_binh_hung              | ward_new_name: Xã Bình Hưng

address: Đường Nguyễn Hữu Thọ, KDC Bình Hưng

has_elevator: 1 | has_pool: 0 | has_parking: 1 | has_security: 1
has_balcony: 1  | has_rooftop: 0 | has_basement: 0 | has_gym: 0
has_ac: 0 | has_wifi: 0

image: /ckfinder/userfiles/projects/BDS-000009/cover.jpg
album: projects/BDS-000009
has_video: 0 | has_virtual_tour: 0

extra_fields: {
  "block_name":       "B2",
  "apartment_code":   "B2-08.03",
  "year_built":       2024,
  "management_fee":   8000,
  "handover_date":    "2025-09",
  "view_type":        "city",
  "area_balcony":     5.0
}

status: active | publish: 2 | is_featured: 0 | is_hot: 1 | is_urgent: 0
sort_order: 0 | view_count: 980
published_at: 2025-05-01 09:00:00
```

---

## RECORD 10 — Condotel | Mua bán · Không sổ

> **Case:** apartment · sale · condotel · legal_status=du_an · không có hướng nhà

```
id: 10 | code: BDS-000010
slug: ban-condotel-da-nang-bds-000010
catalogue_id: 10   | property_group: apartment
type_code: condotel
transaction_type: sale | is_project: 0

title:   Bán condotel 5 sao The Grand - Đà Nẵng view biển
summary: Cam kết lợi nhuận 10%/năm trong 10 năm từ CĐT, pháp lý dự án
price:   2800000000 | price_negotiable: 0
price_unit: total   | price_vnd: 2800000000
area: 42.00 | area_use: 38.00 | area_land: NULL | length: NULL | width: NULL
bedrooms: 1 | bathrooms: 1 | floors: 30 | floor_number: 22
direction: dong | balcony_direction: dong
legal_status: du_an | furniture_status: full

── ĐỊA CHỈ CŨ ─────────────────────────────
province_code: da_nang | province_name: Đà Nẵng
district_code: quan_son_tra | district_name: Quận Sơn Trà
ward_code: phuong_man_thai  | ward_name: Phường Mân Thái

── ĐỊA CHỈ MỚI (Đà Nẵng không sáp nhập) ─
province_new_code: da_nang | province_new_name: Đà Nẵng
ward_new_code: phuong_man_thai | ward_new_name: Phường Mân Thái

address: Võ Nguyên Giáp, Mỹ Khê Beach

latitude: 16.0600000 | longitude: 108.2470000

has_elevator: 1 | has_pool: 1 | has_parking: 1 | has_security: 1
has_balcony: 1  | has_rooftop: 0 | has_basement: 1 | has_gym: 1
has_ac: 1 | has_wifi: 1

extra_fields: {
  "block_name":         "Tower A",
  "apartment_code":     "A-22.08",
  "year_built":         2023,
  "management_fee":     NULL,
  "handover_date":      "2024-12",
  "view_type":          "sea",
  "area_balcony":       6.0,
  "virtual_tour_url":   "https://tour360.example.com/condotel-da-nang"
}

status: active | publish: 2 | is_featured: 1 | is_hot: 1 | is_urgent: 0
sort_order: 15 | view_count: 2100
published_at: 2025-01-05 08:00:00
```

---

## RECORD 11 — Dự án căn hộ | is_project=1 | Có project_items

> **Case:** project · sale · is_project=1 · extra_fields chứa thông tin CĐT/tiến độ

```
id: 11 | code: BDS-000011
slug: du-an-vinhomes-grand-park-quan-9-bds-000011
catalogue_id: 15   | property_group: project
type_code: du_an_can_ho
transaction_type: sale | is_project: 0

title:   Dự án Vinhomes Grand Park - Quận 9 (TP. Thủ Đức)
summary: Đại đô thị 271ha, 44 tòa căn hộ, công viên 36ha, tiện ích đẳng cấp
price:   2500000000 | price_negotiable: 0
price_unit: total   | price_vnd: 2500000000
(price là giá căn thấp nhất; price_vnd dùng để filter "từ 2.5 tỷ")
area: 68.00 | area_use: NULL | area_land: NULL | length: NULL | width: NULL
(area = DT căn nhỏ nhất của dự án — để filter range diện tích)
bedrooms: 1 | bathrooms: NULL | floors: 35 | floor_number: NULL
direction: NULL | balcony_direction: NULL
legal_status: du_an | furniture_status: none

── ĐỊA CHỈ CŨ ─────────────────────────────
province_code: thanh_pho_ho_chi_minh | province_name: Thành phố Hồ Chí Minh
district_code: quan_9                | district_name: Quận 9
ward_code: phuong_long_thanh_my      | ward_name: Phường Long Thạnh Mỹ

── ĐỊA CHỈ MỚI (Quận 9 → TP Thủ Đức) ────
province_new_code: thanh_pho_ho_chi_minh | province_new_name: Thành phố Hồ Chí Minh
ward_new_code: phuong_long_thanh_my      | ward_new_name: Phường Long Thạnh Mỹ

address: Phước Thiện, Long Thạnh Mỹ

latitude: 10.8490000 | longitude: 106.8210000

has_elevator: 1 | has_pool: 1 | has_parking: 1 | has_security: 1
has_balcony: 1  | has_rooftop: 0 | has_basement: 1 | has_gym: 1
has_ac: 0 | has_wifi: 0

image: /ckfinder/userfiles/projects/BDS-000011/cover.jpg
album: projects/BDS-000011
has_video: 1 | video_url: https://www.youtube.com/watch?v=def456
video_embed: https://www.youtube.com/embed/def456
has_virtual_tour: 1 | virtual_tour_url: https://tour.vinhomes.com/grand-park

extra_fields: {
  "developer_name":     "Vinhomes JSC",
  "developer_logo":     "/ckfinder/userfiles/projects/BDS-000011/logo.png",
  "distributor_name":   "CBRE Vietnam",
  "total_units":        11000,
  "total_blocks":       44,
  "area_total":         2710000,
  "started_at":         "2019-01",
  "expected_handover":  "2025-12",
  "progress_pct":       85,
  "price_per_m2_min":   35000000,
  "price_per_m2_max":   65000000,
  "ownership_type":     "long_term",
  "brochure_url":       "/ckfinder/userfiles/projects/BDS-000011/brochure.pdf",
  "highlights":         "<ul><li>Công viên 36ha lớn nhất VN</li><li>Hệ thống tiện ích 5 sao</li></ul>"
}

status: active | publish: 2 | is_featured: 1 | is_hot: 0 | is_urgent: 0
sort_order: 20 | view_count: 8900
published_at: 2025-01-01 08:00:00
```

**project_items của Record 11:**

```
── Item 1 ──────────────────────────────────
id: 1 | project_id: 11
name: Căn 1 phòng ngủ - Loại S | item_code: 1PN-S
bedrooms: 1 | bathrooms: 1
area_min: 28.00 | area_max: 35.00
price_min: 1200000000 | price_max: 1500000000 | price_per_m2: 42000000
total_units: 2200 | available_units: 450 | item_status: available
floor_plan_image: /ckfinder/userfiles/projects/BDS-000011/fp-1pn-s.jpg
extra_fields: { "floor_number_min": 5, "floor_number_max": 35, "direction": "dong_nam" }
publish: 2

── Item 2 ──────────────────────────────────
id: 2 | project_id: 11
name: Căn 2 phòng ngủ - Loại A | item_code: 2PN-A
bedrooms: 2 | bathrooms: 2
area_min: 55.00 | area_max: 72.00
price_min: 2300000000 | price_max: 3100000000 | price_per_m2: 43000000
total_units: 5500 | available_units: 120 | item_status: limited
floor_plan_image: /ckfinder/userfiles/projects/BDS-000011/fp-2pn-a.jpg
extra_fields: { "floor_number_min": 5, "floor_number_max": 35, "direction": "tay_bac", "area_balcony": 8.5 }
publish: 2

── Item 3 ──────────────────────────────────
id: 3 | project_id: 11
name: Căn 3 phòng ngủ - Loại B | item_code: 3PN-B
bedrooms: 3 | bathrooms: 2
area_min: 85.00 | area_max: 98.00
price_min: 3800000000 | price_max: 4500000000 | price_per_m2: 46000000
total_units: 2800 | available_units: 0 | item_status: sold_out
floor_plan_image: /ckfinder/userfiles/projects/BDS-000011/fp-3pn-b.jpg
extra_fields: { "floor_number_min": 10, "floor_number_max": 35, "view_type": "park" }
publish: 2
```

---

## RECORD 12 — Đất nền dự án | is_project=1 | Có project_items là các lô đất

> **Case:** project · sale · land project · items là từng lô đất

```
id: 12 | code: BDS-000012
slug: du-an-dat-nen-long-an-bds-000012
catalogue_id: 15   | property_group: project
type_code: du_an_nha_o
transaction_type: sale | is_project: 0

title:   Dự án đất nền phân lô Eco Garden - Long An
summary: 200 lô đất nền, sổ đỏ từng lô, hạ tầng hoàn chỉnh, giá gốc CĐT
price:   650000000 | price_negotiable: 0
price_unit: total  | price_vnd: 650000000
area: 80.00 | area_use: NULL | area_land: 80.00 | length: NULL | width: NULL
bedrooms: NULL | bathrooms: NULL | floors: NULL | floor_number: NULL
direction: NULL | balcony_direction: NULL
legal_status: so_do | furniture_status: none

── ĐỊA CHỈ CŨ ─────────────────────────────
province_code: long_an | province_name: Long An
district_code: huyen_duc_hoa | district_name: Huyện Đức Hòa
ward_code: xa_duc_hoa        | ward_name: Xã Đức Hòa

── ĐỊA CHỈ MỚI (Long An không sáp nhập) ─
province_new_code: long_an | province_new_name: Long An
ward_new_code: xa_duc_hoa  | ward_new_name: Xã Đức Hòa

address: Đường Nguyễn Văn Tiếp, KDC Đức Hòa

latitude: 10.8200000 | longitude: 106.4900000

has_elevator: 0 | has_pool: 0 | has_parking: 0 | has_security: 1
has_balcony: 0  | has_rooftop: 0 | has_basement: 0 | has_gym: 0
has_ac: 0 | has_wifi: 0

image: /ckfinder/userfiles/projects/BDS-000012/cover.jpg
album: projects/BDS-000012
has_video: 0 | has_virtual_tour: 0

extra_fields: {
  "developer_name":    "Eco Garden JSC",
  "developer_logo":    "/ckfinder/userfiles/projects/BDS-000012/logo.png",
  "total_units":       200,
  "total_blocks":      5,
  "area_total":        50000,
  "started_at":        "2024-06",
  "expected_handover": "2025-06",
  "progress_pct":      100,
  "price_per_m2_min":  8000000,
  "price_per_m2_max":  12000000,
  "ownership_type":    "long_term",
  "brochure_url":      "/ckfinder/userfiles/projects/BDS-000012/brochure.pdf",
  "highlights":        "<ul><li>Hạ tầng hoàn chỉnh</li><li>Sổ đỏ từng lô</li></ul>"
}

status: active | publish: 2 | is_featured: 0 | is_hot: 1 | is_urgent: 0
sort_order: 0 | view_count: 560
published_at: 2025-03-10 08:00:00
```

**project_items của Record 12 (lô đất):**

```
── Item 4 ──────────────────────────────────
id: 4 | project_id: 12
name: Lô A1-05 | item_code: A1-05
bedrooms: NULL | bathrooms: NULL
area_min: 80.00 | area_max: 80.00
price_min: 640000000 | price_max: 640000000 | price_per_m2: 8000000
total_units: 1 | available_units: 1 | item_status: available
floor_plan_image: /ckfinder/userfiles/projects/BDS-000012/lot-a1-05.jpg
extra_fields: { "width": 5, "length": 16, "road_width": 8, "is_corner": false, "legal_status": "so_do" }
publish: 2

── Item 5 ──────────────────────────────────
id: 5 | project_id: 12
name: Lô góc B2-01 | item_code: B2-01
bedrooms: NULL | bathrooms: NULL
area_min: 120.00 | area_max: 120.00
price_min: 1440000000 | price_max: 1440000000 | price_per_m2: 12000000
total_units: 1 | available_units: 0 | item_status: sold_out
floor_plan_image: /ckfinder/userfiles/projects/BDS-000012/lot-b2-01.jpg
extra_fields: { "width": 8, "length": 15, "road_width": 10, "is_corner": true, "legal_status": "so_do" }
publish: 2
```

---

## RECORD 13 — Tin nháp (chưa đăng)

> **Case:** status=draft · publish=1 · chưa có published_at

```
id: 13 | code: BDS-000013
slug: can-ho-officetel-quan-4-bds-000013
catalogue_id: 10   | property_group: apartment
type_code: officetel
transaction_type: rent | is_project: 0

title:   Cho thuê officetel full nội thất - Quận 4
price:   12000000 | price_negotiable: 0
price_unit: per_month | price_vnd: 12000000
area: 32.00 | bedrooms: 0 | bathrooms: 1
direction: NULL | legal_status: so_hong | furniture_status: full

province_code: thanh_pho_ho_chi_minh | province_name: Thành phố Hồ Chí Minh
district_code: quan_4                | district_name: Quận 4
ward_code: phuong_9                  | ward_name: Phường 9
province_new_code: thanh_pho_ho_chi_minh | ward_new_code: phuong_9
address: 22 Đoàn Văn Bơ

extra_fields: {
  "year_built": 2022, "management_fee": 10000, "view_type": "city"
}

image: NULL | album: projects/BDS-000013
has_video: 0 | has_virtual_tour: 0

status: draft | publish: 1 | is_featured: 0 | is_hot: 0 | is_urgent: 0
sort_order: 0 | view_count: 0 | published_at: NULL
created_at: 2025-06-01 15:00:00
```

---

## RECORD 14 — Tin đã bán (status=sold)

> **Case:** status=sold · publish=2 · vẫn hiển thị với badge "Đã bán"

```
id: 14 | code: BDS-000014
slug: ban-nha-pho-3-tang-go-vap-bds-000014
catalogue_id: 11   | property_group: house
type_code: nha_mat_tien
transaction_type: sale | is_project: 0

title:   Bán nhà phố 3 tầng mặt tiền đường D1 - Gò Vấp
price:   6800000000 | price_negotiable: 0
price_unit: total   | price_vnd: 6800000000
area: 80.00 | area_land: 80.00 | length: 16.00 | width: 5.00
bedrooms: 4 | bathrooms: 4 | floors: 3
direction: nam | legal_status: so_hong | furniture_status: negotiable

province_code: thanh_pho_ho_chi_minh | district_code: quan_go_vap
province_name: Thành phố Hồ Chí Minh | district_name: Quận Gò Vấp
ward_code: phuong_15 | ward_name: Phường 15
province_new_code: thanh_pho_ho_chi_minh | ward_new_code: phuong_15
address: 56 Đường D1, KDC Nam Long

extra_fields: { "year_built": 2008, "renovation_year": 2020, "road_type": "nhua", "electricity_type": "kinh_doanh" }

image: /ckfinder/userfiles/projects/BDS-000014/cover.jpg
album: projects/BDS-000014

status: sold | publish: 2 | is_featured: 0 | is_hot: 0 | is_urgent: 0
sort_order: 0 | view_count: 3200
published_at: 2024-10-01 09:00:00
deleted_at: NULL
```

---

## RECORD 15 — Tin bị xóa mềm (deleted_at ≠ NULL)

> **Case:** softDelete · deleted_at có giá trị · ẩn khỏi mọi query thông thường

```
id: 15 | code: BDS-000015
slug: mat-bang-cho-thue-quan-3-bds-000015
catalogue_id: 13   | property_group: commercial
type_code: mat_bang
transaction_type: rent | is_project: 0

title:   Cho thuê mặt bằng tầng trệt - Quận 3 (đã xóa)
status: hidden | publish: 1
...

deleted_at: 2025-05-15 10:00:00
(Record này không hiển thị trong bất kỳ query nào — trừ withTrashed())
```

---

## TỔNG KẾT CÁC CASE ĐÃ BÀO PHỦ

| Record | property_group | transaction | is_project | price_unit   | Địa chỉ              | Đặc biệt                 |
| ------ | -------------- | ----------- | ---------- | ------------ | -------------------- | ------------------------ |
| 1      | apartment      | sale        | 0          | total        | HCM (không sáp nhập) | Full field, featured     |
| 2      | house          | sale        | 0          | total        | Huế mới (sáp nhập)   | Giá thỏa thuận           |
| 3      | land           | sale        | 0          | total        | Chỉ có địa chỉ mới   | Tin sau 01/07/2025       |
| 4      | commercial     | rent        | 0          | per_m2_month | HCM Q1               | price_vnd tự tính        |
| 5      | room           | rent        | 0          | per_month    | Bình Dương           | Tiện ích phòng trọ       |
| 6      | house          | rent        | 0          | per_month    | HCM Q2               | Biệt thự cao cấp         |
| 7      | land           | sale        | 0          | per_m2       | Lâm Đồng             | price_vnd = price × area |
| 8      | commercial     | rent        | 0          | per_month    | HCM Q1               | Văn phòng hạng A         |
| 9      | apartment      | sale        | 0          | total        | HCM Bình Chánh       | NOXH, đang chờ sổ        |
| 10     | apartment      | sale        | 0          | total        | Đà Nẵng              | Condotel, pháp lý dự án  |
| 11     | project        | sale        | 1          | total        | HCM Q9/Thủ Đức       | Dự án căn hộ + items     |
| 12     | project        | sale        | 1          | total        | Long An              | Dự án đất nền + items    |
| 13     | apartment      | rent        | 0          | per_month    | HCM Q4               | Draft, chưa đăng         |
| 14     | house          | sale        | 0          | total        | HCM Gò Vấp           | Đã bán, vẫn hiển thị     |
| 15     | commercial     | rent        | 0          | per_month    | HCM Q3               | Soft deleted             |
