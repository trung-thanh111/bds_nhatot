# 🏗️ SYSTEM DESIGN — NỀN TẢNG BĐS CÁ NHÂN (DYNAMIC + MULTI-DEPLOY)

> **Version:** 2.0 — Optimized Brief  
> **Model:** 1 Môi giới đăng · Khách chỉ xem · Dynamic theo loại BĐS · Multi-deployment  
> **Cập nhật:** Bổ sung đầy đủ field động, cấu trúc deploy nhiều dự án cùng codebase

---

## MỤC LỤC

1. [Tổng quan kiến trúc & triết lý thiết kế](#1-tổng-quan-kiến-trúc--triết-lý-thiết-kế)
2. [Kiến trúc Multi-Deployment](#2-kiến-trúc-multi-deployment)
3. [Danh sách Pages chi tiết](#3-danh-sách-pages-chi-tiết)
4. [Modules — Phân tích đầy đủ & các tình huống xảy ra](#4-modules--phân-tích-đầy-đủ--các-tình-huống-xảy-ra)
5. [Nhóm loại BĐS & Field động theo từng nhóm](#5-nhóm-loại-bđs--field-động-theo-từng-nhóm)
6. [Database Schema — Thiết kế Dynamic đầy đủ](#6-database-schema--thiết-kế-dynamic-đầy-đủ)
7. [Quan hệ bảng (ERD)](#7-quan-hệ-bảng-erd)
8. [Logic Dynamic Field hoạt động như thế nào](#8-logic-dynamic-field-hoạt-động-như-thế-nào)
9. [Admin Panel — Cấu trúc chuẩn hóa](#9-admin-panel--cấu-trúc-chuẩn-hóa)
10. [Multi-Deployment — Cách triển khai nhiều dự án](#10-multi-deployment--cách-triển-khai-nhiều-dự-án)
11. [Tech Stack & Infrastructure](#11-tech-stack--infrastructure)
12. [Checklist triển khai](#12-checklist-triển-khai)

---

## 1. TỔNG QUAN KIẾN TRÚC & TRIẾT LÝ THIẾT KẾ

### 1.1 Nguyên tắc cốt lõi

```
┌─────────────────────────────────────────────────────────────┐
│  TRIẾT LÝ: "1 Codebase — N Deployments — Fully Dynamic"    │
├─────────────────────────────────────────────────────────────┤
│                                                             │
│  ✅ 1 môi giới (admin) toàn quyền quản lý nội dung         │
│  ✅ Khách chỉ XEM — không đăng ký, không đăng nhập         │
│  ✅ Dynamic fields — mỗi loại BĐS có form/field riêng      │
│  ✅ Multi-deploy — cùng code, khác brand/layout/data       │
│  ✅ Admin UI giống nhau 100% cho mọi deployment            │
│                                                             │
│  ❌ Không cần: user registration, chat, ví tiền, VIP       │
└─────────────────────────────────────────────────────────────┘
```

### 1.2 Các loại "Dự án / Deployment" có thể triển khai

| Deployment | Loại BĐS chủ yếu             | Layout                      | Brand               |
| ---------- | ---------------------------- | --------------------------- | ------------------- |
| Site A     | Mua bán nhà phố, căn hộ      | Layout hiện đại             | "Nhà Tốt HCM"       |
| Site B     | Cho thuê mặt bằng thương mại | Layout doanh nghiệp         | "Mặt Bằng Pro"      |
| Site C     | Nhà ở xã hội                 | Layout đơn giản, thân thiện | "Nhà XH Bình Dương" |
| Site D     | Dự án BĐS lớn (multi-unit)   | Layout brochure             | "Dự Án Vinhomes X"  |
| Site E     | Mix tất cả                   | Layout tổng hợp             | "Bất Động Sản ABC"  |

---

## 2. KIẾN TRÚC MULTI-DEPLOYMENT

```
┌──────────────────────────────────────────────────────────────────┐
│                    GIT REPOSITORY (1 codebase)                   │
│         Next.js App + Admin Panel + API Routes                   │
└────────┬─────────────────┬──────────────────┬────────────────────┘
         │                 │                  │
         ▼                 ▼                  ▼
┌─────────────┐   ┌─────────────┐   ┌─────────────────┐
│  Deploy A   │   │  Deploy B   │   │   Deploy C...N  │
│  site-a.com │   │  site-b.com │   │   site-x.com    │
│  DB riêng   │   │  DB riêng   │   │   DB riêng      │
│  .env riêng │   │  .env riêng │   │   .env riêng    │
└─────────────┘   └─────────────┘   └─────────────────┘

Mỗi deployment:
  - Database PostgreSQL độc lập (isolate data hoàn toàn)
  - File .env riêng (brand, colors, contact, features)
  - Có thể override layout qua theme config
  - Admin panel URL: /admin (giống nhau 100%)
```

### 2.1 Cấu hình theo `.env` hoặc `site_settings` DB

```env
# .env của mỗi deployment
SITE_NAME="Nhà Tốt HCM"
SITE_DESCRIPTION="Mua bán cho thuê bất động sản HCM"
SITE_THEME="modern"              # modern | corporate | minimal | luxury
PRIMARY_COLOR="#E84040"
CONTACT_PHONE="0901234567"
CONTACT_EMAIL="admin@nhatothcm.com"
ZALO_URL="https://zalo.me/..."
FACEBOOK_URL="https://facebook.com/..."
GOOGLE_MAPS_KEY="..."
GA_ID="G-XXXXXXXX"

# Feature flags — bật/tắt tính năng theo deployment
FEATURE_MAP=true
FEATURE_VIRTUAL_TOUR=false
FEATURE_MORTGAGE_CALC=true
FEATURE_COMPARE=true
FEATURE_BLOG=false
FEATURE_PROJECT_MODULE=true
FEATURE_SOCIAL_HOUSING=true

# Loại BĐS được phép đăng (filter danh mục)
ENABLED_CATEGORIES="can-ho,nha-o,dat-nen,mat-bang"
```

---

## 3. DANH SÁCH PAGES CHI TIẾT

### 3.1 Public Pages (Khách xem — 0 yêu cầu đăng nhập)

| STT | Tên trang              | URL pattern                            | Mô tả chi tiết                                                                                                                     |
| --- | ---------------------- | -------------------------------------- | ---------------------------------------------------------------------------------------------------------------------------------- |
| 1   | **Trang chủ**          | `/`                                    | Hero banner, danh mục nổi bật, tin mới nhất, tin nổi bật, bộ lọc nhanh, dự án tiêu biểu, thống kê (số tin, dự án), bản đồ overview |
| 2   | **Danh sách tin**      | `/listings`                            | Grid/List view, bộ lọc đầy đủ sidebar, sort, phân trang                                                                            |
| 3   | **Lọc theo giao dịch** | `/mua-ban` `/cho-thue`                 | Breadcrumb, filter auto-set transaction_type                                                                                       |
| 4   | **Lọc theo danh mục**  | `/[category-slug]`                     | VD: `/can-ho-chung-cu`, tự động filter                                                                                             |
| 5   | **Lọc theo tỉnh**      | `/[province-slug]`                     | VD: `/ho-chi-minh`                                                                                                                 |
| 6   | **Lọc kết hợp**        | `/[trans]/[cat]/[province]/[district]` | VD: `/mua-ban/can-ho/ho-chi-minh/quan-1`                                                                                           |
| 7   | **Chi tiết tin**       | `/tin/[slug]`                          | Toàn bộ thông tin, gallery ảnh, video, map, dynamic fields, liên hệ, tin liên quan                                                 |
| 8   | **Danh sách dự án**    | `/du-an`                               | Grid dự án, filter theo loại/tỉnh/trạng thái                                                                                       |
| 9   | **Chi tiết dự án**     | `/du-an/[slug]`                        | Thông tin dự án, thư viện ảnh, căn hộ mẫu, tiến độ, bảng giá, tin đăng trong dự án                                                 |
| 10  | **Tìm kiếm**           | `/search?q=...`                        | Full-text search results, gợi ý                                                                                                    |
| 11  | **Bản đồ**             | `/ban-do`                              | Map view toàn bộ tin, cluster pins, filter trên map                                                                                |
| 12  | **Blog / Tin tức**     | `/blog`                                | Danh sách bài viết (nếu bật feature)                                                                                               |
| 13  | **Chi tiết bài viết**  | `/blog/[slug]`                         | Nội dung bài viết, tin liên quan                                                                                                   |
| 14  | **Trang liên hệ**      | `/lien-he`                             | Form liên hệ, thông tin, map văn phòng                                                                                             |
| 15  | **Giới thiệu**         | `/gioi-thieu`                          | Thông tin môi giới, kinh nghiệm, chứng chỉ                                                                                         |
| 16  | **Bảng giá dịch vụ**   | `/dich-vu`                             | Giới thiệu dịch vụ môi giới (nếu cần)                                                                                              |
| 17  | **Sitemap HTML**       | `/sitemap`                             | Danh sách tất cả trang                                                                                                             |
| 18  | **404**                | `/404`                                 | Trang lỗi thân thiện                                                                                                               |
| 19  | **Tính toán vay**      | `/tinh-toan-vay`                       | Máy tính lãi suất ngân hàng (nếu bật)                                                                                              |
| 20  | **So sánh**            | `/so-sanh?ids=1,2,3`                   | So sánh 2–3 tin cùng loại (nếu bật)                                                                                                |

### 3.2 Admin Pages (Chỉ môi giới — cần đăng nhập)

| STT | Tên trang                     | URL                             | Mô tả                                                  |
| --- | ----------------------------- | ------------------------------- | ------------------------------------------------------ |
| 1   | **Đăng nhập**                 | `/admin/login`                  | Form đăng nhập                                         |
| 2   | **Dashboard**                 | `/admin`                        | KPI: tổng tin, lượt xem, liên hệ, tin hết hạn, biểu đồ |
| 3   | **DS tin đăng**               | `/admin/listings`               | Bảng quản lý, filter, sort, bulk action                |
| 4   | **Thêm tin**                  | `/admin/listings/new`           | Form động theo loại BĐS chọn                           |
| 5   | **Sửa tin**                   | `/admin/listings/[id]/edit`     | Form động, load field theo category                    |
| 6   | **Xem trước tin**             | `/admin/listings/[id]/preview`  | Preview trước khi publish                              |
| 7   | **DS dự án**                  | `/admin/projects`               | Quản lý dự án lớn                                      |
| 8   | **Thêm/Sửa dự án**            | `/admin/projects/[id]`          | Form dự án đầy đủ                                      |
| 9   | **Quản lý ảnh**               | `/admin/media`                  | Thư viện ảnh, upload batch                             |
| 10  | **Danh mục**                  | `/admin/categories`             | Cây danh mục, field definitions                        |
| 11  | **Field Builder**             | `/admin/categories/[id]/fields` | Cấu hình field động cho từng danh mục                  |
| 12  | **Địa lý**                    | `/admin/locations`              | Tỉnh/Huyện/Xã (import hoặc thêm tay)                   |
| 13  | **Tin liên hệ**               | `/admin/contacts`               | Inbox từ khách, đánh dấu đã xử lý                      |
| 14  | **Blog**                      | `/admin/blog`                   | Quản lý bài viết                                       |
| 15  | **Thêm/Sửa blog**             | `/admin/blog/[id]`              | Editor bài viết (rich text)                            |
| 16  | **Thống kê**                  | `/admin/analytics`              | Lượt xem theo tin, theo ngày, liên hệ                  |
| 17  | **Cài đặt chung**             | `/admin/settings`               | Thông tin site, liên hệ, SEO mặc định                  |
| 18  | **Cài đặt giao diện**         | `/admin/settings/theme`         | Theme, màu sắc, logo, favicon                          |
| 19  | **Cài đặt bật/tắt tính năng** | `/admin/settings/features`      | Feature flags                                          |
| 20  | **Cài đặt SEO**               | `/admin/settings/seo`           | Meta defaults, schema markup                           |
| 21  | **Đổi mật khẩu**              | `/admin/settings/security`      | Bảo mật tài khoản                                      |
| 22  | **Banner / Slide**            | `/admin/banners`                | Quản lý slider trang chủ                               |
| 23  | **Testimonials**              | `/admin/testimonials`           | Đánh giá khách hàng                                    |

---

## 4. MODULES — PHÂN TÍCH ĐẦY ĐỦ & CÁC TÌNH HUỐNG XẢY RA

---

### MODULE 1: Listing Management (Quản lý tin đăng — Trung tâm hệ thống)

**Chức năng chính:**

- CRUD đầy đủ
- Form đăng tin **thay đổi động theo danh mục** được chọn
- Upload ảnh (kéo thả, sort, đặt ảnh bìa)
- Trạng thái: draft → active → expired / sold / rented
- Slug SEO tự động sinh
- Clone tin (copy nhanh)

**Các tình huống có thể xảy ra:**

```
✅ Đăng tin mới → chọn danh mục → form hiện đúng field động
✅ Lưu nháp publish = 1 → quay lại sửa tiếp → publish = 2
✏️  Sửa tin đang active → vẫn hiển thị, cập nhật tức thì
📋  Bulk actions: xóa nhiều, đổi trạng thái nhiều tin
🔍  Tìm tin trong admin → search theo tiêu đề, mã tin, địa chỉ
📊  Xem stats từng tin: lượt xem, click SĐT, liên hệ
🏷️  Đánh dấu nổi bật (featured) → hiện ở trang chủ
📌  Pin tin lên đầu danh sách (sort_order)
🏠  Đánh dấu đã bán/đã thuê → chuyển status, vẫn hiển thị (hoặc ẩn — config)
```

---

### MODULE 2: Search & Filter (Tìm kiếm — Không cần Elasticsearch)

**Chức năng chính:**

- Full-text search (Mysql `tsvector` + `tsquery`)
- Bộ lọc cơ bản: danh mục (mua bán, cho thuê,.. và còn loại dự án nhà ở, đất hay dự án... nhé ), tỉnh/huyện (trước và sau sáp nhập tại việt nam ), giá (từ - đến), diện tích (từ - đến), loại GD (căn hộ, nhà phố, đất nền, dự án), hướng nhà, hướng ban công (fix cứng lọc các hướng vd: Đông, Tây, Nam, Bắc, Đông Nam, Đông Bắc, Tây Nam, Tây Bắc), pháp lý (sổ hồng, sổ đỏ, chung cư, đất nền, dự án,.. ), nội thất nữa nhé (đồ cơ bản, full nội thất, không nội thất -> tùy theo dự án nhé)

**Các tình huống có thể xảy ra:**

```
✅ Tìm "căn hộ quận 1" → full-text match title + description + address
✅ Filter nhiều điều kiện → AND logic, chuỗi query params trên URL
✅ URL filter → chia sẻ link giữ nguyên bộ lọc
⚠️  Không có kết quả → hiện "0 tin phù hợp" + gợi ý mở rộng filter
⚠️  Từ tìm kiếm không dấu → normalize về có dấu để match (unaccented search)
🔄  Thay đổi filter → URL cập nhật, không reload page (SPA behavior)
📱  Mobile → bộ lọc thu gọn vào modal
🗺️  Tìm kiếm trên map → vẽ bán kính → filter theo PostGIS
📊  Sort: mới nhất / giá tăng / giá giảm / diện tích / featured-first
⚡  Cache kết quả tìm kiếm phổ biến → Redis (nếu cần)
```

---

### MODULE 6: Project Module (Dự án BĐS lớn)

**Chức năng chính:**

- Quản lý dự án độc lập (có thể không có tin đơn lẻ)
- Hoặc tập hợp nhiều tin đơn lẻ trong 1 dự án
- Bảng giá theo loại căn (có thể import Excel)
- Tiến độ xây dựng
- Thông tin pháp lý dự án

**Các tình huống có thể xảy ra:**

```
✅ Tạo dự án → gán nhiều tin đăng vào dự án
✅ Xem trang dự án → hiện thư viện ảnh + bảng giá + tin trong dự án
⚠️  Dự án chưa có tin nào → vẫn hiển thị trang dự án
🔄  Cập nhật tiến độ dự án → hiện timeline
📊  Bảng giá căn hộ: import từ Excel / nhập tay theo loại căn
🗑️  Xóa dự án → tin đăng liên quan vẫn còn (project_id = NULL)
🏗️  Feature flag: tắt module này nếu deployment không dùng
```

---

### MODULE 7: Contact / Lead Capture (Liên hệ từ khách)

**Chức năng chính:**

- Nút xem SĐT (click → reveal, đếm lượt)
- Form liên hệ nhanh (tên, SĐT, tin nhắn)
- Tích hợp Zalo / Messenger / Viber click-to-chat
- Email thông báo đến admin khi có liên hệ mới
- Inbox quản lý liên hệ trong admin

**Các tình huống có thể xảy ra:**

```
✅ Click "Xem SĐT" → hiện SĐT + đếm phone_click_count
✅ Form liên hệ submit → lưu DB + gửi email thông báo admin
✅ Click Zalo → mở Zalo app (mobile) hoặc Zalo web
⚠️  Form submit spam → Google reCAPTCHA v3 (invisible)
⚠️  SĐT không hợp lệ → validate regex VN phone
⚠️  Email gửi thất bại → retry queue (3 lần)
⚠️  Nhiều submit cùng IP trong 1 phút → rate limit, hỏi captcha
📧  Email thông báo bao gồm: tên khách, SĐT, tin nhắn, link tin đăng
📬  Admin đánh dấu liên hệ: new → read → replied → closed
📊  Thống kê: bao nhiêu liên hệ / tin / ngày
🔔  Tùy chọn: gửi SMS thông báo đến SĐT admin (Twilio/ESMS)
```

---

### MODULE 8: SEO & Meta (Tối ưu tìm kiếm)

**Chức năng chính:**

- Tự động sinh meta title / description từ data tin
- Open Graph tags (chia sẻ Facebook/Zalo)
- Twitter Card
- JSON-LD Schema Markup (RealEstate, BreadcrumbList)
- Sitemap.xml tự động cập nhật
- Robots.txt có thể config
- Canonical URL
- Hreflang (nếu đa ngôn ngữ)

**Các tình huống có thể xảy ra:**

```
✅ Đăng tin mới → tự động có meta tags đầy đủ
✅ Chia sẻ link lên Facebook → OG image là ảnh bìa tin
✅ Google index → sitemap submit tự động (Google Search Console)
⚠️  Meta description dài > 160 ký tự → tự cắt bớt
⚠️  Tiêu đề trùng nhau → thêm suffix "(Quận X, HCM)"
🔄  Tin bị xóa → trả về 410 Gone (không 404) để SEO
🔄  Slug thay đổi → 301 redirect từ slug cũ
📊  Structured data RealEstate: giá, diện tích, địa chỉ, ảnh
```

---

### MODULE 9: Analytics & Thống kê

**Chức năng chính:**

- Đếm lượt xem mỗi tin (dedup theo IP + ngày)
- Đếm click xem SĐT
- Đếm số liên hệ form
- Dashboard admin: biểu đồ 7 ngày / 30 ngày
- Google Analytics tích hợp (GA4)
- Xuất báo cáo CSV

**Các tình huống có thể xảy ra:**

```
✅ Khách vào xem tin → ghi view (dedup: cùng IP + cùng ngày + cùng tin = 1 view)
✅ Bot Google crawl → detect User-Agent, không đếm view
⚠️  Admin xem tin của mình → không đếm view (detect admin session)
📊  Dashboard hiện: top 10 tin được xem nhiều nhất
📊  Dashboard hiện: số liên hệ theo ngày (chart)
📊  Dashboard hiện: tỷ lệ click SĐT / lượt xem
📤  Export danh sách liên hệ theo khoảng ngày (CSV)
```

---

### MODULE 10: Blog / Nội dung (Tùy chọn — Feature Flag)

**Chức năng chính:**

- Viết bài về thị trường BĐS
- Rich text editor (TipTap / Quill)
- Phân loại bài viết (tag)
- SEO từng bài

**Các tình huống xảy ra:**

```
✅ Tạo bài viết → draft → publish với scheduled date
⚠️  Bài viết chứa ảnh ngoài → tự fetch & host lại (tránh hotlink)
🔄  Sửa bài published → cập nhật tức thì
🗑️  Xóa bài → soft delete, URL trả 410
```

---

### MODULE 11: Mortgage Calculator (Tính lãi vay — Tùy chọn)

**Chức năng chính:**

- Tính lãi vay mua nhà (giảm dần / đều)
- Nhúng vào trang chi tiết tin
- Trang riêng `/tinh-toan-vay`

---

### MODULE 12: Compare (So sánh tin — Tùy chọn)

**Chức năng chính:**

- So sánh 2–3 tin cùng loại
- Lưu danh sách so sánh trong localStorage
- Bảng so sánh field theo field

---

### MODULE 13: Notification / Reminder (Nhắc nhở tự động)

**Các tình huống:**

```
📧 Tin sắp hết hạn (7 ngày trước) → email nhắc admin
📧 Tin đã hết hạn → email nhắc gia hạn
📧 Có liên hệ mới từ khách → email realtime đến admin
📧 Báo cáo tuần → tóm tắt lượt xem, liên hệ 7 ngày qua
🔁 Cron job chạy mỗi đêm: kiểm tra tin hết hạn → update status
```

---

### MODULE 14: Import / Export Data

**Chức năng chính:**

- Import tin hàng loạt từ Excel/CSV
- Export danh sách tin (CSV/Excel)
- Export danh sách liên hệ
- Backup database (scheduled)

**Tình huống:**

```
📥  Import Excel: validate từng dòng, báo lỗi dòng nào sai format
⚠️  Import tin trùng (same title + address) → cảnh báo, cho phép skip/overwrite
📤  Export tin → bao gồm URL ảnh, stats
```

---

## 5. NHÓM LOẠI BĐS & FIELD ĐỘNG THEO TỪNG NHÓM

### 5.1 Sơ đồ phân nhóm

```
GIAO DỊCH (transaction_type)
├── MUA BÁN (sale)
└── CHO THUÊ (rent)

NHÓM LOẠI BĐS (property_group) — quyết định SET FIELD hiển thị
├── GROUP_A: Căn hộ / Chung cư / Nhà ở xã hội
├── GROUP_B: Nhà phố / Biệt thự / Nhà riêng
├── GROUP_C: Đất (các loại)
├── GROUP_D: Mặt bằng / Văn phòng / Kho xưởng
├── GROUP_E: Phòng trọ / Nhà trọ
└── GROUP_F: Dự án (quản lý riêng qua Project module)
```

---

### 5.2 Field đầy đủ theo từng nhóm

#### GROUP A — Căn hộ / Chung cư / Nhà ở xã hội

| Field                       | Label                    | Type                               | Bắt buộc | Có thể filter |
| --------------------------- | ------------------------ | ---------------------------------- | -------- | ------------- |
| `area`                      | Diện tích (m²)           | number                             | ✅       | ✅            |
| `price`                     | Giá                      | number                             | ✅       | ✅            |
| `bedrooms`                  | Số phòng ngủ             | select (0,1,2,3,4,5+)              | ✅       | ✅            |
| `bathrooms`                 | Số phòng tắm             | select (1,2,3,4+)                  | ✅       | ✅            |
| `floor_number`              | Căn hộ ở tầng            | number                             |          |               |
| `total_floors`              | Tổng số tầng tòa nhà     | number                             |          |               |
| `direction`                 | Hướng ban công/cửa chính | select                             |          | ✅            |
| `balcony_direction`         | Hướng ban công           | select                             |          |               |
| `furniture`                 | Nội thất                 | select (none/basic/full)           |          | ✅            |
| `legal_status`              | Pháp lý                  | select                             |          | ✅            |
| `project_name`              | Tên dự án / chung cư     | text                               |          |               |
| `block_name`                | Tên tòa / block          | text                               |          |               |
| `apartment_code`            | Mã căn                   | text                               |          |               |
| `area_use`                  | DT sử dụng (m²)          | number                             |          |               |
| `area_balcony`              | DT ban công (m²)         | number                             |          |               |
| `has_elevator`              | Thang máy                | boolean                            |          | ✅            |
| `has_pool`                  | Hồ bơi                   | boolean                            |          | ✅            |
| `has_parking`               | Bãi đỗ xe                | boolean                            |          | ✅            |
| `has_gym`                   | Phòng gym                | boolean                            |          |               |
| `has_security`              | Bảo vệ 24/7              | boolean                            |          |               |
| `has_supermarket`           | Siêu thị nội khu         | boolean                            |          |               |
| `view_type`                 | View nhìn ra             | select (city/river/park/sea/other) |          |               |
| `year_built`                | Năm xây dựng             | number                             |          |               |
| `handover_date`             | Ngày bàn giao            | date                               |          |               |
| `management_fee`            | Phí quản lý (nghìn/m²)   | number                             |          |               |
| `is_social_housing`         | Nhà ở xã hội             | boolean                            |          | ✅            |
| `social_housing_conditions` | Điều kiện mua NOX        | textarea                           |          |               |
| `virtual_tour_url`          | Link Virtual Tour 360°   | url                                |          |               |

---

#### GROUP B — Nhà phố / Biệt thự / Nhà riêng

| Field              | Label                  | Type                                    | Bắt buộc | Có thể filter |
| ------------------ | ---------------------- | --------------------------------------- | -------- | ------------- |
| `area`             | Diện tích đất (m²)     | number                                  | ✅       | ✅            |
| `area_use`         | Diện tích sử dụng (m²) | number                                  |          |               |
| `price`            | Giá                    | number                                  | ✅       | ✅            |
| `length`           | Chiều dài (m)          | number                                  |          | ✅            |
| `width`            | Chiều rộng (m)         | number                                  |          | ✅            |
| `floors`           | Số tầng                | select (1–10+)                          | ✅       | ✅            |
| `bedrooms`         | Số phòng ngủ           | select                                  | ✅       | ✅            |
| `bathrooms`        | Số phòng tắm           | select                                  |          |               |
| `direction`        | Hướng nhà              | select                                  |          | ✅            |
| `legal_status`     | Pháp lý                | select                                  | ✅       | ✅            |
| `furniture`        | Nội thất               | select                                  |          | ✅            |
| `road_width`       | Đường trước nhà (m)    | number                                  |          | ✅            |
| `house_type`       | Loại nhà               | select (mat-tien/hem/biet-thu/nha-vuon) | ✅       | ✅            |
| `alley_width`      | Chiều rộng hẻm (m)     | number                                  |          |               |
| `has_rooftop`      | Sân thượng             | boolean                                 |          |               |
| `has_basement`     | Tầng hầm               | boolean                                 |          |               |
| `has_parking`      | Chỗ đậu xe             | boolean                                 |          | ✅            |
| `is_corner_lot`    | Đất góc (2 mặt tiền)   | boolean                                 |          |               |
| `near_school`      | Gần trường học         | boolean                                 |          |               |
| `near_hospital`    | Gần bệnh viện          | boolean                                 |          |               |
| `near_market`      | Gần chợ/siêu thị       | boolean                                 |          |               |
| `year_built`       | Năm xây                | number                                  |          |               |
| `renovation_year`  | Năm sửa chữa gần nhất  | number                                  |          |               |
| `has_mezzanine`    | Lửng                   | boolean                                 |          |               |
| `electricity_type` | Điện                   | select (kinh-doanh/sinh-hoat)           |          |               |

---

#### GROUP C — Đất (các loại)

| Field            | Label                  | Type                                                          | Bắt buộc | Có thể filter |
| ---------------- | ---------------------- | ------------------------------------------------------------- | -------- | ------------- |
| `area`           | Diện tích (m²)         | number                                                        | ✅       | ✅            |
| `price`          | Giá                    | number                                                        | ✅       | ✅            |
| `price_per_m2`   | Giá/m² (tự tính)       | number                                                        |          |               |
| `length`         | Chiều dài (m)          | number                                                        |          |               |
| `width`          | Mặt tiền (m)           | number                                                        |          | ✅            |
| `legal_status`   | Pháp lý                | select                                                        | ✅       | ✅            |
| `land_type`      | Loại đất               | select (tho-cu/tho-nong/thuong-mai/cong-nghiep/dat-nen-du-an) | ✅       | ✅            |
| `direction`      | Hướng                  | select                                                        |          |               |
| `road_width`     | Đường trước (m)        | number                                                        |          | ✅            |
| `is_corner`      | Đất góc                | boolean                                                       |          |               |
| `is_cleared`     | Đã giải phóng mặt bằng | boolean                                                       |          |               |
| `infrastructure` | Hạ tầng                | multiselect (dien/nuoc/duong/canh)                            |          |               |
| `zoning`         | Quy hoạch              | select (dan-cu/thuong-mai/cong-nghiep/chua-ro)                |          | ✅            |
| `quy_hoach_doc`  | Link file QH 1/500     | url                                                           |          |               |
| `current_use`    | Hiện trạng             | select (dat-trong/co-nha-cu/co-cay)                           |          |               |
| `region_type`    | Khu vực                | select (noi-thanh/ngoai-thanh/khu-cong-nghiep/resort)         |          | ✅            |

---

#### GROUP D — Mặt bằng / Văn phòng / Kho xưởng

| Field                    | Label                        | Type                                               | Bắt buộc | Có thể filter |
| ------------------------ | ---------------------------- | -------------------------------------------------- | -------- | ------------- |
| `area`                   | Diện tích (m²)               | number                                             | ✅       | ✅            |
| `price`                  | Giá thuê/tháng               | number                                             | ✅       | ✅            |
| `price_unit`             | Đơn vị giá                   | select (month/m2_month/total)                      | ✅       |               |
| `floors`                 | Số tầng                      | number                                             |          |               |
| `floor_number`           | Tầng mặt bằng                | number                                             |          | ✅            |
| `length`                 | Chiều dài (m)                | number                                             |          |               |
| `width`                  | Mặt tiền (m)                 | number                                             | ✅       | ✅            |
| `height_clear`           | Chiều cao thông thủy (m)     | number                                             |          |               |
| `road_width`             | Đường trước (m)              | number                                             |          | ✅            |
| `space_type`             | Loại mặt bằng                | select (shop/office/warehouse/showroom/restaurant) | ✅       | ✅            |
| `has_elevator`           | Thang máy                    | boolean                                            |          | ✅            |
| `has_loading_dock`       | Cửa hàng hàng / Loading dock | boolean                                            |          |               |
| `has_mezzanine`          | Lửng / Gác                   | boolean                                            |          |               |
| `has_toilet`             | WC riêng                     | boolean                                            |          |               |
| `electricity_capacity`   | Công suất điện (KVA)         | number                                             |          |               |
| `air_conditioning`       | Điều hòa                     | select (none/window/central)                       |          |               |
| `parking_slots`          | Số chỗ đậu xe                | number                                             |          |               |
| `security_deposit`       | Tiền đặt cọc (tháng)         | number                                             |          |               |
| `min_lease_term`         | Thời gian thuê tối thiểu     | select (1m/3m/6m/1y/2y+)                           |          |               |
| `allowed_business`       | Ngành nghề cho phép          | textarea                                           |          |               |
| `business_license_ready` | Sẵn đăng kinh doanh          | boolean                                            |          |               |
| `current_tenant`         | Đang có thuê                 | boolean                                            |          |               |
| `lease_remaining`        | Còn lại (tháng)              | number                                             |          |               |

---

#### GROUP E — Phòng trọ / Nhà trọ

| Field                   | Label                    | Type                     | Bắt buộc | Có thể filter |
| ----------------------- | ------------------------ | ------------------------ | -------- | ------------- |
| `area`                  | Diện tích (m²)           | number                   | ✅       | ✅            |
| `price`                 | Giá thuê/tháng           | number                   | ✅       | ✅            |
| `furniture`             | Nội thất                 | select                   |          | ✅            |
| `has_private_bathroom`  | WC riêng                 | boolean                  |          | ✅            |
| `has_window`            | Cửa sổ thoáng            | boolean                  |          |               |
| `has_balcony`           | Ban công                 | boolean                  |          |               |
| `has_loft`              | Gác lửng                 | boolean                  |          |               |
| `has_air_conditioning`  | Điều hòa                 | boolean                  |          | ✅            |
| `has_water_heater`      | Máy nước nóng            | boolean                  |          |               |
| `has_fridge`            | Tủ lạnh                  | boolean                  |          |               |
| `has_washing_machine`   | Máy giặt                 | boolean                  |          |               |
| `has_parking_bike`      | Gửi xe máy               | boolean                  |          | ✅            |
| `has_parking_car`       | Gửi ôtô                  | boolean                  |          |               |
| `has_security`          | Bảo vệ                   | boolean                  |          |               |
| `has_camera`            | Camera                   | boolean                  |          |               |
| `electricity_price`     | Giá điện (nghìn/kWh)     | number                   |          |               |
| `water_price`           | Giá nước (nghìn/m³)      | number                   |          |               |
| `internet_included`     | Có wifi                  | boolean                  |          | ✅            |
| `max_people`            | Số người ở tối đa        | select (1/2/3/4+)        |          |               |
| `gender_restriction`    | Giới tính                | select (any/male/female) |          |               |
| `pet_allowed`           | Cho nuôi thú cưng        | boolean                  |          |               |
| `deposit_months`        | Đặt cọc (tháng)          | number                   |          |               |
| `notice_period`         | Báo trước khi dọn (ngày) | number                   |          |               |
| `floor_number`          | Ở tầng                   | number                   |          |               |
| `building_total_floors` | Tổng số tầng             | number                   |          |               |
| `available_from`        | Trống từ ngày            | date                     |          |               |

---

## 6. DATABASE SCHEMA — THIẾT KẾ DYNAMIC ĐẦY ĐỦ

### 6.1 Bảng `admin_users` — Tài khoản quản trị

```sql
CREATE TABLE admin_users (
    id              SERIAL PRIMARY KEY,
    email           VARCHAR(255) UNIQUE NOT NULL,
    password_hash   VARCHAR(255) NOT NULL,
    full_name       VARCHAR(255) NOT NULL,

    -- Thông tin công khai hiển thị trên website
    display_name    VARCHAR(255),          -- Tên hiển thị ("Anh Minh BĐS")
    avatar_url      VARCHAR(500),
    bio             TEXT,                  -- Giới thiệu bản thân
    experience_years INT,                 -- Năm kinh nghiệm
    certifications  JSONB,                 -- ["Chứng chỉ môi giới 2023", "..."]

    -- Kênh liên hệ công khai
    public_phone        VARCHAR(20),
    public_phone_2      VARCHAR(20),
    public_email        VARCHAR(255),
    zalo_url            VARCHAR(500),
    facebook_url        VARCHAR(500),
    youtube_url         VARCHAR(500),
    tiktok_url          VARCHAR(500),
    website_url         VARCHAR(500),

    -- Địa chỉ văn phòng
    office_address      VARCHAR(500),
    office_maps_url     VARCHAR(500),  -- Link Google Maps

    -- Bảo mật
    failed_login_count  INT DEFAULT 0,
    locked_until        TIMESTAMP,
    last_login_at       TIMESTAMP,
    last_login_ip       VARCHAR(45),

    -- Cài đặt thông báo
    notify_contact_email    BOOLEAN DEFAULT TRUE,
    notify_contact_sms      BOOLEAN DEFAULT FALSE,
    notify_expiry_email     BOOLEAN DEFAULT TRUE,
    notify_weekly_report    BOOLEAN DEFAULT TRUE,

    created_at      TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at      TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);
```

---

### 6.2 Bảng `admin_sessions` — Session đăng nhập

```sql
CREATE TABLE admin_sessions (
    id              BIGSERIAL PRIMARY KEY,
    refresh_token_hash  VARCHAR(255) UNIQUE NOT NULL,
    ip_address      VARCHAR(45),
    user_agent      VARCHAR(500),
    device_name     VARCHAR(255),    -- "Chrome on MacOS"
    is_revoked      BOOLEAN DEFAULT FALSE,
    expires_at      TIMESTAMP NOT NULL,
    created_at      TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);
```

---

### 6.3 Bảng `categories` — Danh mục (Cây 2 cấp + nhóm field)

```sql
CREATE TABLE categories (
    id              SERIAL PRIMARY KEY,
    parent_id       INT REFERENCES categories(id) ON DELETE SET NULL,

    name            VARCHAR(255) NOT NULL,
    slug            VARCHAR(255) UNIQUE NOT NULL,
    name_en         VARCHAR(255),          -- Tùy chọn cho SEO

    -- Nhóm field động — quyết định form đăng tin dùng field nào
    property_group  ENUM(
                        'apartment',       -- GROUP_A: Căn hộ, chung cư, NOXH
                        'house',           -- GROUP_B: Nhà phố, biệt thự
                        'land',            -- GROUP_C: Đất các loại
                        'commercial',      -- GROUP_D: Mặt bằng, văn phòng, kho
                        'room',            -- GROUP_E: Phòng trọ, nhà trọ
                        'project'          -- GROUP_F: Dự án (dùng Project module)
                    ),

    -- Loại giao dịch mặc định
    default_transaction ENUM('sale','rent','both') DEFAULT 'both',

    -- Hiển thị
    icon_emoji      VARCHAR(10),           -- "🏠"
    icon_url        VARCHAR(500),
    color_hex       VARCHAR(7),            -- "#E84040"
    description     TEXT,

    -- SEO
    meta_title      VARCHAR(255),
    meta_description VARCHAR(500),
    h1_text         VARCHAR(255),          -- Heading trang danh mục

    -- Cài đặt
    show_on_homepage    BOOLEAN DEFAULT TRUE,
    is_active           BOOLEAN DEFAULT TRUE,
    sort_order          INT DEFAULT 0,

    -- Cấu trúc
    level           TINYINT DEFAULT 1,
    path            VARCHAR(200),          -- "1/5" breadcrumb

    created_at      TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at      TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);
```

---

### 6.4 Bảng `field_definitions` — Định nghĩa field động

```sql
-- Định nghĩa các field cho từng property_group
-- Admin có thể thêm/sửa/xóa field qua Field Builder UI
CREATE TABLE field_definitions (
    id              SERIAL PRIMARY KEY,

    -- Field áp dụng cho nhóm nào
    property_group  ENUM('apartment','house','land','commercial','room','project') NOT NULL,

    -- Định danh
    field_key       VARCHAR(100) NOT NULL,     -- "bedrooms", "road_width" (camelCase/snake_case)
    field_label     VARCHAR(255) NOT NULL,     -- "Số phòng ngủ"
    field_label_en  VARCHAR(255),              -- "Bedrooms"

    -- Loại field
    field_type      ENUM(
                        'text',
                        'textarea',
                        'number',
                        'select',
                        'multiselect',
                        'boolean',
                        'date',
                        'url',
                        'range'
                    ) NOT NULL,

    -- Tùy chọn cho select/multiselect
    options         JSONB,
    -- Ví dụ: [{"value":"1","label":"1 phòng"},{"value":"2","label":"2 phòng"},...]

    -- Validation
    is_required     BOOLEAN DEFAULT FALSE,     -- Bắt buộc khi publish
    min_value       DECIMAL(15,4),             -- Số tối thiểu (nếu type=number)
    max_value       DECIMAL(15,4),
    min_length      INT,
    max_length      INT,
    placeholder     VARCHAR(255),
    helper_text     VARCHAR(500),              -- Gợi ý dưới field

    -- Hành vi
    is_filterable   BOOLEAN DEFAULT FALSE,     -- Hiện trong bộ lọc tìm kiếm
    is_comparable   BOOLEAN DEFAULT FALSE,     -- Hiện trong trang so sánh
    is_public       BOOLEAN DEFAULT TRUE,      -- Hiện với khách xem không
    show_in_list    BOOLEAN DEFAULT FALSE,     -- Hiện trong card danh sách

    -- Filter config (nếu is_filterable=true)
    filter_type     ENUM('select','range','boolean','multiselect'),
    filter_label    VARCHAR(255),

    -- Icon cho field
    icon            VARCHAR(50),               -- "bed", "bath", "maximize-2" (lucide icon)
    unit            VARCHAR(30),               -- "m²", "tầng", "triệu/m²"

    -- Cài đặt
    sort_order      INT DEFAULT 0,
    section_name    VARCHAR(100),              -- Nhóm field trong form: "Diện tích", "Tiện ích"
    is_active       BOOLEAN DEFAULT TRUE,

    UNIQUE(property_group, field_key),
    created_at      TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);
```

---

### 6.5 Bảng `listings` — Tin đăng (Core — field tĩnh + JSONB động)

```sql
CREATE TABLE listings (
    id              BIGSERIAL PRIMARY KEY,
    listing_code    VARCHAR(20) UNIQUE NOT NULL,  -- "BDS-000123" — mã định danh ngắn
    slug            VARCHAR(600) UNIQUE NOT NULL,

    -- Phân loại
    category_id     INT NOT NULL REFERENCES categories(id),
    transaction_type ENUM('sale','rent') NOT NULL,

    -- ===== FIELD TĨNH (chung cho mọi loại) =====
    title           VARCHAR(500) NOT NULL,
    description     TEXT NOT NULL,

    -- Giá
    price           DECIMAL(18,2),
    price_negotiable BOOLEAN DEFAULT FALSE,
    price_unit      ENUM('total','per_m2','per_month','per_m2_month') DEFAULT 'total',
    currency        CHAR(3) DEFAULT 'VND',

    -- Diện tích cơ bản (chung)
    area            DECIMAL(10,2),         -- Diện tích chính (m²)

    -- Vị trí
    address         VARCHAR(500),
    province_id     INT NOT NULL REFERENCES provinces(id),
    district_id     INT NOT NULL REFERENCES districts(id),
    ward_id         INT REFERENCES wards(id),
    ward_name       VARCHAR(100),          -- Backup text nếu ward không có trong DB
    street          VARCHAR(255),
    full_address    VARCHAR(700),          -- Địa chỉ đầy đủ (tổng hợp)
    latitude        DECIMAL(10,8),
    longitude       DECIMAL(11,8),
    show_exact_address BOOLEAN DEFAULT FALSE,

    -- Pháp lý (common field)
    legal_status    ENUM('pink_book','red_book','sale_contract','waiting','other') DEFAULT 'other',

    -- Dự án (nếu thuộc dự án)
    project_id      BIGINT REFERENCES projects(id) ON DELETE SET NULL,

    -- ===== FIELD ĐỘNG THEO NHÓM (lưu JSONB) =====
    -- Không phải tất cả field đều có — mỗi nhóm dùng khác nhau
    -- Schema được định nghĩa trong field_definitions
    extra_fields    JSONB NOT NULL DEFAULT '{}',
    /*
      Ví dụ GROUP_A (apartment):
      {
        "bedrooms": 2,
        "bathrooms": 2,
        "floor_number": 12,
        "total_floors": 25,
        "direction": "east",
        "furniture": "full",
        "has_elevator": true,
        "has_pool": false,
        "has_parking": true,
        "project_name": "Vinhomes Central Park",
        "apartment_code": "P2-12.08",
        "virtual_tour_url": "https://..."
      }

      Ví dụ GROUP_C (land):
      {
        "length": 20,
        "width": 5,
        "land_type": "tho-cu",
        "road_width": 6,
        "zoning": "dan-cu",
        "is_corner": false,
        "infrastructure": ["dien","nuoc","duong"]
      }

      Ví dụ GROUP_D (commercial):
      {
        "width": 8,
        "height_clear": 4.5,
        "space_type": "shop",
        "has_elevator": true,
        "electricity_capacity": 50,
        "min_lease_term": "1y",
        "security_deposit": 3
      }
    */

    -- Media
    cover_image_url VARCHAR(500),          -- Ảnh bìa (cache cho hiệu suất)
    image_count     TINYINT DEFAULT 0,     -- Số lượng ảnh (cache)
    has_video       BOOLEAN DEFAULT FALSE,
    has_virtual_tour BOOLEAN DEFAULT FALSE,

    -- Trạng thái
    status          ENUM('draft','active','expired','sold','rented','hidden') DEFAULT 'draft',

    -- Thứ tự hiển thị
    is_featured     BOOLEAN DEFAULT FALSE, -- Tin nổi bật
    sort_order      INT DEFAULT 0,         -- Pin lên đầu (0=thường, >0=ưu tiên)

    -- SEO
    meta_title      VARCHAR(255),          -- Nếu NULL → tự gen từ title
    meta_description VARCHAR(500),         -- Nếu NULL → tự gen từ description

    -- Liên hệ (nếu muốn override thông tin admin)
    contact_name    VARCHAR(255),          -- Tên hiển thị trên tin (NULL = dùng admin.display_name)
    contact_phone   VARCHAR(20),           -- SĐT trên tin (NULL = dùng admin.public_phone)

    -- Thống kê (cache — cập nhật định kỳ)
    view_count      INT DEFAULT 0,
    phone_click_count INT DEFAULT 0,
    contact_count   INT DEFAULT 0,

    -- Thời gian
    published_at    TIMESTAMP,
    expired_at      TIMESTAMP,             -- = published_at + settings.listing_duration ngày
    sold_at         TIMESTAMP,

    -- Ghi chú nội bộ
    internal_note   TEXT,                  -- Chỉ admin thấy, không hiện public

    -- Full-text search
    search_vector   TSVECTOR,

    created_at      TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at      TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    deleted_at      TIMESTAMP              -- Soft delete
);

-- ===== INDEXES =====
-- Search & filter chính
CREATE INDEX idx_listings_main ON listings(status, province_id, district_id, category_id, transaction_type);
CREATE INDEX idx_listings_price ON listings(price) WHERE price IS NOT NULL;
CREATE INDEX idx_listings_area ON listings(area) WHERE area IS NOT NULL;
CREATE INDEX idx_listings_featured ON listings(is_featured, sort_order DESC, published_at DESC);
CREATE INDEX idx_listings_published ON listings(published_at DESC) WHERE status = 'active';
CREATE INDEX idx_listings_expired ON listings(expired_at) WHERE status = 'active';
CREATE INDEX idx_listings_project ON listings(project_id) WHERE project_id IS NOT NULL;

-- Full-text search
CREATE INDEX idx_listings_fts ON listings USING GIN(search_vector);

-- JSONB extra_fields query (GIN index cho query như extra_fields->>'bedrooms' = '2')
CREATE INDEX idx_listings_extra ON listings USING GIN(extra_fields);

-- Geospatial (nếu dùng PostGIS)
-- CREATE INDEX idx_listings_geo ON listings USING GIST(ST_MakePoint(longitude, latitude));

-- ===== TRIGGER tự động cập nhật search_vector =====
CREATE OR REPLACE FUNCTION fn_update_listing_search_vector()
RETURNS TRIGGER AS $$
BEGIN
    NEW.search_vector := to_tsvector('simple',
        COALESCE(NEW.title, '')       || ' ' ||
        COALESCE(NEW.description, '') || ' ' ||
        COALESCE(NEW.address, '')     || ' ' ||
        COALESCE(NEW.full_address, '') || ' ' ||
        COALESCE(NEW.extra_fields::text, '')
    );
    RETURN NEW;
END;
$$ LANGUAGE plpgsql;

CREATE TRIGGER trg_listing_search
BEFORE INSERT OR UPDATE ON listings
FOR EACH ROW EXECUTE FUNCTION fn_update_listing_search_vector();

-- ===== TRIGGER tự động sinh listing_code =====
CREATE OR REPLACE FUNCTION fn_generate_listing_code()
RETURNS TRIGGER AS $$
BEGIN
    NEW.listing_code := 'BDS-' || LPAD(NEW.id::TEXT, 6, '0');
    RETURN NEW;
END;
$$ LANGUAGE plpgsql;

CREATE TRIGGER trg_listing_code
AFTER INSERT ON listings
FOR EACH ROW EXECUTE FUNCTION fn_generate_listing_code();
```

---

### 6.6 Bảng `listing_images` — Ảnh tin đăng

```sql
CREATE TABLE listing_images (
    id              BIGSERIAL PRIMARY KEY,
    listing_id      BIGINT NOT NULL REFERENCES listings(id) ON DELETE CASCADE,

    -- URLs theo kích thước
    url_original    VARCHAR(500) NOT NULL,
    url_thumbnail   VARCHAR(500),          -- 400x300
    url_medium      VARCHAR(500),          -- 800x600
    url_large       VARCHAR(500),          -- 1200x900
    url_webp        VARCHAR(500),          -- WebP version (tối ưu web)

    -- Metadata ảnh
    alt_text        VARCHAR(255),
    width           INT,
    height          INT,
    file_size       INT,                   -- Bytes

    -- Thứ tự & vai trò
    sort_order      INT DEFAULT 0,
    is_cover        BOOLEAN DEFAULT FALSE, -- Ảnh bìa

    -- Lưu trữ
    storage_key     VARCHAR(500),          -- S3 key (để xóa sau này)

    created_at      TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE INDEX idx_images_listing ON listing_images(listing_id, sort_order);
```

---

### 6.7 Bảng `listing_videos` — Video / Virtual Tour

```sql
CREATE TABLE listing_videos (
    id              BIGSERIAL PRIMARY KEY,
    listing_id      BIGINT NOT NULL REFERENCES listings(id) ON DELETE CASCADE,

    video_type      ENUM('youtube','vimeo','upload','virtual_tour_360','matterport') NOT NULL,

    raw_url         VARCHAR(500),          -- URL người dùng nhập
    embed_url       VARCHAR(500),          -- URL nhúng (xử lý từ raw_url)
    thumbnail_url   VARCHAR(500),

    title           VARCHAR(255),
    duration_seconds INT,
    sort_order      INT DEFAULT 0,

    created_at      TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);
```

---

### 6.8 Bảng `projects` — Dự án BĐS lớn

```sql
CREATE TABLE projects (
    id              BIGSERIAL PRIMARY KEY,
    project_code    VARCHAR(20) UNIQUE NOT NULL,   -- "PRJ-000001"
    slug            VARCHAR(400) UNIQUE NOT NULL,
    name            VARCHAR(400) NOT NULL,

    -- Phân loại
    project_type    ENUM(
                        'chung_cu',
                        'nha_pho_lien_ke',
                        'biet_thu',
                        'shophouse',
                        'resort',
                        'nha_o_xa_hoi',
                        'khu_do_thi',
                        'van_phong',
                        'khu_cong_nghiep'
                    ) NOT NULL,

    transaction_type ENUM('sale','rent','both') DEFAULT 'sale',

    -- Mô tả
    description     TEXT,
    highlights      TEXT,                  -- Điểm nổi bật (dạng bullet)

    -- Chủ đầu tư
    developer_name  VARCHAR(300),
    developer_logo  VARCHAR(500),
    developer_website VARCHAR(300),

    -- Đơn vị phân phối
    distributor_name VARCHAR(300),

    -- Vị trí
    address         VARCHAR(500),
    province_id     INT NOT NULL REFERENCES provinces(id),
    district_id     INT NOT NULL REFERENCES districts(id),
    ward_id         INT REFERENCES wards(id),
    latitude        DECIMAL(10,8),
    longitude       DECIMAL(11,8),

    -- Thông tin dự án
    total_units     INT,                   -- Tổng số căn/lô
    total_blocks    INT,                   -- Số tòa/phân khu
    total_floors    INT,
    area_total      DECIMAL(12,2),         -- Tổng diện tích đất dự án (m²)
    density         DECIMAL(5,2),          -- Mật độ xây dựng (%)

    -- Tiến độ
    started_at      DATE,
    expected_handover_at DATE,
    actual_handover_at DATE,
    status          ENUM('planning','approved','building','completed','selling','paused') DEFAULT 'planning',
    progress_percent INT,                  -- % tiến độ xây dựng

    -- Giá
    price_min       DECIMAL(18,2),
    price_max       DECIMAL(18,2),
    price_per_m2_min DECIMAL(12,2),
    price_per_m2_max DECIMAL(12,2),

    -- Pháp lý
    legal_status    ENUM('approved','pending','unknown') DEFAULT 'unknown',
    ownership_type  ENUM('long_term','50_years','99_years') DEFAULT 'long_term',

    -- Tiện ích dự án (JSONB linh hoạt)
    project_amenities JSONB DEFAULT '[]',
    -- Ví dụ: ["pool","gym","park","supermarket","school","hospital","security_247"]

    -- Media
    cover_image_url VARCHAR(500),
    logo_url        VARCHAR(500),
    virtual_tour_url VARCHAR(500),
    youtube_url     VARCHAR(500),

    -- Tài liệu
    brochure_url    VARCHAR(500),          -- PDF brochure
    floor_plan_url  VARCHAR(500),          -- Mặt bằng tổng thể

    -- SEO & Hiển thị
    is_featured     BOOLEAN DEFAULT FALSE,
    is_active       BOOLEAN DEFAULT TRUE,
    meta_title      VARCHAR(255),
    meta_description VARCHAR(500),
    sort_order      INT DEFAULT 0,

    created_at      TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at      TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);
```

---

### 6.9 Bảng `project_images` — Thư viện ảnh dự án

```sql
CREATE TABLE project_images (
    id              BIGSERIAL PRIMARY KEY,
    project_id      BIGINT NOT NULL REFERENCES projects(id) ON DELETE CASCADE,

    url_original    VARCHAR(500) NOT NULL,
    url_medium      VARCHAR(500),
    url_thumbnail   VARCHAR(500),

    image_category  ENUM(
                        'cover',           -- Ảnh bìa
                        'exterior',        -- Ngoại thất
                        'interior',        -- Nội thất
                        'floor_plan',      -- Mặt bằng
                        'location_map',    -- Bản đồ vị trí
                        'construction',    -- Tiến độ
                        'amenity'          -- Tiện ích
                    ) DEFAULT 'exterior',

    caption         VARCHAR(500),
    sort_order      INT DEFAULT 0,

    created_at      TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);
```

---

### 6.10 Bảng `project_unit_types` — Bảng giá căn hộ theo loại

```sql
-- Bảng loại căn trong dự án (VD: Căn 2PN loại A, Căn 3PN loại B)
CREATE TABLE project_unit_types (
    id              BIGSERIAL PRIMARY KEY,
    project_id      BIGINT NOT NULL REFERENCES projects(id) ON DELETE CASCADE,

    name            VARCHAR(255) NOT NULL,  -- "Căn 2 phòng ngủ - Loại A"
    unit_code       VARCHAR(50),            -- "2PN-A"

    bedrooms        TINYINT,
    bathrooms       TINYINT,
    area_min        DECIMAL(8,2),
    area_max        DECIMAL(8,2),

    price_min       DECIMAL(18,2),
    price_max       DECIMAL(18,2),

    floor_plan_url  VARCHAR(500),           -- Ảnh mặt bằng căn
    description     TEXT,

    available_units INT,                    -- Số căn còn
    total_units     INT,

    sort_order      INT DEFAULT 0,

    created_at      TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);
```

---

### 6.11 Bảng `provinces` / `districts` / `wards`

```sql
CREATE TABLE provinces (
    id          SERIAL PRIMARY KEY,
    code        VARCHAR(10) UNIQUE NOT NULL,    -- Mã ĐVHC: "79"
    name        VARCHAR(100) NOT NULL,           -- "Thành phố Hồ Chí Minh"
    name_short  VARCHAR(50),                    -- "TP. HCM"
    slug        VARCHAR(100) UNIQUE NOT NULL,   -- "ho-chi-minh"
    region      ENUM('north','central','south') DEFAULT 'south',
    sort_order  INT DEFAULT 0,
    is_active   BOOLEAN DEFAULT TRUE
);

CREATE TABLE districts (
    id          SERIAL PRIMARY KEY,
    province_id INT NOT NULL REFERENCES provinces(id),
    code        VARCHAR(10) UNIQUE NOT NULL,
    name        VARCHAR(100) NOT NULL,           -- "Quận 1"
    name_short  VARCHAR(50),
    slug        VARCHAR(100) NOT NULL,
    type        ENUM('quan','huyen','thi_xa','thanh_pho') DEFAULT 'quan',
    sort_order  INT DEFAULT 0,
    is_active   BOOLEAN DEFAULT TRUE,
    UNIQUE(province_id, slug)
);

CREATE TABLE wards (
    id          SERIAL PRIMARY KEY,
    district_id INT NOT NULL REFERENCES districts(id),
    code        VARCHAR(10) UNIQUE NOT NULL,
    name        VARCHAR(100) NOT NULL,           -- "Phường Bến Nghé"
    slug        VARCHAR(100) NOT NULL,
    type        ENUM('phuong','xa','thi_tran') DEFAULT 'phuong',
    is_active   BOOLEAN DEFAULT TRUE,
    UNIQUE(district_id, slug)
);
```

---

### 6.12 Bảng `contact_requests` — Liên hệ từ khách

```sql
CREATE TABLE contact_requests (
    id              BIGSERIAL PRIMARY KEY,

    -- Nguồn liên hệ
    listing_id      BIGINT REFERENCES listings(id) ON DELETE SET NULL,
    project_id      BIGINT REFERENCES projects(id) ON DELETE SET NULL,
    source_page     VARCHAR(500),          -- URL trang khách đang xem
    request_type    ENUM('listing','project','general','phone_click') DEFAULT 'listing',

    -- Thông tin khách
    full_name       VARCHAR(255),
    phone           VARCHAR(20),
    email           VARCHAR(255),
    message         TEXT,

    -- Yêu cầu
    interested_in   ENUM('buy','rent','consult','invest','other') DEFAULT 'consult',
    preferred_contact ENUM('phone','email','zalo','any') DEFAULT 'any',
    contact_time    VARCHAR(100),          -- "Buổi sáng", "Sau 18h"

    -- Xử lý
    status          ENUM('new','read','contacted','done','spam') DEFAULT 'new',
    admin_note      TEXT,

    -- Chống spam
    ip_address      VARCHAR(45),
    user_agent      VARCHAR(500),
    recaptcha_score DECIMAL(3,2),          -- 0.0 → 1.0

    -- Thông báo
    email_sent_to_admin BOOLEAN DEFAULT FALSE,
    email_sent_at   TIMESTAMP,

    created_at      TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE INDEX idx_contacts_new ON contact_requests(status, created_at DESC);
CREATE INDEX idx_contacts_listing ON contact_requests(listing_id) WHERE listing_id IS NOT NULL;
```

---

### 6.13 Bảng `listing_views` — Lượt xem

```sql
CREATE TABLE listing_views (
    id              BIGSERIAL PRIMARY KEY,
    listing_id      BIGINT NOT NULL REFERENCES listings(id) ON DELETE CASCADE,

    ip_address      VARCHAR(45),
    user_agent      VARCHAR(500),
    is_bot          BOOLEAN DEFAULT FALSE,     -- Detect bot crawl
    referer         VARCHAR(500),
    utm_source      VARCHAR(100),
    utm_medium      VARCHAR(100),
    utm_campaign    VARCHAR(100),

    is_phone_click  BOOLEAN DEFAULT FALSE,     -- True khi click xem SĐT

    viewed_at       TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    view_date       DATE GENERATED ALWAYS AS (DATE(viewed_at)) STORED
);

-- Chỉ count 1 view mỗi (listing_id + ip_address + view_date)
CREATE UNIQUE INDEX idx_views_dedup ON listing_views(listing_id, ip_address, view_date)
    WHERE is_bot = FALSE;

CREATE INDEX idx_views_listing_date ON listing_views(listing_id, view_date);
```

---

### 6.14 Bảng `blog_posts` — Bài viết (Tùy chọn)

```sql
CREATE TABLE blog_posts (
    id              BIGSERIAL PRIMARY KEY,
    slug            VARCHAR(500) UNIQUE NOT NULL,

    title           VARCHAR(500) NOT NULL,
    excerpt         VARCHAR(500),          -- Tóm tắt ngắn
    content         TEXT NOT NULL,         -- HTML content
    cover_image_url VARCHAR(500),

    -- Phân loại
    tags            JSONB DEFAULT '[]',    -- ["mua-nha","ho-chi-minh","can-ho"]
    category        VARCHAR(100),          -- "tin-tuc", "kien-thuc", "phap-ly"

    -- Liên quan
    related_listing_ids JSONB DEFAULT '[]', -- [123, 456] — tin đăng liên quan
    related_province_ids JSONB DEFAULT '[]', -- Tỉnh liên quan

    -- Trạng thái
    status          ENUM('draft','published','archived') DEFAULT 'draft',
    is_featured     BOOLEAN DEFAULT FALSE,

    -- Thống kê
    view_count      INT DEFAULT 0,

    -- SEO
    meta_title      VARCHAR(255),
    meta_description VARCHAR(500),
    focus_keyword   VARCHAR(255),

    published_at    TIMESTAMP,
    created_at      TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at      TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);
```

---

### 6.15 Bảng `banners` — Slider / Banner trang chủ

```sql
CREATE TABLE banners (
    id          SERIAL PRIMARY KEY,
    title       VARCHAR(255),
    subtitle    VARCHAR(500),

    position    ENUM(
                    'homepage_hero',       -- Slider trang chủ
                    'homepage_secondary',  -- Banner phụ trang chủ
                    'listing_list_top',    -- Trên danh sách tin
                    'listing_detail_sidebar', -- Sidebar trang chi tiết
                    'footer_banner'
                ) NOT NULL,

    image_url   VARCHAR(500) NOT NULL,
    image_url_mobile VARCHAR(500),         -- Ảnh khác cho mobile
    link_url    VARCHAR(500),
    link_target ENUM('_self','_blank') DEFAULT '_self',

    -- Hiệu ứng (nếu là hero slider)
    button_text VARCHAR(100),
    button_style VARCHAR(50),
    overlay_color VARCHAR(7),
    text_color  VARCHAR(7),

    sort_order  INT DEFAULT 0,
    is_active   BOOLEAN DEFAULT TRUE,

    -- Thời gian hiển thị (tùy chọn)
    display_from TIMESTAMP,
    display_to  TIMESTAMP,

    click_count INT DEFAULT 0,
    created_at  TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);
```

---

### 6.16 Bảng `testimonials` — Đánh giá khách hàng

```sql
CREATE TABLE testimonials (
    id          SERIAL PRIMARY KEY,
    client_name VARCHAR(255) NOT NULL,
    client_title VARCHAR(255),             -- "Khách hàng mua nhà tháng 3/2024"
    client_avatar VARCHAR(500),

    content     TEXT NOT NULL,
    rating      TINYINT DEFAULT 5 CHECK(rating BETWEEN 1 AND 5),

    -- Liên kết tin đã bán (tùy chọn)
    listing_id  BIGINT REFERENCES listings(id) ON DELETE SET NULL,

    sort_order  INT DEFAULT 0,
    is_active   BOOLEAN DEFAULT TRUE,

    created_at  TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);
```

---

### 6.17 Bảng `site_settings` — Cài đặt hệ thống

```sql
CREATE TABLE site_settings (
    id          SERIAL PRIMARY KEY,
    key         VARCHAR(100) UNIQUE NOT NULL,
    value       TEXT,
    type        ENUM('text','json','boolean','number','color','url') DEFAULT 'text',
    group_name  VARCHAR(50) NOT NULL DEFAULT 'general',
    label       VARCHAR(255),
    description VARCHAR(500),
    is_public   BOOLEAN DEFAULT FALSE,     -- TRUE = gửi xuống frontend
    updated_at  TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Dữ liệu mặc định
INSERT INTO site_settings (key, value, type, group_name, label, is_public) VALUES
-- Thông tin chung
('site_name',           'Bất Động Sản Cá Nhân',     'text',    'general', 'Tên website', TRUE),
('site_tagline',        'Tìm nhà trong tầm tay',     'text',    'general', 'Slogan', TRUE),
('site_description',    '',                          'text',    'general', 'Mô tả website', TRUE),

-- Liên hệ
('contact_phone',       '',                          'text',    'contact', 'SĐT chính', TRUE),
('contact_phone_2',     '',                          'text',    'contact', 'SĐT phụ', TRUE),
('contact_email',       '',                          'text',    'contact', 'Email', TRUE),
('contact_address',     '',                          'text',    'contact', 'Địa chỉ', TRUE),
('zalo_url',            '',                          'url',     'contact', 'Zalo URL', TRUE),
('facebook_url',        '',                          'url',     'contact', 'Facebook', TRUE),
('youtube_url',         '',                          'url',     'contact', 'YouTube', TRUE),

-- Giao diện
('theme',               'modern',                    'text',    'theme', 'Giao diện', FALSE),
('primary_color',       '#E84040',                   'color',   'theme', 'Màu chủ đạo', TRUE),
('logo_url',            '',                          'url',     'theme', 'Logo URL', TRUE),
('favicon_url',         '',                          'url',     'theme', 'Favicon URL', TRUE),

-- Nghiệp vụ
('listing_duration_days','60',                       'number',  'business', 'Số ngày hiệu lực tin', FALSE),
('max_images_per_listing','20',                      'number',  'business', 'Số ảnh tối đa/tin', FALSE),
('phone_reveal_method', 'click',                     'text',    'business', 'Cách hiện SĐT (click/blur)', TRUE),
('show_sold_listings',  'true',                      'boolean', 'business', 'Hiện tin đã bán', TRUE),

-- SEO
('google_analytics_id', '',                          'text',    'seo', 'Google Analytics ID', FALSE),
('google_maps_api_key', '',                          'text',    'seo', 'Google Maps Key', FALSE),
('seo_meta_title',      '',                          'text',    'seo', 'Meta title mặc định', TRUE),
('seo_meta_description','',                          'text',    'seo', 'Meta description mặc định', TRUE),
('google_site_verify',  '',                          'text',    'seo', 'Google Search Console verify', FALSE),

-- Features
('feature_map',         'true',                      'boolean', 'features', 'Bản đồ', FALSE),
('feature_blog',        'false',                     'boolean', 'features', 'Blog', FALSE),
('feature_calculator',  'true',                      'boolean', 'features', 'Máy tính vay', FALSE),
('feature_compare',     'true',                      'boolean', 'features', 'So sánh tin', FALSE),
('feature_project',     'true',                      'boolean', 'features', 'Module dự án', FALSE),
('feature_testimonials','true',                      'boolean', 'features', 'Đánh giá khách hàng', FALSE),
('recaptcha_site_key',  '',                          'text',    'security', 'reCAPTCHA site key', TRUE),
('recaptcha_secret_key','',                          'text',    'security', 'reCAPTCHA secret key', FALSE);
```

---

### 6.18 Bảng `slug_redirects` — SEO Redirect

```sql
CREATE TABLE slug_redirects (
    id          BIGSERIAL PRIMARY KEY,
    old_slug    VARCHAR(600) NOT NULL,
    new_slug    VARCHAR(600),              -- NULL = 410 Gone
    redirect_type ENUM('301','410') DEFAULT '301',
    hit_count   INT DEFAULT 0,
    created_at  TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    INDEX(old_slug)
);
```

---

## 7. QUAN HỆ BẢNG (ERD)

```
admin_users (1) ════════════════ Sở hữu toàn bộ
       │
       └─── [ký hiệu quan hệ]

listings (N) ──────────── (1) categories
listings (N) ──────────── (1) provinces
listings (N) ──────────── (1) districts
listings (N) ──────────── (1) wards
listings (N) ──────────── (1) projects [optional]
listings (1) ──────────── (N) listing_images
listings (1) ──────────── (N) listing_videos
listings (1) ──────────── (N) listing_views
listings (1) ──────────── (N) contact_requests

categories
  └── self-referencing: parent_id → id (cây 2 cấp)
  └── property_group → định nghĩa set field động

field_definitions (N) ──── (1) property_group [ENUM, không cần FK]
  └── Được dùng để: render form | hiển thị detail | cấu hình filter

projects (N) ──────────── (1) provinces
projects (N) ──────────── (1) districts
projects (1) ──────────── (N) project_images
projects (1) ──────────── (N) project_unit_types
projects (1) ──────────── (N) listings [1 dự án có nhiều tin đơn]

provinces (1) ─────────── (N) districts
districts (1) ─────────── (N) wards

site_settings (standalone — không FK)
banners (standalone)
testimonials (N) ──────── (1) listings [optional]
blog_posts (standalone)
slug_redirects (standalone)

TỔNG: 18 bảng chính
```

---

## 8. LOGIC DYNAMIC FIELD HOẠT ĐỘNG NHƯ THẾ NÀO

### 8.1 Luồng Admin Đăng Tin (Form Dynamic)

```
1. Admin chọn Danh mục (VD: "Cho thuê mặt bằng")
   ↓
2. System xác định property_group = 'commercial'
   ↓
3. Query: SELECT * FROM field_definitions
          WHERE property_group = 'commercial'
          ORDER BY sort_order
   → Trả về danh sách field: width, space_type, electricity_capacity, ...
   ↓
4. Frontend render form với đúng các field đó
   (grouped theo section_name: "Diện tích", "Thông tin kỹ thuật", "Tiện ích")
   ↓
5. Admin điền form → Submit
   ↓
6. Backend validate:
   - Core fields (title, price, area, province_id...) → kiểm tra chuẩn
   - Dynamic fields → validate theo is_required và type trong field_definitions
   ↓
7. Lưu vào DB:
   - Core fields → cột riêng trong listings
   - Dynamic fields → extra_fields JSONB
```

### 8.2 Luồng Khách Xem Tin (Render Dynamic)

```
1. Khách vào /tin/[slug]
   ↓
2. Query listings + categories (join lấy property_group)
   ↓
3. Query field_definitions WHERE property_group = ? AND is_public = TRUE
   → Biết cần hiển thị field nào, với label gì, icon gì, unit gì
   ↓
4. Từ listings.extra_fields JSONB → extract từng field
   ↓
5. Render:
   - Chỉ hiện field có giá trị (bỏ qua null/undefined)
   - Dùng label từ field_definitions (không hardcode UI)
   - Dùng icon, unit từ field_definitions
```

### 8.3 Luồng Filter Tìm Kiếm Dynamic

```
1. Khách vào trang /cho-thue/mat-bang
   ↓
2. System biết: transaction_type=rent, category has property_group='commercial'
   ↓
3. Query: SELECT * FROM field_definitions
          WHERE property_group = 'commercial'
          AND is_filterable = TRUE
   → Trả về: width (range), space_type (select), has_elevator (boolean)...
   ↓
4. Render bộ lọc sidebar với đúng các field đó
   ↓
5. Khi khách chọn filter VD: space_type = 'shop':
   Query: SELECT * FROM listings
          WHERE extra_fields->>'space_type' = 'shop'

   Hoặc dùng JSONB operator:
   WHERE extra_fields @> '{"space_type": "shop"}'
```

---

## 9. ADMIN PANEL — CẤU TRÚC CHUẨN HÓA

### Cấu trúc sidebar Admin (giống nhau mọi deployment)

```
📊 DASHBOARD
  └── Tổng quan (KPIs, biểu đồ 30 ngày)

🏠 TIN ĐĂNG
  ├── Danh sách tin
  ├── Thêm tin mới
  └── [Tin hết hạn] (badge đỏ nếu có)

🏗️ DỰ ÁN (ẩn nếu feature_project=false)
  ├── Danh sách dự án
  └── Thêm dự án mới

📩 LIÊN HỆ
  └── Hộp thư đến (badge số tin nhắn mới)

📝 NỘI DUNG
  ├── Bài viết Blog (ẩn nếu feature_blog=false)
  ├── Banner / Slider
  └── Đánh giá khách hàng

📁 THƯ VIỆN ẢNH
  └── Tất cả ảnh đã upload

📊 THỐNG KÊ
  └── Báo cáo lượt xem, liên hệ

⚙️ CÀI ĐẶT
  ├── Thông tin & Liên hệ
  ├── Giao diện & Thương hiệu
  ├── Tính năng
  ├── SEO & Google
  └── Bảo mật
```

### Admin Form đăng tin — Các section

```
SECTION 1: Loại tin
  - Giao dịch: [Mua bán] [Cho thuê]
  - Danh mục: (dropdown cây)
  → Sau khi chọn → hiện các section tiếp theo tương ứng

SECTION 2: Thông tin cơ bản
  - Tiêu đề (required)
  - Mô tả (rich text hoặc textarea)

SECTION 3: Giá
  - Giá: [input] [VND]
  - Checkbox: Thỏa thuận
  - Đơn vị: [Tổng giá] [Triệu/m²] [Triệu/tháng]

SECTION 4: Vị trí
  - Tỉnh/Thành (required)
  - Quận/Huyện (required, cascade)
  - Phường/Xã (cascade)
  - Đường / Tên đường
  - Địa chỉ chi tiết
  - [Tùy chọn]: Hiển thị địa chỉ chính xác
  - Bản đồ: Click để chọn tọa độ

SECTION 5: FIELD ĐỘNG (load theo property_group)
  → Render từ field_definitions
  → Chia theo section_name

SECTION 6: Ảnh & Video
  - Upload ảnh (kéo thả, sort, chọn ảnh bìa)
  - Video (paste YouTube URL)
  - Virtual Tour URL

SECTION 7: Pháp lý
  - Tình trạng pháp lý (select)

SECTION 8: Liên hệ
  - Tên hiển thị (default: admin.display_name)
  - SĐT (default: admin.public_phone)

SECTION 9: SEO & Nâng cao
  - Meta title (auto-gen nếu để trống)
  - Meta description
  - Nổi bật (checkbox)
  - Ghi chú nội bộ

BUTTONS:
  [Lưu nháp]  [Xem trước]  [Đăng tin]
```

---

## 10. MULTI-DEPLOYMENT — CÁCH TRIỂN KHAI NHIỀU DỰ ÁN

### 10.1 Cấu trúc thư mục project

```
/real-estate-app
├── /src
│   ├── /app               — Next.js App Router pages
│   ├── /components        — UI components
│   ├── /lib               — Utilities, DB client, etc.
│   ├── /hooks
│   └── /types
├── /themes                — Layout themes
│   ├── /modern            — Theme hiện đại
│   ├── /corporate         — Theme doanh nghiệp
│   ├── /minimal           — Theme tối giản
│   └── /luxury            — Theme cao cấp
├── /prisma                — Database schema (dùng chung)
│   └── schema.prisma
├── .env.example           — Template env mỗi deployment
├── .env.site-a            — Config cho Site A
├── .env.site-b            — Config cho Site B
└── docker-compose.yml
```

### 10.2 Quy trình deploy dự án mới

```bash
# Bước 1: Copy và cấu hình env
cp .env.example .env.site-c
nano .env.site-c  # Điền thông tin brand, DB, API keys

# Bước 2: Tạo database mới
createdb site_c_db
DATABASE_URL=postgresql://user:pass@host/site_c_db

# Bước 3: Chạy migration
npx prisma migrate deploy

# Bước 4: Seed dữ liệu cơ bản
npx prisma db seed  # Tạo admin, danh mục mặc định, provinces/districts

# Bước 5: Build và deploy
npm run build
pm2 start "npm start" --name "site-c" -- --port 3002

# Bước 6: Cấu hình Nginx
# upstream site-c → localhost:3002
# server_name site-c.com
```

### 10.3 Dữ liệu khởi tạo mỗi deployment (Seed)

```sql
-- 1. Admin user (đổi email/password sau)
INSERT INTO admin_users (email, password_hash, full_name)
VALUES ('admin@site.com', '[hashed]', 'Admin');

-- 2. Provinces/Districts (dùng chung file seed Việt Nam)
-- Import từ file JSON chuẩn hóa 63 tỉnh thành

-- 3. Danh mục mặc định
-- Cấp 1: Mua bán, Cho thuê
-- Cấp 2: Căn hộ, Nhà phố, Đất, Mặt bằng, Phòng trọ

-- 4. Field definitions mặc định
-- Import từ file JSON chuẩn (field_definitions_seed.json)
-- Admin có thể tùy chỉnh sau

-- 5. Site settings mặc định
-- Import defaults, admin sẽ update qua UI
```

---

## 11. TECH STACK & INFRASTRUCTURE

### 11.1 Stack chính

| Layer                | Technology                   | Lý do chọn                                                             |
| -------------------- | ---------------------------- | ---------------------------------------------------------------------- |
| **Framework**        | Next.js 14 (App Router)      | SSR/SSG/ISR, SEO tốt, API routes, 1 codebase cho cả frontend + backend |
| **Database**         | PostgreSQL 15+               | JSONB tốt cho dynamic fields, Full-text search, PostGIS tùy chọn       |
| **ORM**              | Prisma                       | Type-safe, migration tốt, dễ seed data                                 |
| **Auth**             | Custom JWT + bcrypt          | Đơn giản, 1 admin, không cần NextAuth                                  |
| **Image Storage**    | Cloudflare R2                | S3-compatible, free egress, rẻ hơn AWS S3                              |
| **Image Processing** | Sharp (Node.js)              | Resize, compress, convert WebP                                         |
| **Cache**            | Next.js built-in cache + ISR | Không cần Redis cho hệ thống nhỏ                                       |
| **Email**            | Resend.com                   | Free 3000 email/tháng, API đơn giản                                    |
| **Maps**             | Google Maps JS API           | Embed map, geocoding                                                   |
| **UI**               | TailwindCSS + shadcn/ui      | Component library đẹp, customize được                                  |
| **Rich Text**        | TipTap                       | Editor cho blog, mô tả                                                 |
| **Deploy**           | Vercel / VPS + PM2           | Vercel cho deploy nhanh, VPS cho control                               |
| **Domain**           | Cloudflare DNS               | Free, DDoS protection                                                  |

### 11.2 Ước tính chi phí mỗi deployment

| Dịch vụ             | Free tier              | Chi phí khi tăng               |
| ------------------- | ---------------------- | ------------------------------ |
| Vercel Hobby        | Free (1 project)       | $20/tháng (Pro, nhiều project) |
| Supabase PostgreSQL | Free 500MB             | $25/tháng                      |
| Cloudflare R2       | Free 10GB/tháng        | $0.015/GB sau đó               |
| Resend Email        | Free 3000/tháng        | $20/tháng (50k emails)         |
| Google Maps         | Free $200 credit/tháng | Hầu hết miễn phí               |
| **Tổng**            | **~$0/tháng**          | **~$45–65/tháng nếu scale**    |

### 11.3 Cron Jobs cần thiết

```
Chạy hàng đêm (00:00):
  → Kiểm tra tin sắp hết hạn (còn 7 ngày): gửi email nhắc admin
  → Cập nhật status = 'expired' cho tin đã hết expired_at
  → Cập nhật view_count cache trong listings table
  → Refresh materialized views (nếu có)

Chạy mỗi tuần (Thứ 2 08:00):
  → Gửi báo cáo tuần: tổng lượt xem, liên hệ, tin mới

Chạy mỗi giờ:
  → Ping sitemap lên Google Search Console (sau khi có tin mới)
```

---

## 12. CHECKLIST TRIỂN KHAI

### ✅ Phase 1 — Core (Tuần 1-2)

- [ ] Setup Next.js + PostgreSQL + Prisma
- [ ] Seed data: provinces, districts, wards VN
- [ ] Admin auth (login, logout, refresh token)
- [ ] CRUD Categories + Field Definitions
- [ ] CRUD Listings (form tĩnh trước, dynamic sau)
- [ ] Upload ảnh → Cloudflare R2

### ✅ Phase 2 — Public Site (Tuần 2-3)

- [ ] Trang chủ
- [ ] Trang danh sách + filter cơ bản
- [ ] Trang chi tiết tin (hiển thị dynamic fields)
- [ ] Trang dự án
- [ ] Form liên hệ + email notification
- [ ] SEO: meta tags, OG, sitemap.xml

### ✅ Phase 3 — Dynamic & Polish (Tuần 3-4)

- [ ] Field Builder UI trong admin
- [ ] Dynamic filter (filterable fields)
- [ ] Full-text search
- [ ] Google Maps integration
- [ ] Thống kê lượt xem
- [ ] Cron job hết hạn tin

### ✅ Phase 4 — Multi-deploy Ready

- [ ] Env-based feature flags
- [ ] Theme system
- [ ] Seed script chuẩn hóa
- [ ] Deploy script (Nginx config template)
- [ ] Admin settings UI đầy đủ

---

_Tài liệu v2.0 — Optimized for Single Broker + Dynamic Fields + Multi-Deployment_
_Tổng: 18 bảng · 23 trang Admin · 20 trang Public · 14 Modules_
