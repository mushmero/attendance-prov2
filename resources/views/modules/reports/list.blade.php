@extends('lapdash::page')

@section(config('adminlte.title'), 'AdminLTE')

@section('content_header')
    <h1 class="m-0 text-dark">{{ $title }}</h1>
@stop
@section('plugins.BsDatepicker', true)

@section('content')
    <div class="row">
        <div class="col-12">
            <x-adminlte-card title="{{ $table_title }}" theme="default" icon="fas fa-sm fa-file" collapsible>
                <form method="get" id="attendanceForm">
                    <div class="row">
                        <div class="col-3"></div>
                        <div class="col-3">
                            <x-adminlte-select2 id="filter" name="filter" label="Filter" fgroup-class="row" label-class="col-md-2 control-label"  igroup-class="col-md-10" igroup-size="md" enable-old-support>
                                @foreach ($range as $single)
                                    <option value="{{ $single }}" {{ isset($_GET['filter']) ? ($_GET['filter'] == $single ? 'selected' : '') : '' }}>{{ $single }}</option>
                                @endforeach
                            </x-adminlte-select2>
                        </div>
                        <div class="col-3">
                            <div class="customDate">
                                <div class="form-group row">
                                    <label for="fromDate" class="col-md-2 control-label">From</label>
                                    <div class="col-md-10 input-group date">
                                        <input id="fromDate" type="text" name="fromDate" class="form-control filterdate" value="{{ isset($_GET['fromDate']) ? $_GET['fromDate'] : '' }}" onchange="defaultDateRangeFilter()">
                                        <div class="input-group-append">
                                            <button type="button" class="btn btn-outline-light">
                                                <i class="fas fa-lg fa-calendar"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="toDate" class="col-md-2 control-label">To</label>
                                    <div class="col-md-10 input-group date">
                                        <input id="toDate" type="text" name="toDate" class="form-control filterdate" value="{{ isset($_GET['toDate']) ? $_GET['toDate'] : '' }}" onchange="defaultDateRangeFilter()">
                                        <div class="input-group-append">
                                            <button type="button" class="btn btn-outline-light">
                                                <i class="fas fa-lg fa-calendar"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                                {{-- <x-adminlte-input-date id="fromDate" name="fromDate" :config="$configDateFilter" placeholder="Choose a date..." label="From" label-class="text-primary" fgroup-class="row" label-class="col-md-2 control-label"  igroup-class="col-md-10" value="{{ isset($_GET['fromDate']) ? $_GET['fromDate'] : '' }}" class="filterdate" onchange="defaultDateRangeFilter()">
                                    <x-slot name="appendSlot">
                                        <x-adminlte-button theme="outline-primary" icon="fas fa-lg fa-calendar"
                                            title="From Date"/>
                                    </x-slot>
                                </x-adminlte-input-date>
                                <x-adminlte-input-date id="toDate" name="toDate" :config="$configDateFilter" placeholder="Choose a date..." label="To" label-class="text-primary" fgroup-class="row" label-class="col-md-2 control-label"  igroup-class="col-md-10" value="{{ isset($_GET['toDate']) ? $_GET['toDate'] : '' }}" class="filterdate" onchange="defaultDateRangeFilter()">
                                    <x-slot name="appendSlot">
                                        <x-adminlte-button theme="outline-primary" icon="fas fa-lg fa-calendar"
                                            title="To Date"/>
                                    </x-slot>
                                </x-adminlte-input-date> --}}
                            </div>
                        </div>
                        <div class="col-3">
                            <button id="reportExport" class="btn btn-block bg-primary col-6 {{ !Mushmero\Lapdash\Helpers\Helper::urlHasPermission('reports')  ? 'hide' : ''}}" style="float:right;"><i class="fas fa-fw fa-print"></i> {{ __('adminlte::adminlte.export')}}</<button>
                        </div>
                    </div>
                </form>
                <x-adminlte-datatable id="attendanceReport" :heads="$heads" :config="$config" head-theme="dark" striped hoverable compressed />
            </x-adminlte-card>
        </div>
    </div>
@stop