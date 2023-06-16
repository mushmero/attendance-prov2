@extends('lapdash::page')

@section(config('adminlte.title'), 'AdminLTE')

@section('content_header')
    <h1 class="m-0 text-dark">{{ $title }}</h1>
@stop

@section('content')
    <div class="row">
        <div class="col-12">
            <x-adminlte-card title="{{ $table_title }} " theme="default" icon="fas fa-sm fa-tasks" collapsible>
                <x-adminlte-input name="name" label="Level Name" fgroup-class="col-md-8 row" label-class="col-md-2 control-label" igroup-class="col-md-10" value="{{ $data->name }}" readonly/>
                <x-adminlte-input name="tag" label="Level Tag" fgroup-class="col-md-8 row" label-class="col-md-2 control-label" igroup-class="col-md-10" value="{{ $data->tag }}" readonly/>
                <x-adminlte-input name="user" label="User" fgroup-class="col-md-6 row" label-class="col-md-2 control-label" igroup-class="col-md-10" value="{{ $data->user->name }}" readonly/>              
                <x-slot name="footerSlot">
                    <a href="{{ route('levels') }}">
                        <x-adminlte-button class="btn-flat" label="{{ __('adminlte::adminlte.back') }}" theme="default" icon="fas fa-chevron-left"/>
                    </a>
                </x-slot>
            </x-adminlte-card>
        </div>
    </div>
@stop