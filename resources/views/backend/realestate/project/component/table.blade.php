<table class="table table-striped table-bordered">
    <thead>
        <tr>
            <th style="width:50px;">
                <input type="checkbox" value="" id="checkAll" class="input-checkbox">
            </th>
            <th style="width:100px;">Ảnh</th>
            <th>Tiêu đề / Mã</th>
            <th class="text-right">Giá</th>
            <th class="text-right">Diện tích</th>
            <th>Vị trí</th>
            <th class="text-center" style="width:100px;">Trạng thái</th>
            <th class="text-center" style="width:100px;">Thao tác</th>
        </tr>
    </thead>
    <tbody>
        @if (isset($projects) && is_object($projects))
            @foreach ($projects as $project)
                <tr>
                    <td>
                        <input type="checkbox" value="{{ $project->id }}" class="input-checkbox checkBoxItem">
                    </td>
                    <td>
                        <span class="img-cover"><img src="{{ $project->image ?? 'backend/img/not-found.png' }}"
                                alt=""></span>
                    </td>
                    <td>
                        <div class="project-info">
                            <div class="name"><strong>{{ $project->name }}</strong></div>
                            <div class="code text-danger">Mã: {{ $project->code }}</div>
                        </div>
                    </td>
                    <td class="text-right">
                        @php
                            $unit = $project->price_unit->description() ?? '';
                            $price = number_format($project->price, 0, ',', '.');
                        @endphp
                        @if ($project->price_unit->value == 'total')
                            Tổng cộng: {{ $price }}
                        @else
                            {{ $price }} /{{ $unit }}
                        @endif
                    </td>
                    <td class="text-right">
                        @php
                            $area = $project->area ?? ($project->area_use ?? ($project->area_land ?? 0));
                        @endphp
                        {{ $area }} m²
                    </td>
                    <td>
                        @php
                            $locationType = request('location_type');
                            $address = '';
                            if ($locationType == 'new') {
                                $address = $project->province_new_name ?? $project->province_name;
                            } else {
                                $address = $project->province_name ?? $project->province_new_name;
                            }
                        @endphp
                        {{ $address }}
                    </td>
                    <td class="text-center js-switch-{{ $project->id }}">
                        <input type="checkbox" value="{{ $project->publish }}" class="js-switch status "
                            data-field="publish" data-model="{{ $config['model'] }}"
                            {{ $project->publish == 2 ? 'checked' : '' }} data-modelId="{{ $project->id }}" />
                    </td>
                    <td class="text-center">
                        <a href="{{ route('realestate.project.edit', $project->id) }}" class="btn btn-success"><i
                                class="fa fa-edit"></i></a>
                        <a href="{{ route('realestate.project.delete', $project->id) }}" class="btn btn-danger"><i
                                class="fa fa-trash"></i></a>
                    </td>
                </tr>
            @endforeach
        @endif
    </tbody>
</table>
{{ $projects->links('pagination::bootstrap-4') }}
