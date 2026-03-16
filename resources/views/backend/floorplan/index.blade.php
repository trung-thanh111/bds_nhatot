@include('backend.dashboard.component.breadcrumb', ['title' => $config['seo']['index']['title']])
<div class="row mt20">
    <div class="col-lg-12">
        <div class="ibox float-e-margins">
            <div class="ibox-title">
                <h5>{{ $config['seo']['index']['table']; }} </h5>
                @include('backend.dashboard.component.toolbox', ['model' => $config['model']])
            </div>
            <div class="ibox-content">
                <x-backend.filter
                    createRoute="floorplan.create"
                    submitRoute="floorplan.index" />
                @php
                $columns = [
                'plan_image' => ['label' => 'Sơ đồ', 'render' => fn($item) => '<img src="'.asset($item->plan_image ?? 'backend/img/img-not-found.jpg').'" style="width:80px;height:50px;object-fit:cover" />'],
                'floor_label' => ['label' => 'Tầng', 'render' => fn($item) => e($item->floor_label)],
                'created_at' => ['label' => 'Ngày tạo', 'render' => fn($item) => $item->created_at?->format('d-m-Y') ?? 'N/A'],
                ];
                @endphp
                <x-backend.customtable
                    :records="$records->getCollection()"
                    :columns="$columns"
                    :actions="[
                        ['route' => 'floorplan.edit', 'class' => 'btn-success', 'icon' => 'fa-edit'],
                        ['route' => 'floorplan.delete', 'class' => 'btn-danger', 'icon' => 'fa-trash'],
                    ]"
                    :model="$config['model']" />
                {{ $records->links('pagination::bootstrap-4') }}
            </div>
        </div>
    </div>
</div>