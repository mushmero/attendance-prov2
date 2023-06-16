@extends('lapdash::page')

@section(config('adminlte.title'), 'AdminLTE')

@section('content_header')
    <h1 class="m-0 text-dark">{{ $title }}</h1>
@stop

@section('content')
    <div class="row">
        <div class="col-12">
            <x-adminlte-card title="{{ $table_title }} " theme="default" icon="fas fa-sm fa-rocket" collapsible>
                <x-adminlte-input name="name" label="Full Name" fgroup-class="row" label-class="col-md-2 control-label" igroup-class="col-md-10" value="{{ $data->fullname }}" readonly/>
                <x-adminlte-input name="gender" label="Gender" fgroup-class="row" label-class="col-md-2 control-label" igroup-class="col-md-10" value="{{ $data->gender }}" readonly/>
                <x-adminlte-input name="address" label="Address" fgroup-class="row" label-class="col-md-2 control-label" igroup-class="col-md-10" value="{{ $data->address }}" readonly/>
                <x-adminlte-input name="city" label="City" fgroup-class="row" label-class="col-md-2 control-label" igroup-class="col-md-10" value="{{ $data->city }}" readonly/>
                <x-adminlte-input name="postal_code" label="Postal Code" fgroup-class="row" label-class="col-md-2 control-label" igroup-class="col-md-10" value="{{ $data->postal_code }}" readonly/>
                <x-adminlte-input name="state" label="State" fgroup-class="row" label-class="col-md-2 control-label" igroup-class="col-md-10" value="{{ $data->state }}" readonly/>
                <x-adminlte-input name="country" label="Country" fgroup-class="row" label-class="col-md-2 control-label" igroup-class="col-md-10" value="{{ Helper::getCountryByCca2($data->country)->name }}" readonly/>
                <x-adminlte-input name="level" label="Level" fgroup-class="row" label-class="col-md-2 control-label" igroup-class="col-md-10" value="{{ $data->level->name }}" readonly/>
                <x-adminlte-input name="department" label="Department" fgroup-class="row" label-class="col-md-2 control-label" igroup-class="col-md-10" value="{{ $data->department->name }}" readonly/>
                <x-adminlte-input name="unit" label="Unit" fgroup-class="row" label-class="col-md-2 control-label" igroup-class="col-md-10" value="{{ $data->unit->name }}" readonly/>       
                <x-slot name="footerSlot">
                    <a href="{{ route('people') }}">
                        <x-adminlte-button class="btn-flat" label="{{ __('adminlte::adminlte.back') }}" theme="default" icon="fas fa-chevron-left"/>
                    </a>
                </x-slot>
            </x-adminlte-card>
        </div>
    </div>
@stop