@php
    $url =
        $config['method'] == 'create'
            ? route('realestate.project_catalogue.store')
            : route('realestate.project_catalogue.update', $projectCatalogue->id);
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
                        <p>Nhập thông tin chung của nhóm BĐS</p>
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
                                    <label for="" class="control-label text-left">Tên Nhóm BĐS <span
                                            class="text-danger">(*)</span></label>
                                    <input type="text" name="name"
                                        value="{{ old('name', $projectCatalogue->name ?? '') }}"
                                        class="form-control title" placeholder="" autocomplete="off">
                                    <div class="mt5">
                                        <span class="text-primary font-bold">Đường dẫn: </span>
                                        <span class="slug-preview text-primary">{{ config('app.url') }}/<span
                                                id="slug-text">{{ old('slug', $projectCatalogue->slug ?? '') }}</span>{{ config('apps.general.suffix') }}</span>
                                        <input type="hidden" name="slug" class="slug"
                                            value="{{ old('slug', $projectCatalogue->slug ?? '') }}">
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="form-row">
                                    <label for="" class="control-label text-left">Nhóm cha</label>
                                    <select name="parent_id" class="form-control setupSelect2">
                                        @foreach ($dropdown as $key => $val)
                                            <option
                                                {{ $key == old('parent_id', $projectCatalogue->parent_id ?? '') ? 'selected' : '' }}
                                                value="{{ $key }}">{{ $val }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="row mb15">
                            <div class="col-lg-6">
                                <div class="form-row">
                                    <label for="" class="control-label text-left">Loại hình chi tiết</label>
                                    <select name="type_code" class="form-control setupSelect2" id="type_code_catalogue">
                                        <option value="">[Chọn Loại hình]</option>
                                        @foreach ($projectTypes as $type)
                                            <option
                                                {{ $type->code == old('type_code', $projectCatalogue->type_code ?? '') ? 'selected' : '' }}
                                                value="{{ $type->code }}" data-group="{{ $type->group_id }}">
                                                {{ $type->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="form-row">
                                    <label for="" class="control-label text-left">Nhóm BĐS chính</label>
                                    <select name="property_group_id" class="form-control setupSelect2"
                                        id="property_group_id_catalogue">
                                        <option value="">[Tự động xác định]</option>
                                        @foreach ($propertyGroups as $group)
                                            <option
                                                {{ $group->id == old('property_group_id', $projectCatalogue->property_group_id ?? '') ? 'selected' : '' }}
                                                value="{{ $group->id }}">{{ $group->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="row mb15">
                            <div class="col-lg-6">
                                <div class="form-row">
                                    <label for="" class="control-label text-left">Giao dịch mặc định</label>
                                    <select name="transaction_type" class="form-control setupSelect2">
                                        @foreach (\App\Enums\RealEstate\TransactionTypeEnum::cases() as $type)
                                            <option
                                                {{ old('transaction_type', $projectCatalogue->transaction_type->value ?? '') == $type->value ? 'selected' : '' }}
                                                value="{{ $type->value }}">{{ $type->description() }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-lg-3">
                                <div class="form-row">
                                    <label for="" class="control-label text-left">Thứ tự</label>
                                    <input type="text" name="sort_order"
                                        value="{{ old('sort_order', $projectCatalogue->sort_order ?? 0) }}"
                                        class="form-control text-right" placeholder="" autocomplete="off">
                                </div>
                            </div>
                            <div class="col-lg-3">
                                <div class="form-row">
                                    <label for="" class="control-label text-left">Tình trạng</label>
                                    <select name="publish" class="form-control setupSelect2">
                                        @foreach (config('apps.general.publish') as $key => $val)
                                            <option
                                                {{ $key == old('publish', isset($projectCatalogue->publish) ? $projectCatalogue->publish : '') ? 'selected' : '' }}
                                                value="{{ $key }}">{{ $val }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="row mb15">
                            <div class="col-lg-12">
                                <div class="form-row">
                                    <label for="" class="control-label text-left">Icon Class</label>
                                    <input type="text" name="icon_url"
                                        value="{{ old('icon_url', $projectCatalogue->icon_url ?? '') }}"
                                        class="form-control" placeholder="Font Awesome class..." autocomplete="off">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <hr>
        <div class="row">
            <div class="col-lg-5">
                <div class="panel-head">
                    <div class="panel-title">Cấu hình SEO</div>
                    <div class="panel-description">
                        <p>Thiết lập các thẻ meta giúp tối ưu SEO</p>
                    </div>
                </div>
            </div>
            <div class="col-lg-7">
                <div class="ibox">
                    <div class="ibox-content">
                        <div class="row mb15">
                            <div class="col-lg-12">
                                <div class="form-row">
                                    <label for="" class="control-label text-left">SEO Title</label>
                                    <input type="text" name="meta_title"
                                        value="{{ old('meta_title', $projectCatalogue->meta_title ?? '') }}"
                                        class="form-control" placeholder="" autocomplete="off">
                                </div>
                            </div>
                        </div>
                        <div class="row mb15">
                            <div class="col-lg-12">
                                <div class="form-row">
                                    <label for="" class="control-label text-left">SEO Description</label>
                                    <textarea name="meta_desc" class="form-control">{{ old('meta_desc', $projectCatalogue->meta_desc ?? '') }}</textarea>
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
