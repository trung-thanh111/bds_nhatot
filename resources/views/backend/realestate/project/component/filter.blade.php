<form action="{{ route('realestate.project.index') }}" method="get">
    <div class="filter-wrapper">
        <div class="uk-flex uk-flex-middle uk-flex-space-between mb20">
            <div class="perpage">
                @php
                    $perpage = request('perpage') ?: 20;
                @endphp
                <div class="uk-flex uk-flex-middle uk-flex-gap-10">
                    <select name="perpage" class="form-control input-sm setupSelect2 perpage ml10">
                        @for ($i = 20; $i <= 200; $i += 20)
                            <option {{ $perpage == $i ? 'selected' : '' }} value="{{ $i }}">
                                {{ $i }} bản ghi</option>
                        @endfor
                    </select>
                </div>
            </div>
            <div class="action">
                <div class="uk-flex uk-flex-middle">
                    <a href="{{ route('realestate.project.create') }}" class="btn btn-danger ml10"><i
                            class="fa fa-plus mr5"></i>Thêm BĐS mới</a>
                </div>
            </div>
        </div>

        <div class="filter-controls-scroller">
            <div class="filter-controls-inner uk-flex uk-flex-middle">
                @php
                    $publish = request('publish');
                    $locationType = request('location_type') ?: '';
                @endphp

                <div class="filter-item">
                    <select name="publish" class="form-control setupSelect2">
                        <option value="0">Trạng thái</option>
                        <option value="1" {{ $publish == 1 ? 'selected' : '' }}>Chưa xuất bản</option>
                        <option value="2" {{ $publish == 2 ? 'selected' : '' }}>Xuất bản</option>
                    </select>
                </div>

                <div class="filter-item w250">
                    <select name="catalogue_id" class="form-control setupSelect2">
                        @foreach ($dropdown as $id => $name)
                            <option value="{{ $id }}"
                                {{ request('catalogue_id') == $id ? 'selected' : '' }}>{{ $name }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="filter-item">
                    <select name="location_type" class="form-control setupSelect2 location-type">
                        <option value="0">Chọn loại địa điểm</option>
                        <option value="new" {{ $locationType == 'new' ? 'selected' : '' }}>Địa chỉ (Mới)</option>
                        <option value="old" {{ $locationType == 'old' ? 'selected' : '' }}>Địa chỉ (Cũ)</option>
                    </select>
                </div>

                <div class="filter-item w200">
                    <select name="province_code" class="form-control setupSelect2 province-filter province"
                        data-target="province" data-source="{{ $locationType == 'old' ? 'before' : 'after' }}">
                        <option value="0">Chọn Tỉnh/TP</option>
                        @foreach ($provinces as $province)
                            <option value="{{ $province->code_name ?? $province->code }}"
                                {{ request('province_code') == ($province->code_name ?? $province->code) ? 'selected' : '' }}>
                                {{ $province->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="filter-item w180">
                    <select name="price_range" class="form-control setupSelect2">
                        <option value="0">Chọn giá</option>
                        <option value="negotiable"
                            {{ request('price_range') == 'negotiable' || request('price_negotiable') ? 'selected' : '' }}>
                            Giá thỏa thuận</option>
                        @foreach ($priceRanges as $key => $val)
                            <option value="{{ $key }}"
                                {{ request('price_range') == $key ? 'selected' : '' }}>{{ $val }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="filter-item w180">
                    <select name="area_range" class="form-control setupSelect2">
                        <option value="0">Chọn diện tích</option>
                        @foreach ($areaRanges as $key => $val)
                            <option value="{{ $key }}"
                                {{ request('area_range') == $key ? 'selected' : '' }}>{{ $val }}</option>
                        @endforeach
                    </select>
                </div>


                <div class="filter-item">
                    <select name="special_status" class="form-control setupSelect2">
                        <option value="">Trạng thái đặc biệt</option>
                        @foreach ($specialStatuses as $key => $val)
                            <option value="{{ $key }}"
                                {{ request('special_status') == $key ? 'selected' : '' }}>{{ $val }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="filter-item">
                    <select name="direction" class="form-control setupSelect2">
                        <option value="">Hướng nhà</option>
                        @foreach ($directions as $key => $val)
                            <option value="{{ $key }}"
                                {{ request('direction') == $key ? 'selected' : '' }}>{{ $val }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="filter-item">
                    <select name="legal_status" class="form-control setupSelect2">
                        <option value="">Pháp lý</option>
                        @foreach ($legalStatuses as $key => $val)
                            <option value="{{ $key }}"
                                {{ request('legal_status') == $key ? 'selected' : '' }}>{{ $val }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="filter-item">
                    <select name="furniture_status" class="form-control setupSelect2">
                        <option value="">Nội thất</option>
                        @foreach ($furnitureStatuses as $key => $val)
                            <option value="{{ $key }}"
                                {{ request('furniture_status') == $key ? 'selected' : '' }}>{{ $val }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="filter-item">
                    <div class="uk-search uk-flex uk-flex-middle">
                        <div class="input-group">
                            <input type="text" name="keyword" value="{{ request('keyword') }}"
                                placeholder="Tên, mã tin..." class="form-control">
                            <span class="input-group-btn">
                                <button type="submit" name="search" value="search"
                                    class="btn btn-primary mb0">Tìm</button>
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
    </div>
    </div>

</form>
