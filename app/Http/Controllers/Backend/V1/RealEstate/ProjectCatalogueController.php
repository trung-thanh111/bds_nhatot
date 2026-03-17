<?php

namespace App\Http\Controllers\Backend\V1\RealEstate;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\V1\RealEstate\ProjectCatalogueService;
use App\Repositories\RealEstate\ProjectCatalogueRepository;
use App\Repositories\RealEstate\ProjectPropertyGroupRepository;
use App\Repositories\RealEstate\ProjectTypeRepository;
use App\Http\Requests\RealEstate\ProjectCatalogue\StoreRequest;
use App\Http\Requests\RealEstate\ProjectCatalogue\UpdateRequest;
use App\Classes\NestedsetRealEstate;

class ProjectCatalogueController extends Controller
{
    protected $projectCatalogueService;
    protected $projectCatalogueRepository;
    protected $projectPropertyGroupRepository;
    protected $projectTypeRepository;
    protected $nestedset;

    public function __construct(
        ProjectCatalogueService $projectCatalogueService,
        ProjectCatalogueRepository $projectCatalogueRepository,
        ProjectPropertyGroupRepository $projectPropertyGroupRepository,
        ProjectTypeRepository $projectTypeRepository
    ) {
        $this->projectCatalogueService = $projectCatalogueService;
        $this->projectCatalogueRepository = $projectCatalogueRepository;
        $this->projectPropertyGroupRepository = $projectPropertyGroupRepository;
        $this->projectTypeRepository = $projectTypeRepository;
    }

    public function index(Request $request)
    {
        $this->authorize('modules', 'realestate.project_catalogue.index');
        $projectCatalogues = $this->projectCatalogueService->paginate($request);
        $config = [
            'js' => [
                'backend/js/plugins/switchery/switchery.js',
                'https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js',
                'backend/library/library.js',
            ],
            'css' => [
                'backend/css/plugins/switchery/switchery.css',
                'https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css',
            ],
            'model' => 'ProjectCatalogue',
        ];
        $config['seo'] = config('apps.realestate.project_catalogue');
        $template = 'backend.realestate.project_catalogue.index';
        return view('backend.dashboard.layout', compact(
            'template',
            'config',
            'projectCatalogues'
        ));
    }

    public function create()
    {
        $this->authorize('modules', 'realestate.project_catalogue.create');
        $propertyGroups = $this->projectPropertyGroupRepository->all();
        $projectTypes = $this->projectTypeRepository->all();
        $this->nestedset = new NestedsetRealEstate([
            'table' => 'project_catalogues',
        ]);
        $dropdown = $this->nestedset->Dropdown();
        
        $config = $this->configData('create');
        $template = 'backend.realestate.project_catalogue.store';
        return view('backend.dashboard.layout', compact(
            'template',
            'config',
            'propertyGroups',
            'projectTypes',
            'dropdown'
        ));
    }

    public function store(StoreRequest $request)
    {
        if ($this->projectCatalogueService->create($request)) {
            if ($request->input('send') == 'send_and_stay') {
                return redirect()->back()->with('success', 'Thêm mới bản ghi thành công');
            }
            return redirect()->route('realestate.project_catalogue.index')->with('success', 'Thêm mới bản ghi thành công');
        }
        return redirect()->route('realestate.project_catalogue.index')->with('error', 'Thêm mới bản ghi không thành công. Hãy thử lại');
    }

    public function edit($id)
    {
        $this->authorize('modules', 'realestate.project_catalogue.update');
        $projectCatalogue = $this->projectCatalogueRepository->findById($id);
        $propertyGroups = $this->projectPropertyGroupRepository->all();
        $projectTypes = $this->projectTypeRepository->all();
        $this->nestedset = new NestedsetRealEstate([
            'table' => 'project_catalogues',
        ]);
        $dropdown = $this->nestedset->Dropdown();

        $config = $this->configData('edit');
        $template = 'backend.realestate.project_catalogue.store';
        return view('backend.dashboard.layout', compact(
            'template',
            'config',
            'projectCatalogue',
            'propertyGroups',
            'projectTypes',
            'dropdown'
        ));
    }

    public function update($id, UpdateRequest $request)
    {
        if ($this->projectCatalogueService->update($id, $request)) {
            if ($request->input('send') == 'send_and_stay') {
                return redirect()->back()->with('success', 'Cập nhật bản ghi thành công');
            }
            return redirect()->route('realestate.project_catalogue.index')->with('success', 'Cập nhật bản ghi thành công');
        }
        return redirect()->route('realestate.project_catalogue.index')->with('error', 'Cập nhật bản ghi không thành công. Hãy thử lại');
    }

    public function delete($id)
    {
        $this->authorize('modules', 'realestate.project_catalogue.destroy');
        $projectCatalogue = $this->projectCatalogueRepository->findById($id);
        $config = [
            'seo' => config('apps.realestate.project_catalogue'),
        ];
        $template = 'backend.realestate.project_catalogue.delete';
        return view('backend.dashboard.layout', compact(
            'template',
            'projectCatalogue',
            'config'
        ));
    }

    public function destroy($id)
    {
        if ($this->projectCatalogueService->destroy($id)) {
            return redirect()->route('realestate.project_catalogue.index')->with('success', 'Xóa bản ghi thành công');
        }
        return redirect()->route('realestate.project_catalogue.index')->with('error', 'Xóa bản ghi không thành công. Hãy thử lại');
    }

    private function configData($method)
    {
        return [
            'js' => [
                'https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js',
                'backend/library/library.js',
                'backend/plugins/ckfinder_2/ckfinder.js',
                'backend/library/finder.js',
                'backend/library/project_catalogue.js',
            ],
            'css' => [
                'https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css',
            ],
            'method' => $method,
            'seo' => config('apps.realestate.project_catalogue'),
        ];
    }
}
