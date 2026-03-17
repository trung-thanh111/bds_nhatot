<?php

namespace App\Repositories\RealEstate;

use App\Repositories\BaseRepository;
use App\Models\Project;

class ProjectRepository extends BaseRepository
{
    public function __construct(Project $model)
    {
        $this->model = $model;
    }

    public function pagination(
        array $column = ['*'],
        array $condition = [],
        int $perPage = 1,
        array $extend = [],
        array $orderBy = ['id', 'DESC'],
        array $join = [],
        array $relations = [],
        array $rawQuery = []
    ) {
        $query = $this->model->select($column);

        // =========================================================
        // Xây dựng dropdownFilter - CHỈ lấy các field cần WHERE =
        // LƯU Ý: Loại bỏ 'publish' khỏi đây vì đã được xử lý
        // riêng bởi scopePublish() để tránh filter 2 lần
        // Thêm 'price_negotiable' vào để filter giá thỏa thuận
        // =========================================================
        $dropdownFilter = array_intersect_key($condition, array_flip([
            // 'publish'     => KHÔNG thêm - đã có scopePublish xử lý riêng
            'property_group',
            'province_code',
            'district_code',
            'ward_code',
            'province_new_code',
            'ward_new_code',
            'direction',
            'balcony_direction',
            'legal_status',
            'furniture_status',
            'is_featured',
            'is_hot',
            'is_urgent',
            'catalogue_id',       // Fix: thêm filter catalogue
            'price_negotiable',   // Fix: thêm filter giá thỏa thuận
        ]));

        $query = $query
            // Filter keyword theo name VÀ code (xem scopeKeyword đã fix)
            ->keyword($condition['keyword'] ?? null, ['name', 'code'])
            // Filter trạng thái xuất bản
            ->publish($condition['publish'] ?? null)
            // Eager load relations nếu cần
            ->relationCount($relations)
            // Custom join nếu cần
            ->CustomJoin($join)
            // Filter các field dropdown (=)
            ->CustomDropdownFilter($dropdownFilter)
            // Filter các điều kiện raw (price range, area range, catalogue IN)
            ->CustomWhereRaw($rawQuery)
            // Sắp xếp
            ->orderBy($orderBy[0], $orderBy[1]);

        // Log để debug (có thể tắt trên production)
        \Illuminate\Support\Facades\Log::info('Project Query SQL: ' . $query->toSql());
        \Illuminate\Support\Facades\Log::info('Project Bindings: ' . json_encode($query->getBindings()));

        return $query
            ->paginate($perPage)
            ->withQueryString();
    }
}
