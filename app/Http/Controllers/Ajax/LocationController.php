<?php

namespace App\Http\Controllers\Ajax;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Repositories\Core\DistrictRepository;
use App\Repositories\User\ProvinceRepository;

class LocationController extends Controller
{

    protected $districtRepository;
    protected $provinceRepository;

    public function __construct(
        DistrictRepository $districtRepository,
        ProvinceRepository $provinceRepository
    ) {
        $this->districtRepository = $districtRepository;
        $this->provinceRepository = $provinceRepository;
    }

    public function getLocation(Request $request)
    {
        $get = $request->input();
        $source = $get['source'] ?? 'database';
        $target = $get['target'] ?? '';
        $html = '';

        if ($source == 'database') {
            $locationId = $get['data']['location_id'] ?? 0;
            if ($target == 'province') {
                $provinces = $this->provinceRepository->all();
                $html = '<option value="0">Chọn Tỉnh/TP</option>';
                foreach ($provinces as $province) {
                    $val = $province->code_name ?? $province->code;
                    $html .= '<option value="' . $val . '">' . $province->name . '</option>';
                }
            } else if ($locationId == 0 || empty($target)) {
                if (str_contains($target, 'districts')) {
                    $html = '<option value="0">[Chọn Quận/Huyện]</option>';
                } else if (str_contains($target, 'wards')) {
                    $html = '<option value="0">[Chọn Phường/Xã]</option>';
                }
            } else {
                if (str_contains($target, 'districts')) {
                    $province = $this->provinceRepository->findByCondition([['code', '=', $locationId]], false, ['districts']);
                    if (!$province) {
                        $province = $this->provinceRepository->findByCondition([['code_name', '=', $locationId]], false, ['districts']);
                    }
                    $html = ($province) ? $this->renderHtml($province->districts) : '<option value="0">[Chọn Quận/Huyện]</option>';
                } else if (str_contains($target, 'wards')) {
                    $district = $this->districtRepository->findByCondition([['code', '=', $locationId]], false, ['wards']);
                    if (!$district) {
                        $district = $this->districtRepository->findByCondition([['code_name', '=', $locationId]], false, ['wards']);
                    }
                    $html = ($district) ? $this->renderHtml($district->wards, '[Chọn Phường/Xã]') : '<option value="0">[Chọn Phường/Xã]</option>';
                }
            }
        } else {
            if ($target == 'province') {
                $html = $this->renderJsonHtml($get, $source);
            } else if (($get['data']['location_id'] ?? 0) == 0 || empty($target)) {
                if (str_contains($target, 'districts')) {
                    $html = '<option value="0">[Chọn Quận/Huyện]</option>';
                } else if (str_contains($target, 'wards')) {
                    $html = '<option value="0">[Chọn Phường/Xã]</option>';
                }
            } else {
                $html = $this->renderJsonHtml($get, $source);
            }
        }

        return response()->json(['html' => $html]);
    }

    private function renderJsonHtml($get, $source)
    {
        $filePath = resource_path('json/vie_address_' . $source . '_1_7.json');
        if (!file_exists($filePath)) return '<option value="0">File not found</option>';

        $data = json_decode(file_get_contents($filePath), true);
        $html = '';
        $target = $get['target'] ?? '';
        $locationId = $get['data']['location_id'] ?? 0;

        $checkMatch = function ($itemCodename, $locationId) use (&$checkMatch) {
            if ($itemCodename == $locationId) return true;
            $locationIdStr = (string)$locationId;
            
            // Danh sách các tiền tố cần xử lý
            $prefixes = ['thanh_pho_', 'tinh_', 'quan_', 'huyen_', 'thi_xa_', 'phuong_', 'xa_'];
            
            // Thử thêm tiền tố vào locationId để so sánh với itemCodename
            foreach ($prefixes as $p) {
                if ($p . $locationIdStr == $itemCodename) return true;
            }
            
            // Thử xóa tiền tố khỏi itemCodename để so sánh với locationIdStr
            $cleanCodename = str_replace($prefixes, '', $itemCodename);
            $cleanLocationId = str_replace($prefixes, '', $locationIdStr);
            if ($cleanCodename == $cleanLocationId) return true;

            return false;
        };

        if ($source == 'before') {
            // before has 3 levels: province -> districts -> wards
            if ($target == 'province') {
                $html = '<option value="0">Chọn Tỉnh/TP</option>';
                foreach ($data as $province) {
                    $val = $province['codename'] ?? $province['code'];
                    $html .= '<option value="' . $val . '">' . $province['name'] . '</option>';
                }
            } else if (str_contains($target, 'districts')) {
                $html = '<option value="0">[Chọn Quận/Huyện]</option>';
                foreach ($data as $province) {
                    if ($province['code'] == $locationId || $checkMatch($province['codename'], $locationId)) {
                        foreach ($province['districts'] as $district) {
                            $val = $district['codename'] ?? $district['code'];
                            $html .= '<option value="' . $val . '">' . $district['name'] . '</option>';
                        }
                        break;
                    }
                }
            } else if (str_contains($target, 'wards')) {
                $html = '<option value="0">[Chọn Phường/Xã]</option>';
                foreach ($data as $province) {
                    foreach ($province['districts'] as $district) {
                        if ($district['code'] == $locationId || $checkMatch($district['codename'], $locationId)) {
                            foreach ($district['wards'] as $ward) {
                                $val = $ward['codename'] ?? $ward['code'];
                                $html .= '<option value="' . $val . '">' . $ward['name'] . '</option>';
                            }
                            break 2;
                        }
                    }
                }
            }
        } else if ($source == 'after') {
            // after has 2 levels: province -> wards
            if ($target == 'province') {
                $html = '<option value="0">Chọn Tỉnh/TP</option>';
                foreach ($data as $province) {
                    $val = $province['codename'] ?? $province['code'];
                    $html .= '<option value="' . $val . '">' . $province['name'] . '</option>';
                }
            } else if (str_contains($target, 'wards')) {
                $html = '<option value="0">[Chọn Phường/Xã]</option>';
                foreach ($data as $province) {
                    if ($province['code'] == $locationId || $checkMatch($province['codename'], $locationId)) {
                        foreach ($province['wards'] as $ward) {
                            $val = $ward['codename'] ?? $ward['code'];
                            $html .= '<option value="' . $val . '">' . $ward['name'] . '</option>';
                        }
                        break;
                    }
                }
            }
        }
        return $html;
    }

    public function renderHtml($districts, $root = '[Chọn Quận/Huyện]')
    {
        $html = '<option value="0">' . $root . '</option>';
        foreach ($districts as $district) {
            // Ưu tiên dùng code_name (chuỗi) để đồng bộ với cách lưu của sếp
            $value = $district->code_name ?? $district->code;
            $html .= '<option value="' . $value . '">' . $district->name . '</option>';
        }
        return $html;
    }
}
