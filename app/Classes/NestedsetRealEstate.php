<?php
namespace App\Classes;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class NestedsetRealEstate {

    private $params;
    private $checked;
    private $data;
    private $count;
    private $count_level;
    private $lft;
    private $rgt;
    private $level;

	function __construct($params = NULL){
		$this->params = $params;
		$this->checked = NULL;
		$this->data = NULL;
		$this->count = 0;
		$this->count_level = 0;
		$this->lft = NULL;
		$this->rgt = NULL;
		$this->level = NULL;
	}

	public function Get(){
		$result = DB::table($this->params['table'])
		->select('id', 'name', 'parent_id', 'lft', 'rgt', 'level', 'sort_order')
		->whereNull('deleted_at')
		->orderBy('lft', 'asc')->get()->toArray();
		$this->data = $result;
	}

	public function Set(){
		if(isset($this->data) && is_array($this->data)){
			$arr = NULL;
			foreach($this->data as $key => $val){
				$arr[$val->id][$val->parent_id] = 1;
				$arr[$val->parent_id][$val->id] = 1;
			}
			return $arr;
		}
	}

	public function Recursive($start = 0, $arr = NULL){
		$this->lft[$start] = ++$this->count;
		$this->level[$start] = $this->count_level;
		if(isset($arr) && is_array($arr)){
			foreach($arr as $key => $val){
				if((isset($arr[$start][$key]) || isset($arr[$key][$start])) &&(!isset($this->checked[$key][$start]) && !isset($this->checked[$start][$key]))){
					$this->count_level++;
					$this->checked[$start][$key] = 1;
					$this->checked[$key][$start] = 1;
					$this->Recursive($key, $arr);
					$this->count_level--;
				}
			}
		}
		$this->rgt[$start] = ++$this->count;
	}

    public function Action(){
		if(isset($this->level) && is_array($this->level) && isset($this->lft) && is_array($this->lft) && isset($this->rgt) && is_array($this->rgt)){
			$data = NULL;
			foreach($this->level as $key => $val){
				if($key == 0) continue;
				$data[] = array(
					'id' => $key,
					'level' => $val,
					'lft' => $this->lft[$key],
					'rgt' => $this->rgt[$key],
				);
			}
			if(isset($data) && is_array($data) && count($data)){
				foreach ($data as $item) {
                    DB::table($this->params['table'])
                        ->where('id', $item['id'])
                        ->update([
                            'level' => $item['level'],
                            'lft' => $item['lft'],
                            'rgt' => $item['rgt']
                        ]);
                }
			}
		}
    }

	public function Dropdown($param = NULL){
		$this->Get();
		if(isset($this->data) && is_array($this->data)){
			$temp = NULL;
			$temp[0] = (isset($param['text']) && !empty($param['text'])) ? $param['text'] : '[Root]';
			foreach($this->data as $key => $val){
				$temp[$val->id] = str_repeat('|-----', (($val->level > 0) ? ($val->level - 1) : 0)) . $val->name;
			}
			return $temp;
		}
	}
}
