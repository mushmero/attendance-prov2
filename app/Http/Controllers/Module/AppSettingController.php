<?php

namespace App\Http\Controllers\Module;

use App\Http\Controllers\Controller;
use App\Models\AppSetting;
use Illuminate\Http\Request;

class AppSettingController extends Controller
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
        $this->title = 'App Settings';
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $data = array();
        $array = AppSetting::all();

        $heads = [
            'Parameter',
            'Value',
            'User',
            ['label' => 'Actions', 'no-export' => true, 'width' => 5],
        ];

        foreach($array as $arr){
            $data[] = array(
                $arr->parameter,
                $arr->value,
                $arr->user->name,
                '<nobr><a href="'.url('app_setting/'.$arr->id.'/edit').'">'.$this->btnEdit.'</a><a href="'.url('app_setting/'.$arr->id.'/delete').'">'.$this->btnDelete.'</a><a href="'.url('app_setting/'.$arr->id.'/show').'">'.$this->btnDetails.'</a></nobr>',
            );
        }

        $config = [
            'data' => $data,
            'order' => [[0, 'asc']],
            'columns' => [null, null, null, null],
        ];

        return view('modules.appsetting.list', [
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
        return view('modules.appsetting.create', [
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
            'parameter' => 'required|unique:module_appsetting',
        ]);

        $data = $request->all();

        $store = AppSetting::create([
            'parameter' => $data['parameter'],
            'value' => $data['value'],
            'user_id' => auth()->user()->id,
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
        $data = AppSetting::where('id', $id)->firstOrFail();

        return view('modules.appsetting.show', [
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
        $data = AppSetting::where('id', $id)->firstOrFail();

        return view('modules.appsetting.edit', [
            'data' => $data,
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
            'parameter' => 'required|unique:module_appsetting',
        ]);

        $data['parameter'] = $request->input('parameter');
        $data['value'] = $request->input('value');
        $data['user_id'] = auth()->user()->id;

        $update = AppSetting::find($id)->update($data);

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
        AppSetting::find($id)->delete();
        flash(
            $this->title.' deleted successfully',
        )->success();
        return redirect()->route('app_setting');
    }
}
