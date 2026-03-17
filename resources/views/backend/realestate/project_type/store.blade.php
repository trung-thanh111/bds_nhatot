@php
    $url =
        $config['method'] == 'create'
            ? route('realestate.project_type.store')
            : route('realestate.project_type.update', $projectType->id);
@endphp
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
<form action="{{ $url }}" method="post" class="box">
    @csrf
    <div class="wrapper wrapper-content animated fadeInRight">
        <div class="row">
            <div class="col-lg-5">
                <div class="panel-head">
                    <div class="panel-title">Thông tin chung</div>
                    <div class="panel-description">
                        <p>Nhập thông tin chung của loại hình BĐS</p>
                        <p>Lưu ý: Những trường đánh dấu <span class="text-danger">(*)</span> là bắt buộc</p>
                    </div>
                </div>
            </div>
            <div class="col-lg-7">
                <div class="ibox">
                    <div class="ibox-content">
                        <div class="row mb15">
                            <div class="col-lg-6">
                                <div class="form-row">
                                    <label for="" class="control-label text-left">Nhóm BĐS <span
                                            class="text-danger">(*)</span></label>
                                    <select name="group_id" class="form-control setupSelect2">
                                        <option value="">[Chọn Nhóm BĐS]</option>
                                        @foreach ($propertyGroups as $group)
                                            <option
                                                {{ $group->id == old('group_id', $projectType->group_id ?? '') ? 'selected' : '' }}
                                                value="{{ $group->id }}">{{ $group->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="form-row">
                                    <label for="" class="control-label text-left">Giao dịch mặc định</label>
                                    <select name="transaction_type" class="form-control setupSelect2">
                                        <option
                                            {{ old('transaction_type', $projectType->transaction_type ?? '') == 'both' ? 'selected' : '' }}
                                            value="both">Cả hai</option>
                                        <option
                                            {{ old('transaction_type', $projectType->transaction_type ?? '') == 'sale' ? 'selected' : '' }}
                                            value="sale">Bán</option>
                                        <option
                                            {{ old('transaction_type', $projectType->transaction_type ?? '') == 'rent' ? 'selected' : '' }}
                                            value="rent">Cho thuê</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="row mb15">
                            <div class="col-lg-6">
                                <div class="form-row">
                                    <label for="" class="control-label text-left">Tên Loại hình <span
                                            class="text-danger">(*)</span></label>
                                    <input type="text" name="name"
                                        value="{{ old('name', $projectType->name ?? '') }}" class="form-control"
                                        placeholder="" autocomplete="off">
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="form-row">
                                    <label for="" class="control-label text-left">Mã Loại hình <span
                                            class="text-danger">(*)</span></label>
                                    <input type="text" name="code"
                                        value="{{ old('code', $projectType->code ?? '') }}" class="form-control"
                                        placeholder="" autocomplete="off">
                                </div>
                            </div>
                        </div>
                        <div class="row mb15">
                            <div class="col-lg-6">
                                <div class="form-row">
                                    <label for="" class="control-label text-left">Tên ngắn (Badge)</label>
                                    <input type="text" name="name_short"
                                        value="{{ old('name_short', $projectType->name_short ?? '') }}"
                                        class="form-control" placeholder="Ví dụ: Chung cư" autocomplete="off">
                                </div>
                            </div>
                            <div class="col-lg-3">
                                <div class="form-row">
                                    <label for="" class="control-label text-left">Thứ tự</label>
                                    <input type="text" name="sort_order"
                                        value="{{ old('sort_order', $projectType->sort_order ?? 0) }}"
                                        class="form-control text-right" placeholder="" autocomplete="off">
                                </div>
                            </div>
                            <div class="col-lg-3">
                                <div class="form-row">
                                    <label for="" class="control-label text-left">Tình trạng</label>
                                    <select name="publish" class="form-control setupSelect2">
                                        @foreach (config('apps.general.publish') as $key => $val)
                                            <option
                                                {{ $key == old('publish', isset($projectType->publish) ? $projectType->publish : '') ? 'selected' : '' }}
                                                value="{{ $key }}">{{ $val }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @include('backend.dashboard.component.button')
    </div>
</form>
