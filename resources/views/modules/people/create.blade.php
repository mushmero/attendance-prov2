@extends('lapdash::page')

@section(config('adminlte.title'), 'AdminLTE')

@section('content_header')
    <h1 class="m-0 text-dark">{{ $title }}</h1>
@stop

@section('content')
    <div class="row">
        <div class="col-12">
            @include('flash::message')
            <form class="form" action="{{ route('people.store') }}" method="post">
                @csrf
                <x-adminlte-card title="{{ $table_title }}" theme="default" icon="fas fa-sm fa-user-alt" collapsible>
                    <div class="row">
                        <div class="col-12 col-md-6">
                            <x-adminlte-input name="name" label="Full Name" fgroup-class="row" label-class="col-md-2 control-label" igroup-class="col-md-10" required enable-old-support/>
                            <x-adminlte-input name="address" label="Address" fgroup-class="row" label-class="col-md-2 control-label" igroup-class="col-md-10" enable-old-support/>
                            <x-adminlte-input name="postal_code" label="Postal Code" fgroup-class="row" label-class="col-md-2 control-label" igroup-class="col-md-10" enable-old-support/>
                            <x-adminlte-select2 id="country" name="country" label="Country" fgroup-class="row" label-class="col-md-2 control-label"  igroup-class="col-md-10" igroup-size="md" :config="$configCountry" enable-old-support>
                                <option/>
                                @foreach ($countries as $country_code => $country_name)
                                    <option value="{{ $country_code }}">{{ $country_name }}</option>
                                @endforeach
                            </x-adminlte-select2>
                            <x-adminlte-select2 id="department" name="department" label="Department" fgroup-class="row" label-class="col-md-2 control-label"  igroup-class="col-md-10" igroup-size="md" :config="$configDepartment" enable-old-support>
                                <option/>
                                @foreach ($departments as $department)
                                    <option value="{{ $department->id }}">{{ $department->name }}</option>
                                @endforeach
                            </x-adminlte-select2>
                        </div>
                        <div class="col-12 col-md-6">
                            <x-adminlte-select2 id="gender" name="gender" label="Gender" fgroup-class="row" label-class="col-md-2 control-label"  igroup-class="col-md-10" igroup-size="md" :config="$configGender" enable-old-support>
                                <option/>
                                @foreach ($gender as $oneGender)
                                    <option value="{{ $oneGender }}">{{ $oneGender }}</option>
                                @endforeach
                            </x-adminlte-select2>
                            <x-adminlte-input name="city" label="City" fgroup-class="row" label-class="col-md-2 control-label" igroup-class="col-md-10" enable-old-support/>
                            <x-adminlte-input name="state" label="State" fgroup-class="row" label-class="col-md-2 control-label" igroup-class="col-md-10" enable-old-support/>
                            <x-adminlte-select2 id="level" name="level" label="Level" fgroup-class="row" label-class="col-md-2 control-label"  igroup-class="col-md-10" igroup-size="md" :config="$configLevel" enable-old-support required>
                                <option/>
                                @foreach ($levels as $level)
                                    <option value="{{ $level->id }}">{{ $level->name }}</option>
                                @endforeach
                            </x-adminlte-select2>
                            <x-adminlte-select2 id="unit" name="unit" label="Unit" fgroup-class="row" label-class="col-md-2 control-label"  igroup-class="col-md-10" igroup-size="md" :config="$configUnit" enable-old-support>
                                <option/>
                                @foreach ($units as $unit)
                                    <option value="{{ $unit->id }}">{{ $unit->name }}</option>
                                @endforeach
                            </x-adminlte-select2>
                        </div>
                    </div>
                    <x-slot name="footerSlot">
                        <a href="{{ route('people') }}">
                            <x-adminlte-button class="btn-flat" label="{{ __('adminlte::adminlte.back') }}" theme="default" icon="fas fa-chevron-left"/>
                        </a>
                        <x-adminlte-button class="btn-flat" type="submit" label="Submit" theme="success" icon="fas fa-lg fa-save"/>
                    </x-slot>
                </x-adminlte-card>
            </form>
        </div>
    </div>
@stop