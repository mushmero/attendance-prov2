<?php

namespace App\Http\Traits;
use App\Helpers\Helper;
use App\Models\Departments;
use App\Models\People;
use App\Models\Units;
use Str;

trait PeopleTraits
{
    public function peopleExist($people_no = '')
    {
        if(empty($people_no)){
            return false;
        }
        $exist = People::where('people_no', $people_no)->first();
        if($exist){
            return true;
        }else{
            return false;
        }
    }

    public function getDepartment($department_id)
    {
        $result = Departments::where('id',$department_id)->first();
        return $result;
    }

    public function getUnit($unit_id)
    {
        $result = Units::where('id',$unit_id)->first();
        return $result;
    }

    public function peopleNo($initial, $department_id, $unit_id )
    {
        return Str::upper(Helper::generataPeopleNo($initial, !is_null($this->getDepartment($department_id)) ? $this->getDepartment($department_id)->tag : '', !is_null($this->getUnit($unit_id)) ? $this->getUnit($unit_id)->tag : ''));
    }
}