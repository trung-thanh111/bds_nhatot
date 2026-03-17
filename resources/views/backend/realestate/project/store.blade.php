@include('backend.dashboard.component.breadcrumb', ['title' => $config['seo'][$config['method']]['title']])
@if ($errors->any())
    <div class="alert alert-danger">
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif
@php
    $url =
        $config['method'] == 'create'
            ? route('realestate.project.store')
            : route('realestate.project.update', $project->id);
@endphp
<form action="{{ $url }}" method="post" class="box">
    @csrf
    <div class="wrapper wrapper-content animated fadeInRight">
        <div class="row">
            <div class="col-lg-9">
                <div class="ibox">
                    <div class="ibox-title">
                        <h5>Thông tin chung</h5>
                    </div>
                    <div class="ibox-content">
                        <div class="row mb15">
                            <div class="col-lg-12">
                                <div class="form-row">
                                    <label for="" class="control-label text-left">Tiêu đề<span
                                            class="text-danger">(*)</span></label>
                                    <input type="text" name="name" value="{{ old('name', $project->name ?? '') }}"
                                        class="form-control title" placeholder="" autocomplete="off">
                                    <div class="mt5">
                                        <span class="text-primary font-bold">Đường dẫn: </span>
                                        <span class="slug-preview text-primary">{{ config('app.url') }}/<span
                                                id="slug-text">{{ old('slug', $project->slug ?? '') }}</span>{{ config('apps.general.suffix') }}</span>
                                        <input type="hidden" name="slug" class="slug"
                                            value="{{ old('slug', $project->slug ?? '') }}">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row mb15">
                            <div class="col-lg-12">
                                <div class="form-row">
                                    <label for="" class="control-label text-left">Mô tả ngắn</label>
                                    <textarea name="summary" class="form-control ck-editor" id="summary" data-height="150">{{ old('summary', $project->summary ?? '') }}</textarea>
                                </div>
                            </div>
                        </div>
                        <div class="row mb15">
                            <div class="col-lg-12">
                                <div class="form-row">
                                    <label for="" class="control-label text-left">Nội dung chi tiết</label>
                                    <textarea name="description" class="form-control ck-editor" id="description" data-height="300">{{ old('description', $project->description ?? '') }}</textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                @include('backend.dashboard.component.album', ['model' => $project ?? null])

                <div class="ibox">
                    <div class="ibox-title">
                        <h5>Vị trí bất động sản</h5>
                    </div>
                    <div class="ibox-content">
                        <div class="row mb15">
                            <div class="col-lg-12">
                                <label class="control-label text-navy">1. Địa chỉ (cũ)</label>
                                <div class="row">
                                    <div class="col-lg-4">
                                        <div class="form-row">
                                            <label class="control-label">Tỉnh / Thành phố <span
                                                    class="text-danger">(*)</span></label>
                                            <select name="province_code"
                                                class="form-control setupSelect2 province location location-old"
                                                data-target="districts-old" data-source="before">
                                                <option value="0">[Chọn Thành Phố]</option>
                                                @foreach ($provinces as $province)
                                                    <option
                                                        {{ old('province_code', $project->province_code ?? '') == ($province->code_name ?? $province->code) ? 'selected' : '' }}
                                                        value="{{ $province->code_name ?? $province->code }}">
                                                        {{ $province->name }}</option>
                                                @endforeach
                                            </select>
                                            <input type="hidden" name="province_name"
                                                value="{{ old('province_name', $project->province_name ?? '') }}">
                                        </div>
                                    </div>
                                    <div class="col-lg-4">
                                        <div class="form-row">
                                            <label class="control-label">Quận / Huyện <span
                                                    class="text-danger">(*)</span></label>
                                            <select name="district_code"
                                                class="form-control districts-old setupSelect2 location location-old"
                                                data-target="wards-old" data-source="before">
                                                <option value="0">[Chọn Quận/Huyện]</option>
                                            </select>
                                            <input type="hidden" name="district_name"
                                                value="{{ old('district_name', $project->district_name ?? '') }}">
                                        </div>
                                    </div>
                                    <div class="col-lg-4">
                                        <div class="form-row">
                                            <label class="control-label">Phường / Xã <span
                                                    class="text-danger">(*)</span></label>
                                            <select name="ward_code"
                                                class="form-control setupSelect2 wards-old location">
                                                <option value="0">[Chọn Phường/Xã]</option>
                                            </select>
                                            <input type="hidden" name="ward_name"
                                                value="{{ old('ward_name', $project->ward_name ?? '') }}">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row mb15">
                            <div class="col-lg-12">
                                <label class="control-label text-navy">2. Địa chỉ (mới)</label>
                                <div class="row">
                                    <div class="col-lg-6">
                                        <div class="form-row">
                                            <label class="control-label">Tỉnh / Thành phố <span
                                                    class="text-danger">(*)</span></label>
                                            <select name="province_new_code"
                                                class="form-control setupSelect2 province-new location location-new"
                                                data-target="wards-new" data-source="after">
                                                <option value="0">[Chọn Thành Phố]</option>
                                                @foreach ($provinces as $province)
                                                    <option
                                                        {{ old('province_new_code', $project->province_new_code ?? '') == ($province->code_name ?? $province->code) ? 'selected' : '' }}
                                                        value="{{ $province->code_name ?? $province->code }}">
                                                        {{ $province->name }}</option>
                                                @endforeach
                                            </select>
                                            <input type="hidden" name="province_new_name"
                                                value="{{ old('province_new_name', $project->province_new_name ?? '') }}">
                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <div class="form-row">
                                            <label class="control-label">Phường / Xã <span
                                                    class="text-danger">(*)</span></label>
                                            <select name="ward_new_code"
                                                class="form-control setupSelect2 wards-new location">
                                                <option value="0">[Chọn Phường/Xã]</option>
                                            </select>
                                            <input type="hidden" name="ward_new_name"
                                                value="{{ old('ward_new_name', $project->ward_new_name ?? '') }}">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row mb15">
                            <div class="col-lg-12">
                                <div class="form-row">
                                    <label class="control-label">Địa chỉ</label>
                                    <input type="text" name="address"
                                        value="{{ old('address', $project->address ?? '') }}" class="form-control"
                                        placeholder="Số nhà, tên đường...">
                                </div>
                            </div>
                        </div>
                        <div class="row mb15">
                            <div class="col-lg-12">
                                <div class="form-row">
                                    <label class="control-label">Nhúng Map (Iframe)</label>
                                    <textarea name="iframe_map" class="form-control" rows="3" placeholder="Dán mã nhúng Google Map vào đây...">{{ old('iframe_map', $project->iframe_map ?? '') }}</textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="ibox">
                    <div class="ibox-title">
                        <h5>Thông tin chi tiết</h5>
                    </div>
                    <div class="ibox-content">
                        <div class="row mb15">
                            <div class="col-lg-4">
                                <div class="form-row">
                                    <label class="control-label">Giá</label>
                                    <input type="text" name="price"
                                        value="{{ old('price', isset($project->price) ? number_format($project->price, 0, ',', '.') : '') }}"
                                        class="form-control int text-right" placeholder="0">
                                </div>
                            </div>
                            <div class="col-lg-4">
                                <div class="form-row">
                                    <label class="control-label">Đơn vị</label>
                                    <select name="price_unit" class="form-control setupSelect2">
                                        @foreach (\App\Enums\RealEstate\PriceUnitEnum::cases() as $unit)
                                            <option
                                                {{ old('price_unit', isset($project->price_unit) ? $project->price_unit->value : '') == $unit->value ? 'selected' : '' }}
                                                value="{{ $unit->value }}">
                                                {{ $unit->description() }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-lg-4">
                                <div class="form-row">
                                    <label class="control-label">Diện tích (m²)</label>
                                    <input type="text" name="area"
                                        value="{{ old('area', $project->area ?? '') }}" class="form-control"
                                        placeholder="0">
                                </div>
                            </div>
                        </div>

                        <div id="dynamic-attributes-container">
                            {{-- Row 1: Area Use & Area Land --}}
                            <div class="row mb15 attr-group apartment-group house-group land-group commercial-group project-group"
                                style="display:none;">
                                <div class="col-lg-6 attr-group apartment-group commercial-group project-group">
                                    <div class="form-row">
                                        <label class="control-label">Diện tích sử dụng (m²)</label>
                                        <input type="text" name="area_use"
                                            value="{{ old('area_use', $project->area_use ?? '') }}"
                                            class="form-control" placeholder="0">
                                    </div>
                                </div>
                                <div class="col-lg-6 attr-group house-group land-group project-group">
                                    <div class="form-row">
                                        <label class="control-label">Diện tích đất (m²)</label>
                                        <input type="text" name="area_land"
                                            value="{{ old('area_land', $project->area_land ?? '') }}"
                                            class="form-control" placeholder="0">
                                    </div>
                                </div>
                            </div>

                            {{-- Extra Fields cho Apartment --}}
                            <div class="row mb15 attr-group apartment-group" style="display:none;">
                                <div class="col-lg-4">
                                    <div class="form-row">
                                        <label class="control-label">Block / Tòa nhà</label>
                                        <input type="text" name="extra_fields[block_name]"
                                            value="{{ old('extra_fields.block_name', $project->extra_fields['block_name'] ?? '') }}"
                                            class="form-control">
                                    </div>
                                </div>
                                <div class="col-lg-4">
                                    <div class="form-row">
                                        <label class="control-label">Mã căn hộ</label>
                                        <input type="text" name="extra_fields[apartment_code]"
                                            value="{{ old('extra_fields.apartment_code', $project->extra_fields['apartment_code'] ?? '') }}"
                                            class="form-control">
                                    </div>
                                </div>
                                <div class="col-lg-4">
                                    <div class="form-row">
                                        <label class="control-label">Phí quản lý</label>
                                        <input type="text" name="extra_fields[management_fee]"
                                            value="{{ old('extra_fields.management_fee', $project->extra_fields['management_fee'] ?? '') }}"
                                            class="form-control int">
                                    </div>
                                </div>
                            </div>

                            {{-- Row 2: Length & Width --}}
                            <div class="row mb15 attr-group house-group land-group commercial-group project-group"
                                style="display:none;">
                                <div class="col-lg-6">
                                    <div class="form-row">
                                        <label class="control-label">Chiều dài (m)</label>
                                        <input type="text" name="length"
                                            value="{{ old('length', $project->length ?? '') }}" class="form-control"
                                            placeholder="0">
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="form-row">
                                        <label class="control-label">Chiều rộng (m)</label>
                                        <input type="text" name="width"
                                            value="{{ old('width', $project->width ?? '') }}" class="form-control"
                                            placeholder="0">
                                    </div>
                                </div>
                            </div>

                            <div class="row mb15 attr-group house-group land-group" style="display:none;">
                                <div class="col-lg-6">
                                    <div class="form-row">
                                        <label class="control-label">Loại đường</label>
                                        <select name="extra_fields[road_type]" class="form-control setupSelect2">
                                            <option value="">[Chọn loại đường]</option>
                                            <option value="nhua"
                                                {{ old('extra_fields.road_type', $project->extra_fields['road_type'] ?? '') == 'nhua' ? 'selected' : '' }}>
                                                Đường nhựa</option>
                                            <option value="be_tong"
                                                {{ old('extra_fields.road_type', $project->extra_fields['road_type'] ?? '') == 'be_tong' ? 'selected' : '' }}>
                                                Bê tông</option>
                                            <option value="dat"
                                                {{ old('extra_fields.road_type', $project->extra_fields['road_type'] ?? '') == 'dat' ? 'selected' : '' }}>
                                                Đường đất</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="form-row">
                                        <label class="control-label">Độ rộng đường (m)</label>
                                        <input type="text" name="extra_fields[road_width]"
                                            value="{{ old('extra_fields.road_width', $project->extra_fields['road_width'] ?? '') }}"
                                            class="form-control">
                                    </div>
                                </div>
                            </div>

                            {{-- Row 3: Bedrooms & Bathrooms --}}
                            <div class="row mb15 attr-group apartment-group house-group room-group project-group"
                                style="display:none;">
                                <div class="col-lg-6">
                                    <div class="form-row">
                                        <label class="control-label">Số phòng ngủ</label>
                                        <input type="number" name="bedrooms"
                                            value="{{ old('bedrooms', $project->bedrooms ?? '') }}"
                                            class="form-control">
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="form-row">
                                        <label class="control-label">Số phòng tắm</label>
                                        <input type="number" name="bathrooms"
                                            value="{{ old('bathrooms', $project->bathrooms ?? '') }}"
                                            class="form-control">
                                    </div>
                                </div>
                            </div>

                            {{-- Row 4: Floors & Floor Number --}}
                            <div class="row mb15 attr-group apartment-group house-group commercial-group room-group project-group"
                                style="display:none;">
                                <div class="col-lg-6">
                                    <div class="form-row">
                                        <label class="control-label">Tổng số tầng</label>
                                        <input type="number" name="floors"
                                            value="{{ old('floors', $project->floors ?? '') }}" class="form-control">
                                    </div>
                                </div>
                                <div
                                    class="col-lg-6 attr-group apartment-group commercial-group room-group project-group">
                                    <div class="form-row">
                                        <label class="control-label">Tầng số mấy</label>
                                        <input type="number" name="floor_number"
                                            value="{{ old('floor_number', $project->floor_number ?? '') }}"
                                            class="form-control">
                                    </div>
                                </div>
                            </div>

                            <div class="row mb15 attr-group commercial-group" style="display:none;">
                                <div class="col-lg-4">
                                    <div class="form-row">
                                        <label class="control-label">Loại không gian</label>
                                        <input type="text" name="extra_fields[space_type]"
                                            value="{{ old('extra_fields.space_type', $project->extra_fields['space_type'] ?? '') }}"
                                            class="form-control" placeholder="VP, Shop, Kho...">
                                    </div>
                                </div>
                                <div class="col-lg-4">
                                    <div class="form-row">
                                        <label class="control-label">Chiều cao trần (m)</label>
                                        <input type="text" name="extra_fields[height_clear]"
                                            value="{{ old('extra_fields.height_clear', $project->extra_fields['height_clear'] ?? '') }}"
                                            class="form-control">
                                    </div>
                                </div>
                                <div class="col-lg-4">
                                    <div class="form-row">
                                        <label class="control-label">Tiền cọc (tháng)</label>
                                        <input type="number" name="extra_fields[security_deposit]"
                                            value="{{ old('extra_fields.security_deposit', $project->extra_fields['security_deposit'] ?? '') }}"
                                            class="form-control">
                                    </div>
                                </div>
                            </div>

                            <div class="row mb15 attr-group room-group" style="display:none;">
                                <div class="col-lg-4">
                                    <div class="form-row">
                                        <label class="control-label">Giá điện</label>
                                        <input type="text" name="extra_fields[electricity_price]"
                                            value="{{ old('extra_fields.electricity_price', $project->extra_fields['electricity_price'] ?? '') }}"
                                            class="form-control">
                                    </div>
                                </div>
                                <div class="col-lg-4">
                                    <div class="form-row">
                                        <label class="control-label">Giá nước</label>
                                        <input type="text" name="extra_fields[water_price]"
                                            value="{{ old('extra_fields.water_price', $project->extra_fields['water_price'] ?? '') }}"
                                            class="form-control">
                                    </div>
                                </div>
                                <div class="col-lg-4">
                                    <div class="form-row">
                                        <label class="control-label">Đặt cọc (tháng)</label>
                                        <input type="number" name="extra_fields[deposit_months]"
                                            value="{{ old('extra_fields.deposit_months', $project->extra_fields['deposit_months'] ?? '') }}"
                                            class="form-control">
                                    </div>
                                </div>
                            </div>

                            {{-- Row 5: Direction & Balcony Direction --}}
                            <div class="row mb15 attr-group apartment-group house-group land-group commercial-group project-group"
                                style="display:none;">
                                <div class="col-lg-6">
                                    <div class="form-row">
                                        <label class="control-label">Hướng nhà/đất</label>
                                        <select name="direction" class="form-control setupSelect2">
                                            <option value="">[Chọn hướng]</option>
                                            @foreach (\App\Enums\RealEstate\DirectionEnum::cases() as $dir)
                                                <option
                                                    {{ old('direction', isset($project->direction) ? $project->direction->value : '') == $dir->value ? 'selected' : '' }}
                                                    value="{{ $dir->value }}">
                                                    {{ $dir->description() }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-lg-6 attr-group apartment-group project-group">
                                    <div class="form-row">
                                        <label class="control-label">Hướng ban công</label>
                                        <select name="balcony_direction" class="form-control setupSelect2">
                                            <option value="">[Chọn hướng]</option>
                                            @foreach (\App\Enums\RealEstate\DirectionEnum::cases() as $dir)
                                                <option
                                                    {{ old('balcony_direction', isset($project->balcony_direction) ? $project->balcony_direction->value : '') == $dir->value ? 'selected' : '' }}
                                                    value="{{ $dir->value }}">
                                                    {{ $dir->description() }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>

                            {{-- Row 6: Legal & Furniture --}}
                            <div class="row mb15">
                                <div class="col-lg-6">
                                    <div class="form-row">
                                        <label class="control-label">Pháp lý</label>
                                        <select name="legal_status" class="form-control setupSelect2">
                                            <option value="">[Chọn pháp lý]</option>
                                            @foreach (\App\Enums\RealEstate\LegalStatusEnum::cases() as $legal)
                                                <option
                                                    {{ old('legal_status', isset($project->legal_status) ? $project->legal_status->value : '') == $legal->value ? 'selected' : '' }}
                                                    value="{{ $legal->value }}">
                                                    {{ $legal->description() }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-lg-6 attr-group apartment-group house-group room-group project-group commercial-group"
                                    style="display:none;">
                                    <div class="form-row">
                                        <label class="control-label">Nội thất</label>
                                        <select name="furniture_status" class="form-control setupSelect2">
                                            <option value="">[Chọn nội thất]</option>
                                            @foreach (\App\Enums\RealEstate\FurnitureStatusEnum::cases() as $furniture)
                                                <option
                                                    {{ old('furniture_status', isset($project->furniture_status) ? $project->furniture_status->value : '') == $furniture->value ? 'selected' : '' }}
                                                    value="{{ $furniture->value }}">
                                                    {{ $furniture->description() }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="ibox attr-group apartment-group house-group commercial-group project-group"
                    style="display:none;">
                    <div class="ibox-title">
                        <h5>Tiện ích BĐS</h5>
                    </div>
                    <div class="ibox-content">
                        <div class="row">
                            @php
                                $amenities = [
                                    'has_elevator' => 'Thang máy',
                                    'has_pool' => 'Hồ bơi',
                                    'has_parking' => 'Chỗ đậu xe',
                                    'has_security' => 'Bảo vệ',
                                    'has_balcony' => 'Ban công',
                                    'has_rooftop' => 'Sân thượng',
                                    'has_basement' => 'Hầm',
                                    'has_gym' => 'Phòng Gym',
                                    'has_ac' => 'Điều hòa',
                                    'has_wifi' => 'Internet/Wifi',
                                ];
                            @endphp
                            @foreach ($amenities as $key => $label)
                                <div class="col-lg-3 mb10">
                                    <div class="form-row">
                                        <label class="fix-label">
                                            <input type="checkbox" name="{{ $key }}" value="1"
                                                {{ old($key, $project->$key ?? 0) == 1 ? 'checked' : '' }}
                                                class="checkBoxItem">
                                            <span class="ml5">{{ $label }}</span>
                                        </label>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
                @include('backend.dashboard.component.seo', ['model' => $project ?? null])
            </div>
            <div class="col-lg-3">
                <div class="ibox">
                    <div class="ibox-title">
                        <h5>Cấu hình danh mục</h5>
                    </div>
                    <div class="ibox-content">
                        <div class="row mb15">
                            <div class="col-lg-12">
                                <div class="form-row">
                                    <label class="control-label">Nhóm BĐS <span class="text-danger">(*)</span></label>
                                    <select name="catalogue_id" class="form-control setupSelect2"
                                        id="project_catalogue_id">
                                        @foreach ($dropdown as $key => $val)
                                            <option
                                                {{ old('catalogue_id', $project->catalogue_id ?? '') == $key ? 'selected' : '' }}
                                                value="{{ $key }}">{{ $val }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                        <input type="hidden" name="transaction_type" id="transaction_type"
                            value="{{ old('transaction_type', $project->transaction_type->value ?? 'sale') }}"
                            readonly>
                        <input type="hidden" name="type_code" id="type_code"
                            value="{{ old('type_code', $project->type_code ?? '') }}" readonly>
                    </div>
                </div>

                <div class="ibox">
                    <div class="ibox-title">
                        <h5>Ảnh đại diện</h5>
                    </div>
                    <div class="ibox-content text-center">
                        <div class="form-row">
                            <span class="image img-cover image-target">
                                <img src="{{ old('image', $project->image ?? 'backend/img/not-found.jpg') }}"
                                    alt="" class="img-scaledown">
                            </span>
                            <input type="hidden" name="image" value="{{ old('image', $project->image ?? '') }}">
                        </div>
                    </div>
                </div>

                <div class="ibox">
                    <div class="ibox-title">
                        <h5>Cấu hình chung</h5>
                    </div>
                    <div class="ibox-content">
                        <div class="row mb15">
                            <div class="col-lg-12">
                                <div class="form-row">
                                    <label class="control-label">Mã tin (Tự động nếu trống)</label>
                                    <input type="text" name="code"
                                        value="{{ old('code', $project->code ?? '') }}" class="form-control">
                                </div>
                            </div>
                        </div>
                        <div class="row mb15">
                            <div class="col-lg-12">
                                <div class="form-row">
                                    <label class="control-label">Trạng thái</label>
                                    <select name="publish" class="form-control setupSelect2">
                                        <option value="1"
                                            {{ old('publish', $project->publish ?? '') == 1 ? 'selected' : '' }}>Chưa
                                            xuất bản</option>
                                        <option value="2"
                                            {{ old('publish', $project->publish ?? '') == 2 ? 'selected' : '' }}>Xuất
                                            bản</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="hr-line-dashed"></div>
                        <div class="row mb10">
                            <div class="col-lg-12">
                                <div class="form-row">
                                    <label class="fix-label">
                                        <input type="checkbox" name="is_featured" value="1"
                                            {{ old('is_featured', $project->is_featured ?? 0) == 1 ? 'checked' : '' }}
                                            class="checkBoxItem">
                                        <span class="ml5 font-bold text-navy">Tin nổi bật</span>
                                    </label>
                                </div>
                            </div>
                        </div>
                        <div class="row mb10">
                            <div class="col-lg-12">
                                <div class="form-row">
                                    <label class="fix-label">
                                        <input type="checkbox" name="is_hot" value="1"
                                            {{ old('is_hot', $project->is_hot ?? 0) == 1 ? 'checked' : '' }}
                                            class="checkBoxItem">
                                        <span class="ml5 font-bold text-danger">Tin HOT</span>
                                    </label>
                                </div>
                            </div>
                        </div>
                        <div class="row mb10">
                            <div class="col-lg-12">
                                <div class="form-row">
                                    <label class="fix-label">
                                        <input type="checkbox" name="is_urgent" value="1"
                                            {{ old('is_urgent', $project->is_urgent ?? 0) == 1 ? 'checked' : '' }}
                                            class="checkBoxItem">
                                        <span class="ml5 font-bold text-warning">Cần bán/cho thuê gấp</span>
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="ibox">
                    <div class="ibox-title">
                        <h5>Truyền thông & 360</h5>
                    </div>
                    <div class="ibox-content">
                        <div class="row mb15">
                            <div class="col-lg-12">
                                <div class="form-row">
                                    <label class="control-label">URL Video (Youtube)</label>
                                    <input type="text" name="video_url"
                                        value="{{ old('video_url', $project->video_url ?? '') }}"
                                        class="form-control" placeholder="https://www.youtube.com/watch?v=...">
                                </div>
                            </div>
                        </div>
                        <div class="row mb15">
                            <div class="col-lg-12">
                                <div class="form-row">
                                    <label class="control-label">Mã nhúng Video (Nếu có)</label>
                                    <textarea name="video_embed" class="form-control" rows="2">{{ old('video_embed', $project->video_embed ?? '') }}</textarea>
                                </div>
                            </div>
                        </div>
                        <div class="row mb15">
                            <div class="col-lg-12">
                                <div class="form-row">
                                    <label class="control-label">URL Tour 360 / Virtual Tour</label>
                                    <input type="text" name="virtual_tour_url"
                                        value="{{ old('virtual_tour_url', $project->virtual_tour_url ?? '') }}"
                                        class="form-control" placeholder="Link 360 độ...">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>


    @include('backend.dashboard.component.button')
</form>

<script>
    var province_id = '{{ isset($project->province_code) ? $project->province_code : old('province_code') }}'
    var district_id = '{{ isset($project->district_code) ? $project->district_code : old('district_code') }}'
    var ward_id = '{{ isset($project->ward_code) ? $project->ward_code : old('ward_code') }}'

    var province_new_id =
        '{{ isset($project->province_new_code) ? $project->province_new_code : old('province_new_code') }}'
    var district_new_id =
        '{{ isset($project->district_new_code) ? $project->district_new_code : old('district_new_code') }}'
    var ward_new_id = '{{ isset($project->ward_new_code) ? $project->ward_new_code : old('ward_new_code') }}'
</script>
