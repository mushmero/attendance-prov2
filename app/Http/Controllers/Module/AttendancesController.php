<?php

namespace App\Http\Controllers\Module;

use App\Http\Controllers\Controller;
use App\Models\Attendance;
use Carbon\Carbon;
use Illuminate\Http\Request;

class AttendancesController extends Controller
{

    protected $btnEdit, $btnDelete, $btnDetails, $title;

    /**
     * Declare default template for button edit, button delete & button view
     */
    public function __construct()
    {
        $this->middleware('autologout');
        $this->btnEdit = config('lapdash.btn-edit');
        $this->btnDelete = config('lapdash.btn-delete');
        $this->btnDetails = config('lapdash.btn-view');
        $this->title = 'Attendance';
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $data = array();
        $array = Attendance::where('created_at', Carbon::today())->get();

        $heads = [
            'Staff No',
            'Clock In',
            'Clock Out',
            'Status',
            // ['label' => 'Actions', 'no-export' => true, 'width' => 5],
        ];

        foreach($array as $arr){
            $data[] = array(
                $arr->people_no,
                $arr->clock_in,
                $arr->clock_out,
                $arr->status,
                // '<nobr><a href="'.url('departments/'.$arr->id.'/edit').'">'.$this->btnEdit.'</a><a href="'.url('departments/'.$arr->id.'/delete').'">'.$this->btnDelete.'</a><a href="'.url('departments/'.$arr->id.'/show').'">'.$this->btnDetails.'</a></nobr>',
            );
        }

        $config = [
            'data' => $data,
            'order' => [[0, 'asc']],
            'columns' => [null, null, null, null],
        ];

        return view('modules.attendances.list', [
            'heads' => $heads,
            'config' => $config,
            'title' => $this->title,
            'table_title' => $this->title.' List for '.Carbon::today()->format('d/m/Y'),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
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
