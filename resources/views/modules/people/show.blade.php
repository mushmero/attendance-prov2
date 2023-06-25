@extends('lapdash::page')

@section(config('adminlte.title'), 'AdminLTE')

@section('content_header')
    <h1 class="m-0 text-dark">{{ $title }}</h1>
@stop

@section('content')
    <div class="row">
        <div class="col-12">
            <x-adminlte-card title="{{ $table_title }} " theme="default" icon="fas fa-sm fa-user-alt" collapsible>
                <div class="row">
                    <div class="col-12 col-md-6">
                        <div class="row">
                            <label for="people_no" class="col-md-2">People No</label>
                            <div class="col-md-10">
                                <span class="text-bold h4">{{ $data->people_no }}</span>
                            </div> 
                        </div>
                    </div>
                    <div class="col-12 col-md-6">&nbsp;</div>
                    <div class="col-12 col-md-6">                    
                        <x-adminlte-input name="fullname" label="Full Name" fgroup-class="row" label-class="col-md-2 control-label" igroup-class="col-md-10" value="{{ $data->fullname }}" readonly/>
                        <x-adminlte-input name="address" label="Address" fgroup-class="row" label-class="col-md-2 control-label" igroup-class="col-md-10" value="{{ $data->address }}" readonly/>
                        <x-adminlte-input name="postal_code" label="Postal Code" fgroup-class="row" label-class="col-md-2 control-label" igroup-class="col-md-10" value="{{ $data->postal_code }}" readonly/>
                        <x-adminlte-input name="country" label="Country" fgroup-class="row" label-class="col-md-2 control-label" igroup-class="col-md-10" value="{{ $data->country ? \App\Helpers\Helper::getCountryByCca2($data->country)->name : '' }}" readonly/>
                        <x-adminlte-input name="department" label="Department" fgroup-class="row" label-class="col-md-2 control-label" igroup-class="col-md-10" value="{{ $data->department ? $data->department->name : '' }}" readonly/>
                    </div>
                    <div class="col-12 col-md-6">
                        <x-adminlte-input name="gender" label="Gender" fgroup-class="row" label-class="col-md-2 control-label" igroup-class="col-md-10" value="{{ $data->gender }}" readonly/>
                        <x-adminlte-input name="city" label="City" fgroup-class="row" label-class="col-md-2 control-label" igroup-class="col-md-10" value="{{ $data->city }}" readonly/>
                        <x-adminlte-input name="state" label="State" fgroup-class="row" label-class="col-md-2 control-label" igroup-class="col-md-10" value="{{ $data->state }}" readonly/>
                        <x-adminlte-input name="level" label="Level" fgroup-class="row" label-class="col-md-2 control-label" igroup-class="col-md-10" value="{{ $data->level ? $data->level->name : '' }}" readonly/>
                        <x-adminlte-input name="unit" label="Unit" fgroup-class="row" label-class="col-md-2 control-label" igroup-class="col-md-10" value="{{ $data->unit ? $data->unit->name : '' }}" readonly/>
                    </div>
                </div>
                <div class="row">
                    <div class="col-12">                        
                        <x-adminlte-card title="{{ $table_kpi_title }} for {{ $data->people_no }}" theme="default" header-class="bg-gray" icon="fas fa-sm fa-chart-line" collapsible="collapsed">
                            <div class="row">
                                <div class="col-12 col-md-3">
                                    <x-adminlte-info-box title="Normal (Total)" class="text-bold" text="{{ $performance['total_normal'] }}" icon="fas fa-lg fa-calendar-alt" icon-theme="gradient-success"/>
                                </div>
                                <div class="col-12 col-md-3">
                                    <x-adminlte-info-box title="Late (Total)" class="text-bold" text="{{ $performance['total_late'] }}" icon="fas fa-lg fa-calendar-alt" icon-theme="gradient-danger"/>
                                </div>
                                <div class="col-12 col-md-3">
                                    <x-adminlte-info-box title="Normal (Monthly)" class="text-bold" text="{{ $performance['month_normal'] }}" icon="fas fa-lg fa-calendar-alt" icon-theme="gradient-green"/>
                                </div>
                                <div class="col-12 col-md-3">
                                    <x-adminlte-info-box title="Late (Monthly)" class="text-bold" text="{{ $performance['month_late'] }}" icon="fas fa-lg fa-calendar-alt" icon-theme="gradient-red"/>
                                </div>
                            </div>
                        </x-adminlte-card>
                    </div>
                </div>
                <x-slot name="footerSlot">
                    <a href="{{ route('people') }}">
                        <x-adminlte-button class="btn-flat" label="{{ __('adminlte::adminlte.back') }}" theme="default" icon="fas fa-chevron-left"/>
                    </a>
                </x-slot>
            </x-adminlte-card>
        </div>
    </div>
@stop