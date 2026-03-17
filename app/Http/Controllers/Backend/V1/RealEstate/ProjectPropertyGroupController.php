<?php

namespace App\Http\Controllers\Backend\V1\RealEstate;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\V1\RealEstate\ProjectPropertyGroupService;
use App\Repositories\RealEstate\ProjectPropertyGroupRepository;
use App\Http\Requests\RealEstate\ProjectPropertyGroup\StoreRequest;
use App\Http\Requests\RealEstate\ProjectPropertyGroup\UpdateRequest;

class ProjectPropertyGroupController extends Controller
{
    protected $projectPropertyGroupService;
    protected $projectPropertyGroupRepository;

    public function __construct(
        ProjectPropertyGroupService $projectPropertyGroupService,
        ProjectPropertyGroupRepository $projectPropertyGroupRepository
    ){
        $this->projectPropertyGroupService = $projectPropertyGroupService;
        $this->projectPropertyGroupRepository = $projectPropertyGroupRepository;
    }

    public function index(Request $request)
    {
        $this->authorize('modules', 'realestate.property_group.index');
        $propertyGroups = $this->projectPropertyGroupService->paginate($request);
        $config = [
            'extendJs' => true,
            'model' => 'ProjectPropertyGroup',
            'seo' => [
                'index' => [
                    'title' => 'Quản lý Phân loại BĐS',
                    'table' => 'Danh sách Phân loại BĐS'
                ]
            ]
        ];
        $template = 'backend.realestate.property_group.index';
        return view('backend.dashboard.layout', compact(
            'template',
            'config',
            'propertyGroups'
        ));
    }

    public function create()
    {
        $this->authorize('modules', 'realestate.property_group.create');
        $config = $this->configData();
        $config['seo'] = [
            'create' => [
                'title' => 'Thêm mới Phân loại BĐS'
            ]
        ];
        $config['method'] = 'create';
        $template = 'backend.realestate.property_group.store';
        return view('backend.dashboard.layout', compact(
            'template',
            'config',
        ));
    }

    public function store(StoreRequest $request)
    {
        if ($this->projectPropertyGroupService->create($request)) {
            if ($request->input('send') == 'send_and_stay') {
                return redirect()->back()->with('success', 'Thêm mới bản ghi thành công');
            }
            return redirect()->route('realestate.property_group.index')->with('success', 'Thêm mới bản ghi thành công');
        }
        return redirect()->back()->with('error', 'Thêm mới bản ghi không thành công. Hãy thử lại');
    }

    public function edit($id)
    {
        $this->authorize('modules', 'realestate.property_group.update');
        $propertyGroup = $this->projectPropertyGroupRepository->findById($id);
        $config = $this->configData();
        $config['seo'] = [
            'edit' => [
                'title' => 'Cập nhật Phân loại BĐS'
            ]
        ];
        $config['method'] = 'edit';
        $template = 'backend.realestate.property_group.store';
        return view('backend.dashboard.layout', compact(
            'template',
            'config',
            'propertyGroup',
        ));
    }

    public function update($id, UpdateRequest $request)
    {
        if ($this->projectPropertyGroupService->update($id, $request)) {
            if ($request->input('send') == 'send_and_stay') {
                return redirect()->back()->with('success', 'Cập nhật bản ghi thành công');
            }
            return redirect()->route('realestate.property_group.index')->with('success', 'Cập nhật bản ghi thành công');
        }
        return redirect()->back()->with('error', 'Cập nhật bản ghi không thành công. Hãy thử lại');
    }

    public function delete($id)
    {
        $this->authorize('modules', 'realestate.property_group.destroy');
        $propertyGroup = $this->projectPropertyGroupRepository->findById($id);
        $config['seo'] = [
            'delete' => [
                'title' => 'Xóa Phân loại BĐS'
            ]
        ];
        $template = 'backend.realestate.property_group.delete';
        return view('backend.dashboard.layout', compact(
            'template',
            'propertyGroup',
            'config',
        ));
    }

    public function destroy($id)
    {
        if ($this->projectPropertyGroupService->destroy($id)) {
            return redirect()->route('realestate.property_group.index')->with('success', 'Xóa bản ghi thành công');
        }
        return redirect()->route('realestate.property_group.index')->with('error', 'Xóa bản ghi không thành công. Hãy thử lại');
    }

    private function configData()
    {
        return [
            'extendJs' => true
        ];
    }
}
