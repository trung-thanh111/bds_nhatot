@include('backend.dashboard.component.breadcrumb', ['title' => $config['seo']['delete']['title']])
<form action="{{ route('realestate.project_catalogue.destroy', $projectCatalogue->id) }}" method="post" class="box">
    @csrf
    @method('DELETE')
    <div class="wrapper wrapper-content animated fadeInRight">
        <div class="row">
            <div class="col-lg-5">
                <div class="panel-head">
                    <div class="panel-title">Xóa bản ghi</div>
                    <div class="panel-description">
                        <p>Bạn đang muốn xóa nhóm BĐS có tên là: <strong>{{ $projectCatalogue->name }}</strong></p>
                        <p>Lưu ý: Không thể khôi phục bản ghi sau khi xóa. Hãy chắc chắn với quyết định của mình.</p>
                    </div>
                </div>
            </div>
            <div class="col-lg-7">
                <div class="ibox">
                    <div class="ibox-content">
                        <div class="row mb15">
                            <div class="col-lg-12">
                                <div class="form-row">
                                    <label for="" class="control-label text-left">Tên Nhóm BĐS</label>
                                    <input type="text" name="name"
                                        value="{{ old('name', $projectCatalogue->name ?? '') }}" class="form-control"
                                        placeholder="" autocomplete="off" readonly>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="text-right mb15">
            <button class="btn btn-danger" type="submit" name="send" value="send">Xác nhận xóa</button>
        </div>
    </div>
</form>
