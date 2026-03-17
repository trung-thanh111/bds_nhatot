<?php

namespace App\Services\V1\RealEstate;

use App\Services\V1\BaseService;
use App\Repositories\RealEstate\ProjectCatalogueRepository;
use Illuminate\Support\Facades\DB;
use App\Classes\NestedsetRealEstate;
use Illuminate\Support\Str;

class ProjectCatalogueService extends BaseService
{
    protected $projectCatalogueRepository;
    protected $nestedset;

    public function __construct(
        ProjectCatalogueRepository $projectCatalogueRepository
    ) {
        $this->projectCatalogueRepository = $projectCatalogueRepository;
    }

    public function paginate($request)
    {
        $perPage = $request->integer('perpage', 20);
        $condition = [
            'keyword' => addslashes($request->input('keyword')),
            'publish' => $request->input('publish'),
        ];
        
        return $this->projectCatalogueRepository->pagination(
            $this->paginateSelect(),
            $condition,
            $perPage,
            ['path' => 'project/catalogue/index'],
            ['lft', 'ASC']
        );
    }

    public function create($request)
    {
        DB::beginTransaction();
        try {
            $payload = $request->only($this->payload());
            if (!isset($payload['slug']) || empty($payload['slug'])) {
                $payload['slug'] = Str::slug($payload['name']);
            }
            
            $projectCatalogue = $this->projectCatalogueRepository->create($payload);
            
            if ($projectCatalogue && $projectCatalogue->id > 0) {
                $this->nestedset = new NestedsetRealEstate([
                    'table' => 'project_catalogues',
                ]);
                $this->nestedset->Get();
                $this->nestedset->Recursive(0, $this->nestedset->Set());
                $this->nestedset->Action();
            }
            
            DB::commit();
            return true;
        } catch (\Exception $e) {
            DB::rollBack();
            return false;
        }
    }

    public function update($id, $request)
    {
        DB::beginTransaction();
        try {
            $payload = $request->only($this->payload());
            if (!isset($payload['slug']) || empty($payload['slug'])) {
                $payload['slug'] = Str::slug($payload['name']);
            }

            $flag = $this->projectCatalogueRepository->update($id, $payload);
            
            if ($flag) {
                $this->nestedset = new NestedsetRealEstate([
                    'table' => 'project_catalogues',
                ]);
                $this->nestedset->Get();
                $this->nestedset->Recursive(0, $this->nestedset->Set());
                $this->nestedset->Action();
            }
            
            DB::commit();
            return true;
        } catch (\Exception $e) {
            DB::rollBack();
            return false;
        }
    }

    public function destroy($id)
    {
        DB::beginTransaction();
        try {
            $this->projectCatalogueRepository->delete($id);
            
            $this->nestedset = new NestedsetRealEstate([
                'table' => 'project_catalogues',
            ]);
            $this->nestedset->Get();
            $this->nestedset->Recursive(0, $this->nestedset->Set());
            $this->nestedset->Action();

            DB::commit();
            return true;
        } catch (\Exception $e) {
            DB::rollBack();
            return false;
        }
    }

    private function paginateSelect()
    {
        return [
            'id',
            'name',
            'slug',
            'property_group_id',
            'level',
            'publish',
            'sort_order',
        ];
    }

    private function payload()
    {
        return [
            'parent_id',
            'property_group_id',
            'type_code',
            'name',
            'slug',
            'transaction_type',
            'icon_url',
            'sort_order',
            'publish',
            'meta_title',
            'meta_desc',
        ];
    }
}
