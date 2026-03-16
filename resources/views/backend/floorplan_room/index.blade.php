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
                    createRoute="floorplan_room.create"
                    submitRoute="floorplan_room.index" />
                @php
                $columns = [
                'room_name' => ['label' => 'Tên phòng', 'render' => fn($item) => e($item->room_name)],
                'area_sqm' => ['label' => 'Diện tích', 'render' => fn($item) => $item->area_sqm . ' m²'],
                'floorplan' => ['label' => 'Mặt bằng', 'render' => fn($item) => ($item->floorplans?->floor_label ?? 'N/A') . ' - ' . ($item->floorplans?->properties?->title ?? '')],
                ];
                @endphp
                <x-backend.customtable
                    :records="$records->getCollection()"
                    :columns="$columns"
                    :actions="[
                        ['route' => 'floorplan_room.edit', 'class' => 'btn-success', 'icon' => 'fa-edit'],
                        ['route' => 'floorplan_room.delete', 'class' => 'btn-danger', 'icon' => 'fa-trash'],
                    ]"
                    :model="$config['model']" />
                {{ $records->links('pagination::bootstrap-4') }}
            </div>
        </div>
    </div>
</div>