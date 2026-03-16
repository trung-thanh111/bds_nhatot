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
                    createRoute="property.create"
                    submitRoute="property.index" />
                @php
                $columns = [
                'image' => ['label' => 'Hình ảnh', 'render' => fn($item) => '<img src="'.asset($item->image ?? 'backend/img/img-not-found.jpg').'" style="width:80px;height:50px;object-fit:cover" />'],
                'title' => ['label' => 'Tiêu đề', 'render' => fn($item) => e($item->title)],
                'price' => ['label' => 'Giá', 'render' => fn($item) => number_format($item->price) . ' ' . $item->price_unit],
                'area' => ['label' => 'Diện tích', 'render' => fn($item) => $item->area_sqm . ' m²'],
                'address' => ['label' => 'Địa chỉ', 'render' => fn($item) => e($item->address)],
                ];
                @endphp
                <x-backend.customtable
                    :records="$records->getCollection()"
                    :columns="$columns"
                    :actions="[
                        ['route' => 'property.edit', 'class' => 'btn-success', 'icon' => 'fa-edit'],
                        ['route' => 'property.delete', 'class' => 'btn-danger', 'icon' => 'fa-trash'],
                    ]"
                    :model="$config['model']" />
                {{ $records->links('pagination::bootstrap-4') }}
            </div>
        </div>
    </div>
</div>