<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class Attendance extends Model
{
    use HasFactory, LogsActivity;

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
        ->useLogName('Attendance')
        ->logOnly([
            'people_no',
            'clock_in',
            'clock_out',
            'status',
            'created_at',
            'updated_at',
        ]);
    } 
    protected $table = 'module_attendances';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'people_no',
        'clock_in',
        'clock_out',
        'status',
    ];

    protected $dates = ['clock_in','clock_out'];
}
