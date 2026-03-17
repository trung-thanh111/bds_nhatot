<?php

namespace App\Http\Controllers\Backend\V1\RealEstate;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\V1\RealEstate\ProjectTypeService;
use App\Repositories\RealEstate\ProjectTypeRepository;
use App\Repositories\RealEstate\ProjectPropertyGroupRepository;
use App\Http\Requests\RealEstate\ProjectType\StoreRequest;
use App\Http\Requests\RealEstate\ProjectType\UpdateRequest;

class ProjectTypeController extends Controller
{
    protected $projectTypeService;
    protected $projectTypeRepository;
    protected $projectPropertyGroupRepository;

    public function __construct(
        ProjectTypeService $projectTypeService,
        ProjectTypeRepository $projectTypeRepository,
        ProjectPropertyGroupRepository $projectPropertyGroupRepository
    ){
        $this->projectTypeService = $projectTypeService;
        $this->projectTypeRepository = $projectTypeRepository;
        $this->projectPropertyGroupRepository = $projectPropertyGroupRepository;
    }

    public function index(Request $request)
    {
        $this->authorize('modules', 'realestate.project_type.index');
        $projectTypes = $this->projectTypeService->paginate($request);
        $config = [
            'extendJs' => true,
            'model' => 'ProjectType',
            'seo' => [
                'index' => [
                    'title' => 'Quản lý Loại hình BĐS',
                    'table' => 'Danh sách Loại hình BĐS'
                ]
            ]
        ];
        $template = 'backend.realestate.project_type.index';
        return view('backend.dashboard.layout', compact(
            'template',
            'config',
            'projectTypes'
        ));
    }

    public function create()
    {
        $this->authorize('modules', 'realestate.project_type.create');
        $config = $this->configData();
        $config['seo'] = [
            'create' => [
                'title' => 'Thêm mới Loại hình BĐS'
            ]
        ];
        $config['method'] = 'create';
        $propertyGroups = $this->projectPropertyGroupRepository->all();
        $template = 'backend.realestate.project_type.store';
        return view('backend.dashboard.layout', compact(
            'template',
            'config',
            'propertyGroups'
        ));
    }

    public function store(StoreRequest $request)
    {
        if ($this->projectTypeService->create($request)) {
            if ($request->input('send') == 'send_and_stay') {
                return redirect()->back()->with('success', 'Thêm mới bản ghi thành công');
            }
            return redirect()->route('realestate.project_type.index')->with('success', 'Thêm mới bản ghi thành công');
        }
        return redirect()->back()->with('error', 'Thêm mới bản ghi không thành công. Hãy thử lại');
    }

    public function edit($id)
    {
        $this->authorize('modules', 'realestate.project_type.update');
        $projectType = $this->projectTypeRepository->findById($id);
        $config = $this->configData();
        $config['seo'] = [
            'edit' => [
                'title' => 'Cập nhật Loại hình BĐS'
            ]
        ];
        $config['method'] = 'edit';
        $propertyGroups = $this->projectPropertyGroupRepository->all();
        $template = 'backend.realestate.project_type.store';
        return view('backend.dashboard.layout', compact(
            'template',
            'config',
            'projectType',
            'propertyGroups',
        ));
    }

    public function update($id, UpdateRequest $request)
    {
        if ($this->projectTypeService->update($id, $request)) {
            if ($request->input('send') == 'send_and_stay') {
                return redirect()->back()->with('success', 'Cập nhật bản ghi thành công');
            }
            return redirect()->route('realestate.project_type.index')->with('success', 'Cập nhật bản ghi thành công');
        }
        return redirect()->back()->with('error', 'Cập nhật bản ghi không thành công. Hãy thử lại');
    }

    public function delete($id)
    {
        $this->authorize('modules', 'realestate.project_type.destroy');
        $projectType = $this->projectTypeRepository->findById($id);
        $config['seo'] = [
            'delete' => [
                'title' => 'Xóa Loại hình BĐS'
            ]
        ];
        $template = 'backend.realestate.project_type.delete';
        return view('backend.dashboard.layout', compact(
            'template',
            'projectType',
            'config',
        ));
    }

    public function destroy($id)
    {
        if ($this->projectTypeService->destroy($id)) {
            return redirect()->route('realestate.project_type.index')->with('success', 'Xóa bản ghi thành công');
        }
        return redirect()->route('realestate.project_type.index')->with('error', 'Xóa bản ghi không thành công. Hãy thử lại');
    }

    private function configData()
    {
        return [
            'extendJs' => true
        ];
    }
}
