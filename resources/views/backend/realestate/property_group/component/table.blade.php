<table class="table table-striped table-bordered">
    <thead>
        <tr>
            <th style="width:50px;">
                <input type="checkbox" value="" id="checkAll" class="input-checkbox">
            </th>
            <th>Tên Nhóm</th>
            <th style="width:150px;">Mã</th>
            <th>Mô tả</th>
            <th class="text-center" style="width:100px;">Sắp xếp</th>
            <th class="text-center" style="width:100px;">Trạng thái</th>
            <th class="text-center" style="width:100px;">Thao tác</th>
        </tr>
    </thead>
    <tbody>
        @if (isset($propertyGroups) && is_object($propertyGroups))
            @foreach ($propertyGroups as $propertyGroup)
                <tr>
                    <td>
                        <input type="checkbox" value="{{ $propertyGroup->id }}" class="input-checkbox checkBoxItem">
                    </td>
                    <td>
                        {{ $propertyGroup->name }}
                    </td>
                    <td>
                        {{ $propertyGroup->code }}
                    </td>
                    <td>
                        {{ $propertyGroup->description }}
                    </td>
                    <td>
                        <input type="text" name="order" value="{{ $propertyGroup->sort_order }}"
                            class="form-control sort-order text-right" data-id="{{ $propertyGroup->id }}"
                            data-model="{{ $config['model'] }}">
                    </td>
                    <td class="text-center js-switch-{{ $propertyGroup->id }}">
                        <input type="checkbox" value="{{ $propertyGroup->publish }}" class="js-switch status "
                            data-field="publish" data-model="{{ $config['model'] }}"
                            {{ $propertyGroup->publish == 2 ? 'checked' : '' }}
                            data-modelId="{{ $propertyGroup->id }}" />
                    </td>
                    <td class="text-center">
                        <a href="{{ route('realestate.property_group.edit', $propertyGroup->id) }}"
                            class="btn btn-success"><i class="fa fa-edit"></i></a>
                        <a href="{{ route('realestate.property_group.delete', $propertyGroup->id) }}"
                            class="btn btn-danger"><i class="fa fa-trash"></i></a>
                    </td>
                </tr>
            @endforeach
        @endif
    </tbody>
</table>
{{ $propertyGroups->links('pagination::bootstrap-4') }}
