<table class="table table-striped table-bordered">
    <thead>
        <tr>
            <th style="width:50px;">
                <input type="checkbox" value="" id="checkAll" class="input-checkbox">
            </th>
            <th>Tên Nhóm BĐS</th>
            <th class="text-center" style="width:100px;">Sắp xếp</th>
            <th class="text-center" style="width:100px;">Trạng thái</th>
            <th class="text-center" style="width:100px;">Thao tác</th>
        </tr>
    </thead>
    <tbody>
        @if (isset($projectCatalogues) && is_object($projectCatalogues))
            @foreach ($projectCatalogues as $catalogue)
                <tr>
                    <td>
                        <input type="checkbox" value="{{ $catalogue->id }}" class="input-checkbox checkBoxItem">
                    </td>
                    <td>
                        {{ str_repeat('|-----', $catalogue->level > 1 ? $catalogue->level - 1 : 0) . $catalogue->name }}
                    </td>
                    <td>
                        <input type="text" name="order" value="{{ $catalogue->sort_order }}"
                            class="form-control sort-order text-right" data-id="{{ $catalogue->id }}"
                            data-model="{{ $config['model'] }}">
                    </td>
                    <td class="text-center js-switch-{{ $catalogue->id }}">
                        <input type="checkbox" value="{{ $catalogue->publish }}" class="js-switch status "
                            data-field="publish" data-model="{{ $config['model'] }}"
                            {{ $catalogue->publish == 2 ? 'checked' : '' }} data-modelId="{{ $catalogue->id }}" />
                    </td>
                    <td class="text-center">
                        <a href="{{ route('realestate.project_catalogue.edit', $catalogue->id) }}"
                            class="btn btn-success"><i class="fa fa-edit"></i></a>
                        <a href="{{ route('realestate.project_catalogue.delete', $catalogue->id) }}"
                            class="btn btn-danger"><i class="fa fa-trash"></i></a>
                    </td>
                </tr>
            @endforeach
        @endif
    </tbody>
</table>
{{ $projectCatalogues->links('pagination::bootstrap-4') }}
