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
$url = ($config['method'] == 'create') ? route('property_facility.store') : route('property_facility.update', $record->id);
@endphp
<form action="{{ $url }}" method="post" class="box">
    @csrf
    <div class="wrapper wrapper-content animated fadeInRight">
        <div class="row">
            <div class="col-lg-8">
                <div class="ibox">
                    <div class="ibox-title">
                        <h5>Thông tin tiện ích</h5>
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
                            <div class="col-lg-12">
                                <div class="form-row">
                                    <label for="" class="control-label text-left">Tên tiện ích <span class="text-danger">(*)</span></label>
                                    <input
                                        type="text"
                                        name="name"
                                        value="{{ old('name', ($record->name) ?? '' ) }}"
                                        class="form-control"
                                        placeholder="Ví dụ: Bể bơi vô cực, Gym, Spa..."
                                        autocomplete="off">
                                </div>
                            </div>
                        </div>
                        <div class="row mb15">
                            <div class="col-lg-12">
                                <div class="form-row">
                                    <label for="" class="control-label text-left">Icon (SVG / Class) <span class="text-danger">(*)</span></label>
                                    <input
                                        type="text"
                                        name="icon"
                                        value="{{ old('icon', ($record->icon) ?? '' ) }}"
                                        class="form-control"
                                        placeholder="Ví dụ: fa fa-swimming-pool">
                                </div>
                            </div>
                        </div>
                        <div class="row mb15">
                            <div class="col-lg-12">
                                <div class="form-row">
                                    <label for="" class="control-label text-left">Mô tả chi tiết</label>
                                    <textarea name="description" class="form-control" rows="4">{{ old('description', $record->description ?? '') }}</textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-4">
                <div class="ibox">
                    <div class="ibox-title">
                        <h5>Ảnh minh họa & Cấu hình</h5>
                    </div>
                    <div class="ibox-content">
                        <div class="row mb15">
                            <div class="col-lg-12">
                                <div class="form-row text-center">
                                    <label for="" class="control-label text-left">Ảnh tiện ích</label>
                                    <div class="form-row">
                                        <span class="image img-cover image-target"><img src="{{ old('image', ($record->image) ?? '' ) ? old('image', ($record->image) ?? '') :  'backend/img/not-found.jpg' }}" alt=""></span>
                                        <input type="hidden" name="image" value="{{ old('image', $record->image ?? '') }}">
                                    </div>
                                    <small class="text-muted mt10 block">Click để chọn ảnh</small>
                                </div>
                            </div>
                        </div>
                        <div class="row mb15">
                            <div class="col-lg-12">
                                <div class="form-row">
                                    <label for="" class="control-label text-left">Thứ tự</label>
                                    <input type="number" name="sort_order" value="{{ old('sort_order', $record->sort_order ?? 0) }}" class="form-control">
                                </div>
                            </div>
                        </div>
                        <div class="row mb15">
                            <div class="col-lg-12">
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
            </div>
        </div>
        <div class="text-right mb15">
            <button class="btn btn-primary" type="submit" name="send" value="send">Lưu lại</button>
        </div>
    </div>
</form>