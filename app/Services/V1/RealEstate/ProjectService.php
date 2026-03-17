<?php

namespace App\Services\V1\RealEstate;

use App\Services\V1\BaseService;
use App\Repositories\RealEstate\ProjectRepository;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;
use Carbon\Carbon;

class ProjectService extends BaseService
{
    protected $projectRepository;

    public function __construct(
        ProjectRepository $projectRepository
    ) {
        $this->projectRepository = $projectRepository;
    }

    /**
     * Lấy danh sách dự án có phân trang kèm bộ filter
     */
    public function paginate($request)
    {
        $perPage = $request->integer('perpage', 20);
        $condition = [];
        $rawQuery = [];

        // =========================================================
        // 1. FILTER TRẠNG THÁI XUẤT BẢN (publish)
        // Chỉ thêm vào condition khi có giá trị hợp lệ (không phải '0')
        // =========================================================
        if ($request->filled('publish') && $request->input('publish') != '0') {
            $condition['publish'] = $request->input('publish');
        }

        // =========================================================
        // 2. FILTER NHÓM BĐS (catalogue_id) + CÁC DANH MỤC CON
        // Sử dụng nested set (lft/rgt) để lấy tất cả con/cháu
        // Dùng Cache để tránh query DB nhiều lần → tránh giảm performance
        // =========================================================
        if ($request->filled('catalogue_id') && $request->input('catalogue_id') != '0') {
            $catalogueId = (int) $request->input('catalogue_id');
            $catalogue = \App\Models\ProjectCatalogue::find($catalogueId);
            if ($catalogue) {
                // Check if nested set data is initialized
                if ($catalogue->lft > 0 && $catalogue->rgt > 0) {
                    // Optimized: Use Subquery to handle performance for nested sets
                    $rawQuery[] = [
                        'catalogue_id IN (SELECT id FROM project_catalogues WHERE lft >= ? AND rgt <= ?)', 
                        [$catalogue->lft, $catalogue->rgt]
                    ];
                } else {
                    // Fallback to specific ID if nested set isn't set up (lft/rgt = 0)
                    $condition['catalogue_id'] = $catalogueId;
                }
            }
        }

        // =========================================================
        // 3. FILTER NHÓM BĐS (property_group)
        // =========================================================
        if ($request->filled('property_group') && $request->input('property_group') != '0') {
            $condition['property_group'] = $request->input('property_group');
        }

        // =========================================================
        // 4. FILTER KEYWORD (tìm theo tên VÀ mã tin)
        // =========================================================
        if ($request->filled('keyword')) {
            $condition['keyword'] = trim($request->input('keyword'));
        }

        // =========================================================
        // 5. FILTER ĐỊA ĐIỂM (tỉnh/thành theo loại địa chỉ cũ/mới)
        // =========================================================
        $locationType  = $request->input('location_type');
        $provinceCode  = $request->input('province_code');

        if ($provinceCode && $provinceCode != '0') {
            if ($locationType == 'new') {
                $condition['province_new_code'] = $provinceCode;
                if ($request->filled('ward_new_code') && $request->input('ward_new_code') != '0') {
                    $condition['ward_new_code'] = $request->input('ward_new_code');
                }
            } else {
                $condition['province_code'] = $provinceCode;
                if ($request->filled('district_code') && $request->input('district_code') != '0') {
                    $condition['district_code'] = $request->input('district_code');
                }
                if ($request->filled('ward_code') && $request->input('ward_code') != '0') {
                    $condition['ward_code'] = $request->input('ward_code');
                }
            }
        }

        // =========================================================
        // 6. FILTER TRẠNG THÁI ĐẶC BIỆT (is_featured, is_hot, is_urgent)
        // =========================================================
        $specialStatus = $request->input('special_status');
        if ($specialStatus && $specialStatus != '0' && $specialStatus != '') {
            if ($specialStatus == 'featured') $condition['is_featured'] = 1;
            if ($specialStatus == 'hot') $condition['is_hot'] = 1;
            if ($specialStatus == 'urgent') $condition['is_urgent'] = 1;
        }

        // =========================================================
        // 7. FILTER CÁC DROPDOWN ĐƠN GIẢN (=)
        // direction, balcony_direction, legal_status, furniture_status, publish
        // =========================================================
        $simpleFilters = ['direction', 'balcony_direction', 'legal_status', 'furniture_status', 'publish'];
        foreach ($simpleFilters as $filter) {
            if ($request->filled($filter) && ($val = $request->input($filter)) != '0' && $val != '') {
                $condition[$filter] = $val;
            }
        }

        // =========================================================
        // 8. FILTER GIÁ (price_vnd, price_negotiable)
        // filter theo cả price_vnd (giá VND) và price (nếu giá trị là tổng cộng)
        // =========================================================
        $priceRange = $request->input('price_range');

        if ($request->filled('price_negotiable') || $priceRange == 'negotiable') {
            // Giá thỏa thuận: filter bản ghi có price_negotiable = 1
            $condition['price_negotiable'] = 1;
        } elseif ($priceRange && $priceRange != '0') {
            // Dropdown: giá trị đã là số đầy đủ (VD: 500000000-1000000000)
            [$pFrom, $pTo] = explode('-', $priceRange);
            $pFrom = (float) $pFrom;
            $pTo = (float) $pTo;

            if ($pFrom > 0) {
                // Kiểm tra cả giá đã quy đổi VÀ giá gốc (để cover các case per_m2 nhưng giá lớn)
                $rawQuery[] = [
                    '(price_vnd >= ? OR price >= ?)', 
                    [$pFrom, $pFrom]
                ];
            }
            if ($pTo > 0) {
                $rawQuery[] = [
                    '(price_vnd <= ? OR price <= ?)', 
                    [$pTo, $pTo]
                ];
            }
        } else {
            // Manual input: nhân với 1,000,000 (input tính bằng triệu)
            if ($request->filled('price_from') && (float) $request->input('price_from') > 0) {
                $pFromManual = (float) $request->input('price_from') * 1_000_000;
                $rawQuery[] = [
                    '(price_vnd >= ? OR price >= ?)', 
                    [$pFromManual, $pFromManual]
                ];
            }
            if ($request->filled('price_to') && (float) $request->input('price_to') > 0) {
                $pToManual = (float) $request->input('price_to') * 1_000_000;
                $rawQuery[] = [
                    '(price_vnd <= ? OR price <= ?)', 
                    [$pToManual, $pToManual]
                ];
            }
        }

        // =========================================================
        // 9. FILTER DIỆN TÍCH (area, area_use, area_land)
        // Dùng OR giữa 3 field diện tích vì BĐS có thể dùng 1 trong 3
        // Bọc trong group để không phá vỡ các điều kiện AND khác
        // =========================================================
        $areaRange = $request->input('area_range');
        $aFrom = 0;
        $aTo   = 0;

        if ($areaRange && $areaRange != '0') {
            [$aFrom, $aTo] = explode('-', $areaRange);
            $aFrom = (float) $aFrom;
            $aTo   = (float) $aTo;
        } else {
            $aFrom = (float) $request->input('area_from', 0);
            $aTo   = (float) $request->input('area_to', 0);
        }

        // Lọc giới hạn dưới: ít nhất 1 trong 3 field >= aFrom
        if ($aFrom > 0) {
            $rawQuery[] = [
                '(area >= ? OR area_use >= ? OR area_land >= ?)',
                [$aFrom, $aFrom, $aFrom]
            ];
        }

        // Lọc giới hạn trên: ít nhất 1 trong 3 field <= aTo
        if ($aTo > 0) {
            $rawQuery[] = [
                '(area <= ? OR area_use <= ? OR area_land <= ?)',
                [$aTo, $aTo, $aTo]
            ];
        }

        // Debug log (tắt trên production)
        \Illuminate\Support\Facades\Log::info('ProjectService conditions: ' . json_encode($condition));
        \Illuminate\Support\Facades\Log::info('ProjectService rawQuery: ' . json_encode($rawQuery));

        return $this->projectRepository->pagination(
            $this->paginateSelect(),
            $condition,
            $perPage,
            ['path' => '/realestate/project/index'],
            ['id', 'DESC'],
            [],
            [],
            $rawQuery
        );
    }

    /**
     * Tạo mới bản ghi dự án
     */
    public function create($request)
    {
        DB::beginTransaction();
        try {
            $payload = $this->requestPayload($request);

            if (empty($payload['slug'])) {
                $payload['slug'] = Str::slug($payload['name']);
            }
            if ($payload['publish'] == 2 && empty($payload['published_at'])) {
                $payload['published_at'] = Carbon::now();
            }
            if (empty($payload['code'])) {
                $payload['code'] = 'BDS-' . strtoupper(Str::random(8));
            }

            $this->projectRepository->create($payload);

            DB::commit();
            return true;
        } catch (\Exception $e) {
            DB::rollBack();
            \Illuminate\Support\Facades\Log::error('ProjectService::create error: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Cập nhật bản ghi dự án
     */
    public function update($id, $request)
    {
        DB::beginTransaction();
        try {
            $payload = $this->requestPayload($request);

            if (empty($payload['slug'])) {
                $payload['slug'] = Str::slug($payload['name']);
            }

            $this->projectRepository->update($id, $payload);

            DB::commit();
            return true;
        } catch (\Exception $e) {
            DB::rollBack();
            \Illuminate\Support\Facades\Log::error('ProjectService::update error: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Xóa bản ghi dự án
     */
    public function destroy($id)
    {
        DB::beginTransaction();
        try {
            $this->projectRepository->delete($id);
            DB::commit();
            return true;
        } catch (\Exception $e) {
            DB::rollBack();
            return false;
        }
    }

    /**
     * Xây dựng payload từ request để lưu DB
     */
    private function requestPayload($request)
    {
        $payload = $request->only($this->payload());

        // Chuyển đổi giá (bỏ dấu chấm ngăn cách hàng nghìn)
        if (isset($payload['price'])) {
            $payload['price'] = (float) str_replace('.', '', $payload['price']);
        }

        // Tính price_vnd dựa trên đơn vị và diện tích
        $price = (float) ($payload['price'] ?? 0);
        $area  = (float) ($payload['area'] ?? $payload['area_use'] ?? $payload['area_land'] ?? 0);
        $unit  = $payload['price_unit'] ?? 'total';

        $payload['price_vnd'] = ($unit == 'per_m2' || $unit == 'per_m2_month')
            ? $price * $area
            : $price;

        // Xử lý các checkbox tiện ích (mặc định = 0 nếu không check)
        $checkboxFields = [
            'has_elevator',
            'has_pool',
            'has_parking',
            'has_security',
            'has_balcony',
            'has_rooftop',
            'has_basement',
            'has_gym',
            'has_ac',
            'has_wifi',
        ];
        foreach ($checkboxFields as $field) {
            $payload[$field] = ($request->input($field) == 1 || $request->has($field)) ? 1 : 0;
        }

        // Lấy các trường tên địa điểm (_name)
        $locationNameFields = [
            'province_name',
            'province_new_name',
        ];
        foreach ($locationNameFields as $field) {
            if ($request->has($field)) {
                $payload[$field] = $request->input($field);
            }
        }

        // Checkbox trạng thái đặc biệt
        $payload['is_featured'] = $request->has('is_featured') ? 1 : 0;
        $payload['is_hot']      = $request->has('is_hot') ? 1 : 0;
        $payload['is_urgent']   = $request->has('is_urgent') ? 1 : 0;

        return $payload;
    }

    /**
     * Các field SELECT cho trang danh sách
     */
    private function paginateSelect(): array
    {
        return [
            'id',
            'code',
            'name',
            'slug',
            'image',
            'price',
            'price_unit',
            'price_vnd',
            'area',
            'province_name',
            'province_new_name',
            'publish',
            'is_featured',
            'is_hot',
            'is_urgent',
            'sort_order',
            'view_count',
        ];
    }

    /**
     * Danh sách các field được phép lưu vào DB
     */
    private function payload(): array
    {
        return [
            'code',
            'name',
            'slug',
            'catalogue_id',
            'type_code',
            'property_group',
            'transaction_type',
            'is_project',
            'summary',
            'description',
            'meta_title',
            'meta_desc',
            'focus_keyword',
            'price',
            'price_unit',
            'price_vnd',
            'price_negotiable',
            'area',
            'area_use',
            'area_land',
            'length',
            'width',
            'bedrooms',
            'bathrooms',
            'floors',
            'floor_number',
            'direction',
            'balcony_direction',
            'legal_status',
            'furniture_status',
            'has_elevator',
            'has_pool',
            'has_parking',
            'has_security',
            'has_balcony',
            'has_rooftop',
            'has_basement',
            'has_gym',
            'has_ac',
            'has_wifi',
            'province_code',
            'province_name',
            'district_code',
            'district_name',
            'ward_code',
            'ward_name',
            'province_new_code',
            'province_new_name',
            'ward_new_code',
            'ward_new_name',
            'address',
            'latitude',
            'longitude',
            'image',
            'album',
            'iframe_map',
            'has_video',
            'video_url',
            'video_embed',
            'has_virtual_tour',
            'virtual_tour_url',
            'extra_fields',
            'status',
            'publish',
            'is_featured',
            'is_hot',
            'is_urgent',
            'sort_order',
            'view_count',
            'published_at',
        ];
    }
}
