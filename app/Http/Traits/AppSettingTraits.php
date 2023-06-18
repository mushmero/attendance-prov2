<?php

namespace App\Http\Traits;
use App\Models\AppSetting;

trait AppSettingTraits 
{
    public function getValueByParameter($parameter = '')
    {
        if(empty($parameter)) return false;
        $value = AppSetting::where('parameter',$parameter)->first();
        if($value){
            return $value;
        }else{
            return false;
        }
    }
}