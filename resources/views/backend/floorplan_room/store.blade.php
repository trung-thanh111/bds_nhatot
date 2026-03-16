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
$url = ($config['method'] == 'create') ? route('floorplan_room.store') : route('floorplan_room.update', $record->id);
@endphp
<form action="{{ $url }}" method="post" class="box">
    @csrf
    <div class="wrapper wrapper-content animated fadeInRight">
        <div class="row">
            <div class="col-lg-8">
                <div class="ibox">
                    <div class="ibox-title">
                        <h5>Thông tin phòng</h5>
                    </div>
                    <div class="ibox-content">
                        <div class="row mb15">
                            <div class="col-lg-12">
                                <div class="form-row">
                                    <label for="" class="control-label text-left">Mặt bằng tầng <span class="text-danger">(*)</span></label>
                                    <select name="floorplan_id" class="form-control setupSelect2">
                                        <option value="">[Chọn Mặt bằng]</option>
                                        @foreach($floorplans as $floor)
                                        <option {{ $floor->id == old('floorplan_id', (isset($record->floorplan_id)) ? $record->floorplan_id : '') ? 'selected' : '' }} value="{{ $floor->id }}">{{ $floor->floor_label }} - {{ $floor->properties?->title }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="row mb15">
                            <div class="col-lg-8">
                                <div class="form-row">
                                    <label for="" class="control-label text-left">Tên phòng <span class="text-danger">(*)</span></label>
                                    <input
                                        type="text"
                                        name="room_name"
                                        value="{{ old('room_name', ($record->room_name) ?? '' ) }}"
                                        class="form-control"
                                        placeholder="Ví dụ: Phòng ngủ chính, Phòng khách, Ban công..."
                                        autocomplete="off">
                                </div>
                            </div>
                            <div class="col-lg-4">
                                <div class="form-row">
                                    <label for="" class="control-label text-left">Diện tích (m²) <span class="text-danger">(*)</span></label>
                                    <input
                                        type="number"
                                        step="0.01"
                                        name="area_sqm"
                                        value="{{ old('area_sqm', ($record->area_sqm) ?? '' ) }}"
                                        class="form-control">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-4">
                <div class="ibox">
                    <div class="ibox-title">
                        <h5>Cấu hình</h5>
                    </div>
                    <div class="ibox-content">
                        <div class="row mb15">
                            <div class="col-lg-12">
                                <div class="form-row">
                                    <label for="" class="control-label text-left">Thứ tự</label>
                                    <input type="number" name="sort_order" value="{{ old('sort_order', $record->sort_order ?? 0) }}" class="form-control">
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