<?php

namespace App\Http\Traits;
use App\Models\Attendance;
use Carbon\Carbon;

trait AttendanceTraits
{
    public function attendanceExist($people_no = '', $timestamp = '', $method = 'clock_in')
    {
        if(empty($people_no) || empty($timestamp)){
            return false;
        }

        if($method == 'clock_in'){
            $clocked_in = Attendance::where(['people_no' => $people_no, 'clock_in' => Carbon::parse($timestamp)->format('Y-m-d')])->first();
            if($clocked_in){
                return true;
            }else{
                return false;
            }
        }else if($method == 'clock_out'){
            $clocked_out = Attendance::where(['people_no' => $people_no, 'clock_out' => Carbon::parse($timestamp)->format('Y-m-d')])->first();
            if($clocked_out){
                return true;
            }else{
                return false;
            }
        }

    }
}