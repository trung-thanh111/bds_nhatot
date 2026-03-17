<?php

namespace App\Services\V1\RealEstate;

use App\Services\V1\BaseService;
use App\Repositories\RealEstate\ProjectPropertyGroupRepository;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ProjectPropertyGroupService extends BaseService
{
    protected $projectPropertyGroupRepository;

    public function __construct(
        ProjectPropertyGroupRepository $projectPropertyGroupRepository
    ){
        $this->projectPropertyGroupRepository = $projectPropertyGroupRepository;
    }

    public function paginate($request){
        $perPage = $request->integer('perpage') ?? 20;
        $condition = [
            'keyword' => addslashes($request->input('keyword')),
            'publish' => $request->integer('publish'),
        ];
        
        return $this->projectPropertyGroupRepository->pagination(
            ['*'], 
            $condition, 
            $perPage,
            ['path' => 'realestates/property-group/index'],
            ['sort_order', 'ASC']
        );
    }

    public function create($request){
        DB::beginTransaction();
        try{
            $payload = $request->only(['code', 'name', 'description', 'icon_url', 'sort_order', 'publish']);
            $this->projectPropertyGroupRepository->create($payload);
            DB::commit();
            return true;
        }catch(\Exception $e ){
            DB::rollBack();
            Log::error($e->getMessage());
            return false;
        }
    }

    public function update($id, $request){
        DB::beginTransaction();
        try{
            $payload = $request->only(['code', 'name', 'description', 'icon_url', 'sort_order', 'publish']);
            $this->projectPropertyGroupRepository->update($id, $payload);
            DB::commit();
            return true;
        }catch(\Exception $e ){
            DB::rollBack();
            Log::error($e->getMessage());
            return false;
        }
    }

    public function destroy($id){
        DB::beginTransaction();
        try{
            $this->projectPropertyGroupRepository->delete($id);
            DB::commit();
            return true;
        }catch(\Exception $e ){
            DB::rollBack();
            Log::error($e->getMessage());
            return false;
        }
    }
}
