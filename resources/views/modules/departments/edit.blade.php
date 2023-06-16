@extends('lapdash::page')

@section(config('adminlte.title'), 'AdminLTE')

@section('content_header')
    <h1 class="m-0 text-dark">{{ $title }}</h1>
@stop

@section('content')
    <div class="row">
        <div class="col-12">
            @include('flash::message')
            <form class="form" action="{{ route('department.update',$data->id) }}" method="post">
                @method('put')
                @csrf
                    <x-adminlte-card title="{{ $table_title }} {{ $data->name }}" theme="default" icon="fas fa-sm fa-rocket" collapsible>
                        <x-adminlte-input name="name" label="Department Name" fgroup-class="col-md-8 row" label-class="col-md-2 control-label" igroup-class="col-md-10" value="{{ $data->name }}" required enable-old-support/>
                        <x-adminlte-input name="tag" label="Department Tag" fgroup-class="col-md-8 row" label-class="col-md-2 control-label" igroup-class="col-md-10" value="{{ $data->tag }}" required enable-old-support/>
                        <x-slot name="footerSlot">
                            <a href="{{ route('department') }}">
                                <x-adminlte-button class="btn-flat" label="{{ __('adminlte::adminlte.back') }}" theme="default" icon="fas fa-chevron-left"/>
                            </a>
                            <x-adminlte-button class="btn-flat" type="submit" label="Submit" theme="success" icon="fas fa-lg fa-save"/>
                        </x-slot>
                </x-adminlte-card>
            </form>
        </div>
    </div>
@stop