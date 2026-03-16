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
$url = ($config['method'] == 'create') ? route('property.store') : route('property.update', $record->id);
@endphp
<form action="{{ $url }}" method="post" class="box">
    @csrf
    <div class="wrapper wrapper-content animated fadeInRight">
        <div class="row">
            <div class="col-lg-9">
                <div class="ibox">
                    <div class="ibox-title">
                        <h5>Thông tin cơ bản</h5>
                    </div>
                    <div class="ibox-content">
                        <div class="row mb15">
                            <div class="col-lg-12">
                                <div class="form-row">
                                    <label for="" class="control-label text-left">Tiêu đề BĐS <span class="text-danger">(*)</span></label>
                                    <input
                                        type="text"
                                        name="title"
                                        value="{{ old('title', ($record->title) ?? '' ) }}"
                                        class="form-control"
                                        placeholder="Ví dụ: Căn hộ cao cấp Vinhomes Central Park"
                                        autocomplete="off">
                                </div>
                            </div>
                        </div>
                        <div class="row mb15">
                            <div class="col-lg-12">
                                <div class="form-row">
                                    <label for="" class="control-label text-left">Đường dẫn (Slug) <span class="text-danger">(*)</span></label>
                                    <input
                                        type="text"
                                        name="slug"
                                        value="{{ old('slug', ($record->slug) ?? '' ) }}"
                                        class="form-control"
                                        placeholder="tu-dong-theo-tieu-de">
                                </div>
                            </div>
                        </div>
                        <div class="row mb15">
                            <div class="col-lg-12">
                                <div class="form-row">
                                    <label for="" class="control-label text-left">Tagline (Slogan ngắn)</label>
                                    <input
                                        type="text"
                                        name="tagline"
                                        value="{{ old('tagline', ($record->tagline) ?? '' ) }}"
                                        class="form-control"
                                        placeholder="Ví dụ: Tận hưởng không gian sống đẳng cấp">
                                </div>
                            </div>
                        </div>
                        <div class="row mb15">
                            <div class="col-lg-12">
                                <div class="form-row">
                                    <label for="" class="control-label text-left">Mô tả ngắn (Hero Section)</label>
                                    <textarea name="description_short" class="form-control" rows="3">{{ old('description_short', $record->description_short ?? '') }}</textarea>
                                </div>
                            </div>
                        </div>
                        <div class="row mb15">
                            <div class="col-lg-12">
                                <div class="form-row">
                                    <label for="" class="control-label text-left">Mô tả chi tiết</label>
                                    <textarea name="description" class="ck-editor" id="ckDescription">{{ old('description', $record->description ?? '') }}</textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="ibox">
                    <div class="ibox-title">
                        <h5>Thông số kỹ thuật & Giá</h5>
                    </div>
                    <div class="ibox-content">
                        <div class="row mb15">
                            <div class="col-lg-4">
                                <div class="form-row">
                                    <label for="" class="control-label text-left">Giá tiền <span class="text-danger">(*)</span></label>
                                    <input type="text" name="price" value="{{ old('price', $record->price ?? '') }}" class="form-control int">
                                </div>
                            </div>
                            <div class="col-lg-2">
                                <div class="form-row">
                                    <label for="" class="control-label text-left">Đơn vị</label>
                                    <select name="price_unit" class="form-control setupSelect2">
                                        <option value="tỷ" {{ old('price_unit', $record->price_unit ?? '') == 'tỷ' ? 'selected' : '' }}>Tỷ</option>
                                        <option value="triệu" {{ old('price_unit', $record->price_unit ?? '') == 'triệu' ? 'selected' : '' }}>Triệu</option>
                                        <option value="usd" {{ old('price_unit', $record->price_unit ?? '') == 'usd' ? 'selected' : '' }}>USD</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-lg-3">
                                <div class="form-row">
                                    <label for="" class="control-label text-left">Diện tích (m²) <span class="text-danger">(*)</span></label>
                                    <input type="text" name="area_sqm" value="{{ old('area_sqm', $record->area_sqm ?? '') }}" class="form-control">
                                </div>
                            </div>
                            <div class="col-lg-3">
                                <div class="form-row">
                                    <label for="" class="control-label text-left">Năm xây dựng</label>
                                    <input type="number" name="year_built" value="{{ old('year_built', $record->year_built ?? '') }}" class="form-control">
                                </div>
                            </div>
                        </div>
                        <div class="row mb15">
                            <div class="col-lg-3">
                                <div class="form-row">
                                    <label for="" class="control-label text-left">Phòng ngủ</label>
                                    <input type="number" name="bedrooms" value="{{ old('bedrooms', $record->bedrooms ?? 0) }}" class="form-control">
                                </div>
                            </div>
                            <div class="col-lg-3">
                                <div class="form-row">
                                    <label for="" class="control-label text-left">Phòng tắm</label>
                                    <input type="number" name="bathrooms" value="{{ old('bathrooms', $record->bathrooms ?? 0) }}" class="form-control">
                                </div>
                            </div>
                            <div class="col-lg-3">
                                <div class="form-row">
                                    <label for="" class="control-label text-left">Chỗ đỗ xe</label>
                                    <input type="number" name="parking_spots" value="{{ old('parking_spots', $record->parking_spots ?? 0) }}" class="form-control">
                                </div>
                            </div>
                            <div class="col-lg-3">
                                <div class="form-row">
                                    <label for="" class="control-label text-left">Số tầng</label>
                                    <input type="number" name="floors" value="{{ old('floors', $record->floors ?? 1) }}" class="form-control">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="ibox">
                    <div class="ibox-title">
                        <h5>Địa chỉ & Vị trí</h5>
                    </div>
                    <div class="ibox-content">
                        <div class="row mb15">
                            <div class="col-lg-12">
                                <div class="form-row">
                                    <label for="" class="control-label text-left">Địa chỉ chi tiết <span class="text-danger">(*)</span></label>
                                    <input type="text" name="address" value="{{ old('address', $record->address ?? '') }}" class="form-control">
                                </div>
                            </div>
                        </div>
                        <div class="row mb15">
                            <div class="col-lg-6">
                                <div class="form-row">
                                    <label for="" class="control-label text-left">Quận / Huyện <span class="text-danger">(*)</span></label>
                                    <input type="text" name="district" value="{{ old('district', $record->district ?? '') }}" class="form-control">
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="form-row">
                                    <label for="" class="control-label text-left">Thành phố / Tỉnh <span class="text-danger">(*)</span></label>
                                    <input type="text" name="city" value="{{ old('city', $record->city ?? '') }}" class="form-control">
                                </div>
                            </div>
                        </div>
                        <div class="row mb15">
                            <div class="col-lg-6">
                                <div class="form-row">
                                    <label for="" class="control-label text-left">Vĩ độ (Latitude)</label>
                                    <input type="text" name="latitude" value="{{ old('latitude', $record->latitude ?? '') }}" class="form-control">
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="form-row">
                                    <label for="" class="control-label text-left">Kinh độ (Longitude)</label>
                                    <input type="text" name="longitude" value="{{ old('longitude', $record->longitude ?? '') }}" class="form-control">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="ibox">
                    <div class="ibox-title">
                        <h5>Cấu hình SEO</h5>
                    </div>
                    <div class="ibox-content">
                        <div class="row mb15">
                            <div class="col-lg-12">
                                <div class="form-row">
                                    <label for="" class="control-label text-left">SEO Title</label>
                                    <input type="text" name="seo_title" value="{{ old('seo_title', $record->seo_title ?? '') }}" class="form-control">
                                </div>
                            </div>
                        </div>
                        <div class="row mb15">
                            <div class="col-lg-12">
                                <div class="form-row">
                                    <label for="" class="control-label text-left">Meta Description</label>
                                    <textarea name="seo_description" class="form-control" rows="3">{{ old('seo_description', $record->seo_description ?? '') }}</textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-3">
                <div class="ibox">
                    <div class="ibox-title">
                        <h5>Hình Ảnh</h5>
                    </div>
                    <div class="ibox-content text-center">
                        <div class="form-row">
                            <span class="image img-cover image-target"><img src="{{ old('image', ($record->image) ?? '' ) ? old('image', ($record->image) ?? '') :  'backend/img/not-found.jpg' }}" alt=""></span>
                            <input type="hidden" name="image" value="{{ old('image', $record->image ?? '') }}">
                        </div>
                        <small class="text-muted mt10 block">Click để chọn ảnh</small>
                    </div>
                </div>

                <div class="ibox">
                    <div class="ibox-title">
                        <h5>Video & Media</h5>
                    </div>
                    <div class="ibox-content">
                        <div class="form-row">
                            <label for="" class="control-label">Video Tour URL (YT)</label>
                            <input type="text" name="video_tour_url" value="{{ old('video_tour_url', $record->video_tour_url ?? '') }}" class="form-control" placeholder="Link YouTube">
                        </div>
                    </div>
                </div>

                <div class="ibox">
                    <div class="ibox-title">
                        <h5>Trạng thái</h5>
                    </div>
                    <div class="ibox-content">
                        <select name="publish" class="form-control setupSelect2">
                            @foreach(__('messages.publish') as $key => $val)
                            <option {{ $key == old('publish', (isset($record->publish)) ? $record->publish : '2') ? 'selected' : '' }} value="{{ $key }}">{{ $val }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>
        </div>
        <div class="text-right mb15 fixed-bottom">
            <button class="btn btn-primary" type="submit" name="send" value="send">Lưu lại</button>
        </div>
    </div>
</form>