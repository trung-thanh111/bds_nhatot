<table class="table table-striped table-bordered">
    <thead>
        <tr>
            <th style="width:50px;">
                <input type="checkbox" value="" id="checkAll" class="input-checkbox">
            </th>
            <th>Tên Loại hình</th>
            <th style="width:150px;">Mã</th>
            <th>Nhóm BĐS</th>
            <th>Giao dịch mặc định</th>
            <th class="text-center" style="width:100px;">Sắp xếp</th>
            <th class="text-center" style="width:100px;">Trạng thái</th>
            <th class="text-center" style="width:100px;">Thao tác</th>
        </tr>
    </thead>
    <tbody>
        @if (isset($projectTypes) && is_object($projectTypes))
            @foreach ($projectTypes as $projectType)
                <tr>
                    <td>
                        <input type="checkbox" value="{{ $projectType->id }}" class="input-checkbox checkBoxItem">
                    </td>
                    <td>
                        {{ $projectType->name }}
                    </td>
                    <td>
                        {{ $projectType->code }}
                    </td>
                    <td>
                        {{ $projectType->group->name ?? 'N/A' }}
                    </td>
                    <td>
                        {{ $projectType->transaction_type }}
                    </td>
                    <td>
                        <input type="text" name="order" value="{{ $projectType->sort_order }}"
                            class="form-control sort-order text-right" data-id="{{ $projectType->id }}"
                            data-model="{{ $config['model'] }}">
                    </td>
                    <td class="text-center js-switch-{{ $projectType->id }}">
                        <input type="checkbox" value="{{ $projectType->publish }}" class="js-switch status "
                            data-field="publish" data-model="{{ $config['model'] }}"
                            {{ $projectType->publish == 2 ? 'checked' : '' }} data-modelId="{{ $projectType->id }}" />
                    </td>
                    <td class="text-center">
                        <a href="{{ route('realestate.project_type.edit', $projectType->id) }}"
                            class="btn btn-success"><i class="fa fa-edit"></i></a>
                        <a href="{{ route('realestate.project_type.delete', $projectType->id) }}"
                            class="btn btn-danger"><i class="fa fa-trash"></i></a>
                    </td>
                </tr>
            @endforeach
        @endif
    </tbody>
</table>
{{ $projectTypes->links('pagination::bootstrap-4') }}
