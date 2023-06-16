<?php

namespace App\Helpers;
use Carbon\Carbon;
use PragmaRX\Countries\Package\Countries;
use Mushmero\Lapdash\Helpers\Helper as PackageHelper;

class Helper {
    
	public static function transformArray($old_array)
	{
		if(empty($old_array)) return;
		$new_array = array();
		foreach($old_array as $key => $value){
			foreach($value as $k => $v){
				$new_array[$k] = $v;
			}
			unset($old_array[$key]);
		}
		return $new_array;
	}
    public static function getCountryList()
    {
        $countries = Countries::all()
                    ->map(function($country){
                        $commonName = $country->name->common;
            
                        $languages = $country->languages ?? collect();
                    
                        $language = $languages->keys()->first() ?? null;
                    
                        $nativeNames = $country->name->native ?? null;
                    
                        if (
                            filled($language) &&
                                filled($nativeNames) &&
                                filled($nativeNames[$language]) ?? null
                        ) {
                            $native = $nativeNames[$language]['common'] ?? null;
                        }
                    
                        if (blank($native ?? null) && filled($nativeNames)) {
                            $native = $nativeNames->first()['common'] ?? null;
                        }
                    
                        $native = $native ?? $commonName;
                    
                        if ($native !== $commonName && filled($native)) {
                            $native = "$native ($commonName)";
                        }
                        return [$country->cca2 => $native];
                    })->values();

        return self::transformArray($countries);
    }

    public static function getCountryByCca2($cca2 = '')
    {
        $country = Countries::where('cca2', $cca2)
                    ->map(function($country){
                        $commonName = $country->name->common;

                        $languages = $country->languages ?? collect();
                    
                        $language = $languages->keys()->first() ?? null;
                    
                        $nativeNames = $country->name->native ?? null;
                    
                        if (
                            filled($language) &&
                                filled($nativeNames) &&
                                filled($nativeNames[$language]) ?? null
                        ) {
                            $native = $nativeNames[$language]['common'] ?? null;
                        }
                    
                        if (blank($native ?? null) && filled($nativeNames)) {
                            $native = $nativeNames->first()['common'] ?? null;
                        }
                    
                        $native = $native ?? $commonName;
                    
                        if ($native !== $commonName && filled($native)) {
                            $native = "$native ($commonName)";
                        }
                        return ['name' => $native];
                    });
        return (object)self::transformArray($country);
    }

    public static function generataPeopleNo($initial = '', $department_tag = '', $unit_tag = '')
    {
        $people_no = '';
        if(empty($initial)) $initial = PackageHelper::generateRandomString(3,'alphaUpper');
        if(!empty($department_tag)){
            $people_no = $initial.Carbon::now()->format('Y').$department_tag.PackageHelper::generateRandomNumber(4);
        }
        if(!empty($unit_tag)){
            $people_no = $initial.Carbon::now()->format('Y').$unit_tag.PackageHelper::generateRandomNumber(4);
        }
        if(!empty($department_tag) && !empty($unit_tag)){
            $people_no = $initial.Carbon::now()->format('Y').$department_tag.'-'.$unit_tag.PackageHelper::generateRandomNumber(4);
        }
        if(empty($department_tag) && empty($unit_tag)){
            $people_no = $initial.Carbon::now()->format('Y').PackageHelper::generateRandomString(4,'alphaUpper').PackageHelper::generateRandomNumber(4);
        }
        return $people_no;
    }
}