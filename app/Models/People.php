<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class People extends Model
{
    use HasFactory, LogsActivity;

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
        ->useLogName('People')
        ->logOnly([
            'fullname',
            'gender',
            'address',
            'city',
            'postal_code',
            'state',
            'country',
            'level_id',
            'department_id',
            'unit_id',
            'user_id',
            'people_no',
            'created_at',
            'updated_at',
        ]);
    } 
    protected $table = 'module_people';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'fullname',
        'gender',
        'address',
        'city',
        'postal_code',
        'state',
        'country',
        'level_id',
        'department_id',
        'unit_id',
        'user_id',
        'people_no',
    ];

    public function user()
    {
        return $this->hasOne('Mushmero\Lapdash\Models\User','id','user_id')->withTrashed();
    }

    public function level()
    {
        return $this->hasOne(Levels::class,'id','level_id');
    }

    public function department()
    {
        return $this->hasOne(Departments::class,'id','department_id');
    }

    public function unit()
    {
        return $this->hasOne(Units::class,'id','unit_id');
    }

    public function attendance()
    {
        return $this->hasMany(Attendance::class, 'people_no','people_no');
    }
}
