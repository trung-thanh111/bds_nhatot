<?php

namespace App\Http\Controllers\Backend\V1\RealEstate;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\V1\RealEstate\ProjectService;
use App\Repositories\RealEstate\ProjectRepository;
use App\Repositories\RealEstate\ProjectCatalogueRepository;
use App\Repositories\RealEstate\ProjectPropertyGroupRepository;
use App\Repositories\RealEstate\ProjectTypeRepository;
use App\Repositories\User\ProvinceRepository;
use App\Http\Requests\RealEstate\Project\StoreRequest;
use App\Http\Requests\RealEstate\Project\UpdateRequest;
use App\Repositories\RealEstate\AgentRepo;
use App\Classes\NestedsetRealEstate;

class ProjectController extends Controller
{
    protected $projectService;
    protected $projectRepository;
    protected $projectCatalogueRepository;
    protected $projectPropertyGroupRepository;
    protected $projectTypeRepository;
    protected $provinceRepository;
    protected $agentRepo;

    public function __construct(
        ProjectService $projectService,
        ProjectRepository $projectRepository,
        ProjectCatalogueRepository $projectCatalogueRepository,
        ProjectPropertyGroupRepository $projectPropertyGroupRepository,
        ProjectTypeRepository $projectTypeRepository,
        ProvinceRepository $provinceRepository,
        AgentRepo $agentRepo
    ) {
        $this->projectService = $projectService;
        $this->projectRepository = $projectRepository;
        $this->projectCatalogueRepository = $projectCatalogueRepository;
        $this->projectPropertyGroupRepository = $projectPropertyGroupRepository;
        $this->projectTypeRepository = $projectTypeRepository;
        $this->provinceRepository = $provinceRepository;
        $this->agentRepo = $agentRepo;
    }

    public function index(Request $request)
    {
        $this->authorize('modules', 'realestate.project.index');
        $projects = $this->projectService->paginate($request);

        $config = [
            'js' => [
                'backend/js/plugins/switchery/switchery.js',
                'https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js',
                'backend/library/library.js',
                'backend/library/location.js',
            ],
            'css' => [
                'backend/css/plugins/switchery/switchery.css',
                'https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css',
            ],
            'model' => 'Project',
        ];
        $config['seo'] = config('apps.realestate.project');

        $propertyGroups = $this->projectPropertyGroupRepository->all();
        $nestedset = new NestedsetRealEstate(['table' => 'project_catalogues']);
        $dropdown = $nestedset->Dropdown(['text' => '[Chọn Nhóm BĐS]']);
        
        $locationType = $request->input('location_type');
        $provinces = collect();
        if ($locationType == 'old') {
            $path = base_path('public/vie_address_before_1_7.json');
            if (file_exists($path)) {
                $data = json_decode(file_get_contents($path), true);
                $provinces = collect(array_map(function($item) {
                    return (object)[
                        'code' => $item['code'],
                        'code_name' => $item['code_name'] ?? $item['code'],
                        'name' => $item['name']
                    ];
                }, $data));
            }
        } elseif ($locationType == 'new') {
            $path = base_path('public/vie_address_after_1_7.json');
            if (file_exists($path)) {
                $data = json_decode(file_get_contents($path), true);
                $provinces = collect(array_map(function($item) {
                    return (object)[
                        'code' => $item['code'],
                        'code_name' => $item['code_name'] ?? $item['code'],
                        'name' => $item['name']
                    ];
                }, $data));
            }
        }

        // Pass Enums for filtering
        $directions = \App\Enums\RealEstate\DirectionEnum::toArray();
        $legalStatuses = \App\Enums\RealEstate\LegalStatusEnum::toArray();
        $furnitureStatuses = \App\Enums\RealEstate\FurnitureStatusEnum::toArray();

        $priceRanges = [
            '0-500000000' => 'Dưới 500 triệu',
            '500000000-800000000' => '500 - 800 triệu',
            '800000000-1000000000' => '800 triệu - 1 tỷ',
            '1000000000-2000000000' => '1 - 2 tỷ',
            '2000000000-3000000000' => '2 - 3 tỷ',
            '3000000000-5000000000' => '3 - 5 tỷ',
            '5000000000-7000000000' => '5 - 7 tỷ',
            '7000000000-10000000000' => '7 - 10 tỷ',
            '10000000000-20000000000' => '10 - 20 tỷ',
            '20000000000-30000000000' => '20 - 30 tỷ',
            '30000000000-0' => 'Trên 30 tỷ',
        ];

        $areaRanges = [
            '0-30' => 'Dưới 30 m²',
            '30-50' => '30 - 50 m²',
            '50-80' => '50 - 80 m²',
            '80-100' => '80 - 100 m²',
            '100-150' => '100 - 150 m²',
            '150-200' => '150 - 200 m²',
            '200-250' => '200 - 250 m²',
            '250-300' => '250 - 300 m²',
            '300-500' => '300 - 500 m²',
            '500-0' => 'Trên 500 m²',
        ];


        $specialStatuses = [
            'featured' => 'Nổi bật',
            'hot' => 'Hot',
            'urgent' => 'Cần bán gấp',
        ];

        return view('backend.dashboard.layout', [
            'template' => 'backend.realestate.project.index',
            'config' => $config,
            'projects' => $projects,
            'propertyGroups' => $propertyGroups,
            'dropdown' => $dropdown,
            'provinces' => $provinces,
            'directions' => $directions,
            'legalStatuses' => $legalStatuses,
            'furnitureStatuses' => $furnitureStatuses,
            'priceRanges' => $priceRanges,
            'areaRanges' => $areaRanges,
            'specialStatuses' => $specialStatuses,
        ]);
    }

    public function create()
    {
        $this->authorize('modules', 'realestate.project.create');

        $propertyGroups = $this->projectPropertyGroupRepository->all();
        $projectTypes = $this->projectTypeRepository->all();
        $nestedset = new NestedsetRealEstate(['table' => 'project_catalogues']);
        $dropdown = $nestedset->Dropdown(['text' => '[Chọn Nhóm BĐS]']);
        $provinces = $this->provinceRepository->all();
        $agents = $this->agentRepo->all();

        $config = $this->configData('create');
        return view('backend.dashboard.layout', [
            'template' => 'backend.realestate.project.store',
            'config' => $config,
            'propertyGroups' => $propertyGroups,
            'projectTypes' => $projectTypes,
            'dropdown' => $dropdown,
            'provinces' => $provinces,
            'agents' => $agents
        ]);
    }

    public function store(StoreRequest $request)
    {
        if ($this->projectService->create($request)) {
            if ($request->input('send') == 'send_and_stay') {
                return redirect()->back()->with('success', 'Thêm mới bản ghi thành công');
            }
            return redirect()->route('realestate.project.index')->with('success', 'Thêm mới bản ghi thành công');
        }
        return redirect()->back()->with('error', 'Thêm mới bản ghi không thành công. Hãy thử lại');
    }

    public function edit($id)
    {
        $this->authorize('modules', 'realestate.project.update');
        $project = $this->projectRepository->findById($id);

        $propertyGroups = $this->projectPropertyGroupRepository->all();
        $projectTypes = $this->projectTypeRepository->all();
        $nestedset = new NestedsetRealEstate(['table' => 'project_catalogues']);
        $dropdown = $nestedset->Dropdown(['text' => '[Chọn Nhóm BĐS]']);
        $provinces = $this->provinceRepository->all();
        $agents = $this->agentRepo->all();

        $config = $this->configData('edit');
        return view('backend.dashboard.layout', [
            'template' => 'backend.realestate.project.store',
            'config' => $config,
            'project' => $project,
            'propertyGroups' => $propertyGroups,
            'projectTypes' => $projectTypes,
            'dropdown' => $dropdown,
            'provinces' => $provinces,
            'agents' => $agents
        ]);
    }

    public function update($id, UpdateRequest $request)
    {
        if ($this->projectService->update($id, $request)) {
            if ($request->input('send') == 'send_and_stay') {
                return redirect()->back()->with('success', 'Cập nhật bản ghi thành công');
            }
            return redirect()->route('realestate.project.index')->with('success', 'Cập nhật bản ghi thành công');
        }
        return redirect()->back()->with('error', 'Cập nhật bản ghi không thành công. Hãy thử lại');
    }

    public function delete($id)
    {
        $this->authorize('modules', 'realestate.project.destroy');
        $project = $this->projectRepository->findById($id);
        $config = [
            'seo' => config('apps.realestate.project'),
        ];
        return view('backend.dashboard.layout', [
            'template' => 'backend.realestate.project.delete',
            'project' => $project,
            'config' => $config
        ]);
    }

    public function destroy($id)
    {
        if ($this->projectService->destroy($id)) {
            return redirect()->route('realestate.project.index')->with('success', 'Xóa bản ghi thành công');
        }
        return redirect()->route('realestate.project.index')->with('error', 'Xóa bản ghi không thành công. Hãy thử lại');
    }

    private function configData($method)
    {
        return [
            'js' => [
                'https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js',
                'backend/library/library.js',
                'backend/plugins/ckfinder_2/ckfinder.js',
                'backend/library/finder.js',
                'backend/library/location.js',
                'backend/library/realestate.js',
            ],
            'css' => [
                'https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css',
            ],
            'method' => $method,
            'seo' => config('apps.realestate.project'),
        ];
    }
}
