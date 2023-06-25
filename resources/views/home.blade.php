@extends('lapdash::page')

@section(config('adminlte.title'), 'AdminLTE')

@section('content_header')
    <h1 class="m-0 text-dark">{{ $title }}</h1>
@stop
@section('plugins.BsDatepicker', true)
@section('plugins.Chartjs', true)

@section('content')
    @php
        $callout_count = count($callouts);
    @endphp
    <div class="row">
        @foreach ($callouts as $callout)
            @if ($callout_count % 4 === 0)
                <div class="col-3">
                    <x-adminlte-callout theme="{{ $callout['theme'] }}" class="{{ $callout['class'] }}" title="{{ $callout['title'] }}" title-class="{{ $callout['title-class'] }}" icon="{{ $callout['icon'] }}">
                        {!! $callout['value'] !!}
                    </x-adminlte-callout>
                </div>
            @endif  
        @endforeach
    </div>
    <div class="row">
        <div class="col-12">
            <x-adminlte-card title="{{ $statCard['title'] }}" theme="{{ $statCard['theme'] }}" icon="{{ $statCard['icon'] }}" collapsible>
                <div class="row">
                    <div class="col-4"></div>
                    <div class="col-4">
                        <x-adminlte-select2 id="filter" name="filter" label="Filter" fgroup-class="row" label-class="col-md-2 control-label"  igroup-class="col-md-10" igroup-size="md" enable-old-support>
                            @foreach ($range as $single)
                                <option value="{{ $single }}" {{ isset($_GET['filter']) ? ($_GET['filter'] == $single ? 'selected' : '') : '' }}>{{ $single }}</option>
                            @endforeach
                        </x-adminlte-select2>
                    </div>
                    <div class="col-4">
                        <div class="customDate">
                            <div class="form-group row">
                                <label for="fromDate" class="col-md-2 control-label">From</label>
                                <div class="col-md-10 input-group date">
                                    <input id="fromDate" type="text" name="fromDate" class="form-control filterdate" value="{{ isset($_GET['fromDate']) ? $_GET['fromDate'] : '' }}" onchange="lineChartData()">
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
                                    <input id="toDate" type="text" name="toDate" class="form-control filterdate" value="{{ isset($_GET['toDate']) ? $_GET['toDate'] : '' }}" onchange="lineChartData()">
                                    <div class="input-group-append">
                                        <button type="button" class="btn btn-outline-light">
                                            <i class="fas fa-lg fa-calendar"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div style="position: relative; height:40vh; width:83vw"><canvas id="statistic" height="80"></canvas></div>
                <x-slot name="footerSlot"></x-slot>
            </x-adminlte-card>
        </div>
    </div>
@stop
