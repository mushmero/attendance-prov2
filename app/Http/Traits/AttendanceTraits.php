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
            $is_clocked_in = Attendance::where('people_no', $people_no)->whereDate('clock_in', $date)->first();
            if($is_clocked_in){
                $clocked_out = Attendance::where('people_no', $people_no)->whereDate('clock_in', $date)->whereDate('clock_out', $date)->first();
                if($clocked_out){
                    return true;
                }else{
                    return false;
                }
            }else{
                return null;
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