<?php

namespace App\Http\Controllers\Module;

use App\Helpers\Helper;
use App\Http\Controllers\Controller;
use App\Http\Traits\PeopleTraits;
use App\Models\Departments;
use App\Models\People;
use App\Models\Units;
use Illuminate\Http\Request;
use App\Models\Levels;

class PeopleController extends Controller
{
    use PeopleTraits;

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
        $this->title = 'People';
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $data = array();
        $array = People::where(['user_id' => auth()->user()->id])->get();

        $heads = [
            'Fullname',
            'Level',
            'Department',
            'Unit',
            'ID No',
            ['label' => 'Actions', 'no-export' => true, 'width' => 5],
        ];

        foreach($array as $arr){
            $data[] = array(
                $arr->fullname,
                $arr->level->name,
                $arr->department->name,
                $arr->unit->name,
                $arr->people_no,
                '<nobr><a href="'.url('people/'.$arr->id.'/edit').'">'.$this->btnEdit.'</a><a href="'.url('people/'.$arr->id.'/delete').'">'.$this->btnDelete.'</a><a href="'.url('people/'.$arr->id.'/show').'">'.$this->btnDetails.'</a></nobr>',
            );
        }

        $config = [
            'data' => $data,
            'order' => [[0, 'asc']],
            'columns' => [null, null, null, null, null, null],
        ];

        return view('modules.people.list', [
            'heads' => $heads,
            'config' => $config,
            'title' => $this->title,
            'table_title' => $this->title.' List',
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $configGender = [
            "placeholder" => "Select gender",
            "allowClear" => true,
        ];
        $gender = ['Male','Female','Prefer Not To Say'];

        $configCountry = [
            "placeholder" => "Select country",
            "allowClear" => true,
        ];
        $countries = Helper::getCountryList();

        $configLevel = [
            "placeholder" => "Select level",
            "allowClear" => true,
        ];
        $levels = Levels::all();

        $configDepartment = [
            "placeholder" => "Select department",
            "allowClear" => true,
        ];
        $departments = Departments::all();

        $configUnit = [
            "placeholder" => "Select unit",
            "allowClear" => true,
        ];
        $units = Units::all();

        return view('modules.people.create', [
            'gender' => $gender,
            'configGender' => $configGender,
            'countries' => $countries,
            'configCountry' => $configCountry,
            'levels' => $levels,
            'configLevel' => $configLevel,
            'departments' => $departments,
            'configDepartment' => $configDepartment,
            'units' => $units,
            'configUnit' => $configUnit,
            'title' => $this->title,
            'table_title' => 'Create '.$this->title,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'fullname' => 'required',
            'level' => 'required',
        ]);

        $data = $request->all();

        $store = People::create([
            'fullname' => $data['fullname'],
            'gender' => $data['gender'],
            'address' => $data['address'],
            'city' => $data['city'],
            'postal_code' => $data['postal_code'],
            'state' => $data['state'],
            'country' => $data['country'],
            'level_id' => $data['level'],
            'department_id' => $data['department'],
            'unit_id' => $data['unit'],
            'user_id' => auth()->user()->id,
            'people_no' => $this->peopleNo('MY',$data['department'],$data['unit']),
        ]);

        if($store){
            flash(
                $this->title.' created successfully',
            )->success();
        }else{
            flash(
                'Unable to create '.$this->title,
            )->error();
        }

        return back();
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $data = People::where('id', $id)->firstOrFail();

        return view('modules.people.show', [
            'data' => $data,
            'title' => $this->title,
            'table_title' => 'Detail '.$this->title,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $data = People::where('id', $id)->firstOrFail();

        $configGender = [
            "placeholder" => "Select gender",
            "allowClear" => true,
        ];
        $gender = ['Male','Female','Prefer Not To Say'];

        $configCountry = [
            "placeholder" => "Select country",
            "allowClear" => true,
        ];
        $countries = Helper::getCountryList();

        $configLevel = [
            "placeholder" => "Select level",
            "allowClear" => true,
        ];
        $levels = Levels::all();

        $configDepartment = [
            "placeholder" => "Select department",
            "allowClear" => true,
        ];
        $departments = Departments::all();

        $configUnit = [
            "placeholder" => "Select unit",
            "allowClear" => true,
        ];
        $units = Units::all();

        return view('modules.people.edit', [
            'data' => $data,
            'gender' => $gender,
            'configGender' => $configGender,
            'countries' => $countries,
            'configCountry' => $configCountry,
            'levels' => $levels,
            'configLevel' => $configLevel,
            'departments' => $departments,
            'configDepartment' => $configDepartment,
            'units' => $units,
            'configUnit' => $configUnit,
            'title' => $this->title,
            'table_title' => 'Edit '.$this->title,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $this->validate($request, [
            'fullname' => 'required',
            'level' => 'required',
        ]);

        $data['fullname'] = $request->input('fullname');
        $data['gender'] = $request->input('gender');
        $data['address'] = $request->input('address');
        $data['city'] = $request->input('city');
        $data['postal_code'] = $request->input('postal_code');
        $data['state'] = $request->input('state');
        $data['country'] = $request->input('country');
        $data['level_id'] = $request->input('level');
        $data['department_id'] = $request->input('department');
        $data['unit_id'] = $request->input('unit');
        $data['user_id'] = auth()->user()->id;

        $update = People::find($id)->update($data);

        if($update){
            flash(
                $this->title.' updated successfully',
            )->success();
        }else{
            flash(
                'Unable to update '.$this->title,
            )->error();
        }

         return back();
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        People::find($id)->delete();
        flash(
            $this->title.' deleted successfully',
        )->success();
        return redirect()->route('units');
    }
}
