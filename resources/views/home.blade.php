@extends('lapdash::page')

@section(config('adminlte.title'), 'AdminLTE')

@section('content_header')
    <h1 class="m-0 text-dark">Dashboard</h1>
@stop

@section('content')
    <div class="row">
        <div class="col-6">
            <x-adminlte-card title="Card 1" theme="default" icon="fas fa-lg fa-moon" collapsible>
                <x-slot name="toolsSlot"></x-slot>
                <x-slot name="footerSlot"></x-slot>
            </x-adminlte-card>
        </div>
        <div class="col-6">
            <x-adminlte-card title="Card 2" theme="default" icon="fas fa-lg fa-moon" collapsible>
                <x-slot name="toolsSlot"></x-slot>
                <x-slot name="footerSlot"></x-slot>
            </x-adminlte-card>
        </div>
    </div>
@stop
