<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Attendance;
use Carbon\Carbon;
use Str;
use App\Http\Traits\PeopleTraits;
use App\Http\Traits\AttendanceTraits;

class WelcomeController extends Controller
{
    use PeopleTraits, AttendanceTraits;

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
                if($this->attendanceExist($people_no, Carbon::today(), 'clock_in')){         
                    flash(
                        'You already clocked in.',
                    )->warning();
                }else{
                    Attendance::create(
                        [
                            'people_no' => $people_no,
                            'clock_in' => Carbon::now()->toDateTimeString(),
                            'status' => 'New',
                        ]
                    );   
                    flash(
                        'Clock in succesfull.',
                    )->success();
                }
            }
            if(!empty($clock_out) && $clock_out == 'out'){
                if($this->attendanceExist($people_no, Carbon::today(), 'clock_out')){
                    Attendance::where('people_no',$people_no)->update(
                        [
                            'clock_out' => Carbon::now()->toDateTimeString(),
                            'status' => 'Clock Out',
                        ]
                    );   
                    flash(
                        'Clock out succesfull.',
                    )->success();
                }else{
                    Attendance::where('people_no',$people_no)->update(
                        [
                            'clock_out' => Carbon::now()->toDateTimeString(),
                            'status' => 'Clock Out',
                        ]
                    );   
                    flash(
                        'Clock out succesfull.',
                    )->success();
                }
            }
            flash(
                'Error! Please contact administrator',
            )->error();
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
