<?php

namespace App\Http\Traits;
use App\Models\Attendance;

trait AttendanceTraits
{
    public function attendanceExist($people_no = '', $date = '', $method = 'clock_in')
    {
        if(empty($people_no) || empty($date)){
            return false;
        }

        if($method == 'clock_in'){
            $clocked_in = Attendance::where('people_no', $people_no)->whereDate('clock_in', $date)->first();
            if($clocked_in){
                return true;
            }else{
                return false;
            }
        }else if($method == 'clock_out'){
            $clocked_out = Attendance::where('people_no', $people_no)->whereDate('clock_out', $date)->first();
            if($clocked_out){
                return true;
            }else{
                return false;
            }
        }

    }

    public function getAttendance($condition = [])
    {
        if(!empty($condition)){
            return Attendance::where($condition);
        }else{
            return Attendance::all();
        }
    }
}