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
                    createRoute="property_facility.create"
                    submitRoute="property_facility.index" />
                @php
                $columns = [
                'name' => ['label' => 'Tên tiện ích', 'render' => fn($item) => e($item->name)],
                'created_at' => ['label' => 'Ngày tạo', 'render' => fn($item) => $item->created_at?->format('d-m-Y') ?? 'N/A'],
                ];
                @endphp
                <x-backend.customtable
                    :records="$records->getCollection()"
                    :columns="$columns"
                    :actions="[
                        ['route' => 'property_facility.edit', 'class' => 'btn-success', 'icon' => 'fa-edit'],
                        ['route' => 'property_facility.delete', 'class' => 'btn-danger', 'icon' => 'fa-trash'],
                    ]"
                    :model="$config['model']" />
                {{ $records->links('pagination::bootstrap-4') }}
            </div>
        </div>
    </div>
</div>