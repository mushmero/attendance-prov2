@extends('lapdash::page')

@section(config('adminlte.title'), 'AdminLTE')

@section('content_header')
    <h1 class="m-0 text-dark">{{ $title }}</h1>
@stop

@section('content')
    <div class="row">
        <div class="col-12">
            @include('flash::message')
            <form class="form" action="{{ route('app_setting.update',$data->id) }}" method="post">
                @method('put')
                @csrf
                    <x-adminlte-card title="{{ $table_title }} {{ $data->parameter }}" theme="default" icon="fas fa-sm fa-toolbox" collapsible>
                        <x-adminlte-input name="parameter" label="Parameter" fgroup-class="col-md-6 row" label-class="col-md-2 control-label" igroup-class="col-md-10" value="{{ $data->parameter }}" required enable-old-support/>
                        <x-adminlte-input name="value" label="Value" fgroup-class="col-md-6 row" label-class="col-md-2 control-label" igroup-class="col-md-10" value="{{ $data->value }}" required enable-old-support/>
                        <x-slot name="footerSlot">
                            <a href="{{ route('app_setting') }}">
                                <x-adminlte-button class="btn-flat" label="{{ __('adminlte::adminlte.back') }}" theme="default" icon="fas fa-chevron-left"/>
                            </a>
                            <x-adminlte-button class="btn-flat" type="submit" label="Submit" theme="success" icon="fas fa-lg fa-save"/>
                        </x-slot>
                </x-adminlte-card>
            </form>
        </div>
    </div>
@stop