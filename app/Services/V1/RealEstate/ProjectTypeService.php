<?php

namespace App\Services\V1\RealEstate;

use App\Services\V1\BaseService;
use App\Repositories\RealEstate\ProjectTypeRepository;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ProjectTypeService extends BaseService
{
    protected $projectTypeRepository;

    public function __construct(
        ProjectTypeRepository $projectTypeRepository
    ){
        $this->projectTypeRepository = $projectTypeRepository;
    }

    public function paginate($request){
        $perPage = $request->integer('perpage') ?? 20;
        $condition = [
            'keyword' => addslashes($request->input('keyword')),
            'publish' => $request->integer('publish'),
        ];
        
        return $this->projectTypeRepository->getProjectTypes(
            ['*'], 
            $condition, 
            $perPage
        );
    }

    public function create($request){
        DB::beginTransaction();
        try{
            $payload = $request->only(['group_id', 'code', 'name', 'name_short', 'default_transaction', 'sort_order', 'publish']);
            $this->projectTypeRepository->create($payload);
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
            $payload = $request->only(['group_id', 'code', 'name', 'name_short', 'default_transaction', 'sort_order', 'publish']);
            $this->projectTypeRepository->update($id, $payload);
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
            $this->projectTypeRepository->delete($id);
            DB::commit();
            return true;
        }catch(\Exception $e ){
            DB::rollBack();
            Log::error($e->getMessage());
            return false;
        }
    }
}
