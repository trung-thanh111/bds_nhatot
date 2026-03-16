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
$url = ($config['method'] == 'create') ? route('floorplan.store') : route('floorplan.update', $record->id);
@endphp
<form action="{{ $url }}" method="post" class="box">
    @csrf
    <div class="wrapper wrapper-content animated fadeInRight">
        <div class="row">
            <div class="col-lg-8">
                <div class="ibox">
                    <div class="ibox-title">
                        <h5>Thông tin mặt bằng</h5>
                    </div>
                    <div class="ibox-content">
                        <div class="row mb15">
                            <div class="col-lg-12">
                                <div class="form-row">
                                    <label for="" class="control-label text-left">Bất động sản <span class="text-danger">(*)</span></label>
                                    <select name="property_id" class="form-control setupSelect2">
                                        <option value="">[Chọn Bất động sản]</option>
                                        @foreach($properties as $property)
                                        <option {{ $property->id == old('property_id', (isset($record->property_id)) ? $record->property_id : '') ? 'selected' : '' }} value="{{ $property->id }}">{{ $property->title }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="row mb15">
                            <div class="col-lg-6">
                                <div class="form-row">
                                    <label for="" class="control-label text-left">Số tầng <span class="text-danger">(*)</span></label>
                                    <input
                                        type="number"
                                        name="floor_number"
                                        value="{{ old('floor_number', ($record->floor_number) ?? '' ) }}"
                                        class="form-control"
                                        placeholder="Ví dụ: 1, 2, 3...">
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="form-row">
                                    <label for="" class="control-label text-left">Nhãn hiển thị <span class="text-danger">(*)</span></label>
                                    <input
                                        type="text"
                                        name="floor_label"
                                        value="{{ old('floor_label', ($record->floor_label) ?? '' ) }}"
                                        class="form-control"
                                        placeholder="Ví dụ: Tầng Trệt, Tầng 1, Sân Thượng...">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-4">
                <div class="ibox">
                    <div class="ibox-title">
                        <h5>Ảnh sơ đồ mặt bằng</h5>
                    </div>
                    <div class="ibox-content text-center">
                        <div class="form-row">
                            <span class="image img-cover image-target"><img src="{{ old('plan_image', ($record->plan_image) ?? '' ) ? old('plan_image', ($record->plan_image) ?? '') :  'backend/img/not-found.jpg' }}" alt=""></span>
                            <input type="hidden" name="plan_image" value="{{ old('plan_image', $record->plan_image ?? '') }}">
                        </div>
                        <small class="text-muted mt10 block">Click để chọn ảnh sơ đồ</small>
                    </div>
                </div>
                <div class="ibox">
                    <div class="ibox-title">
                        <h5>Cấu hình</h5>
                    </div>
                    <div class="ibox-content">
                        <div class="form-row">
                            <label for="" class="control-label text-left">Trạng thái</label>
                            <select name="publish" class="form-control setupSelect2">
                                @foreach(__('messages.publish') as $key => $val)
                                <option {{ $key == old('publish', (isset($record->publish)) ? $record->publish : '2') ? 'selected' : '' }} value="{{ $key }}">{{ $val }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="text-right mb15">
            <button class="btn btn-primary" type="submit" name="send" value="send">Lưu lại</button>
        </div>
    </div>
</form>