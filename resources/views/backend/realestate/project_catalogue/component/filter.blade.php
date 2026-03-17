<form action="{{ route('realestate.project_catalogue.index') }}">
    <div class="filter-wrapper">
        <div class="uk-flex uk-flex-middle uk-flex-space-between">
            <div class="perpage">
                @php
                    $perpage = request('perpage') ?: old('perpage');
                @endphp
                <div class="uk-flex uk-flex-middle uk-flex-gap-10">
                    <select name="perpage" class="form-control input-sm setupSelect2 updateSelection">
                        @for($i = 20; $i<= 200; $i+=20)
                            <option {{ ($perpage == $i)  ? 'selected' : '' }}  value="{{ $i }}">{{ $i }} bản ghi</option>
                        @endfor
                    </select>
                </div>
            </div>
            <div class="action">
                <div class="uk-flex uk-flex-middle uk-flex-gap-10">
                    @php
                        $publish = request('publish') ?: old('publish');
                    @endphp
                    <select name="publish" class="form-control setupSelect2 updateSelection">
                        @foreach(config('apps.general.publish') as $key => $val)
                            <option {{ ($publish == $key)  ? 'selected' : '' }} value="{{ $key }}">{{ $val }}</option>
                        @endforeach
                    </select>
                    <div class="uk-search uk-flex uk-flex-middle mr10">
                        <div class="input-group">
                            <input 
                                type="text" 
                                name="keyword" 
                                value="{{ request('keyword') ?: old('keyword') }}" 
                                placeholder="Nhập từ khóa tìm kiếm..." 
                                class="form-control"
                            >
                            <span class="input-group-btn">
                                <button type="submit" name="search" value="search" class="btn btn-primary mb0 btn-sm">Tìm Kiếm
                                </button>
                            </span>
                        </div>
                    </div>
                    <a href="{{ route('realestate.project_catalogue.create') }}" class="btn btn-danger"><i class="fa fa-plus mr5"></i>Thêm Nhóm BĐS</a>
                </div>
            </div>
        </div>
    </div>
</form>
