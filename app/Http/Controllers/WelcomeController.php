<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Attendance;
use Carbon\Carbon;
use Str;
use App\Http\Traits\PeopleTraits;
use App\Http\Traits\AttendanceTraits;
use App\Http\Traits\AppSettingTraits;

class WelcomeController extends Controller
{
    use PeopleTraits, AttendanceTraits, AppSettingTraits;

    protected $title;

    /**
     * Declare default template for button edit, button delete & button view
     */
    public function __construct()
    {
        $this->title = 'Welcome';
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('modules.attendances.create');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $this->validate(
            $request, 
            [
                'people_no' => 'required',
            ],
            [
                'people_no.required' => 'The staff no is required',
            ],
        );
        $people_no = Str::upper($request->input('people_no'));
        $clock_in = $request->input('clock_in');
        $clock_out = $request->input('clock_out');

        if($this->peopleExist($people_no)){
            if(!empty($clock_in) && $clock_in == 'in'){
                if($this->attendanceExist($people_no, Carbon::today()->toDateString(), 'clock_in')){         
                    flash(
                        'You already clocked in.',
                    )->warning();
                }else{
                    $now = Carbon::now(config('app.timezone'));
                    $clocked_in_time = $now->toTimeString();
                    $time_limit = $this->getValueByParameter('clock_in_time_limit')->value;
                    if($time_limit){
                        if($clocked_in_time < Carbon::parse($time_limit)->format('h:i:s')){
                            $create = [
                                'people_no' => $people_no,
                                'clock_in' => $now->toDateTimeString(),
                                'status' => 'Normal',
                            ];
                        }else{
                            $create = [
                                'people_no' => $people_no,
                                'clock_in' => $now->toDateTimeString(),
                                'status' => 'Late',
                            ];
                        }
                        Attendance::create($create);
                        flash(
                            'Clock in succesfull.',
                        )->success();
                    }else{
                        flash(
                            'Unable to clock in. Please contact administrator',
                        )->error();
                    }  
                }
            }else if(!empty($clock_out) && $clock_out == 'out'){
                if($this->attendanceExist($people_no, Carbon::today()->toDateString(), 'clock_out')){ 
                    flash(
                        'You already clocked out',
                    )->warning();
                }else{
                    Attendance::where('people_no',$people_no)->update(
                        [
                            'clock_out' => Carbon::now(config('app.timezone'))->toDateTimeString(),
                        ]
                    );   
                    flash(
                        'Clock out succesfull.',
                    )->success();
                }
            }else{
                flash(
                    'Error! Please contact administrator',
                )->error();
            }
        }else{
            flash(
                'Unable to find staff. Please refer to management',
            )->error();
        }

        return back();
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
