<div class="row">
    <div class="col-lg-3">
        <div class="ibox float-e-margins">
            <div class="ibox-title">
                <span class="label label-success pull-right">Tháng</span>
                <h5>Liên hệ trong tháng</h5>
            </div>
            <div class="ibox-content">
                <h1 class="no-margins">{{ $stats['currentMonthCR'] }}</h1>
                <div class="stat-percent font-bold text-success">{{ $stats['growth'] }}% <i class="fa fa-level-up"></i>
                </div>
                <small>Tăng trưởng so với tháng trước</small>
            </div>
        </div>
    </div>
    <div class="col-lg-3">
        <div class="ibox float-e-margins">
            <div class="ibox-title">
                <span class="label label-info pull-right">Tổng số</span>
                <h5>Nhân viên môi giới</h5>
            </div>
            <div class="ibox-content">
                <h1 class="no-margins">{{ $stats['agentCount'] }}</h1>
                <small>Nhân viên đang quản lý</small>
            </div>
        </div>
    </div>
    <div class="col-lg-3">
        <div class="ibox float-e-margins">
            <div class="ibox-title">
                <span class="label label-primary pull-right">Tổng số</span>
                <h5>Dự án</h5>
            </div>
            <div class="ibox-content">
                <h1 class="no-margins">{{ $stats['projectCount'] }}</h1>
                <small>Tổng số dự án trên hệ thống</small>
            </div>
        </div>
    </div>
    <div class="col-lg-3">
        <div class="ibox float-e-margins">
            <div class="ibox-title">
                <span class="label label-danger pull-right">Tổng số</span>
                <h5>Bài viết</h5>
            </div>
            <div class="ibox-content">
                <h1 class="no-margins">{{ $stats['postCount'] }}</h1>
                <small>Tổng số bài viết</small>
            </div>
        </div>
    </div>
</div>
