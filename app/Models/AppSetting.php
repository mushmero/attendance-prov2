<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class AppSetting extends Model
{
    use HasFactory, LogsActivity;

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
        ->useLogName('AppSetting')
        ->logOnly([
            'parameter',
            'value',
            'user_id',
            'created_at',
            'updated_at',
        ]);
    } 
    protected $table = 'module_appsetting';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'parameter',
        'value',
        'user_id',
    ];

    public function user()
    {
        return $this->hasOne('Mushmero\Lapdash\Models\User','id','user_id')->withTrashed();
    }
}
