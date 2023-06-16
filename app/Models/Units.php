<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class Units extends Model
{
    use HasFactory, LogsActivity;

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
        ->useLogName('Units')
        ->logOnly([
            'name',
            'tag',
            'user_id',
            'created_at',
            'updated_at',
        ]);
    } 
    protected $table = 'module_units';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'tag',
        'user_id',
    ];

    public function user()
    {
        return $this->hasOne('Mushmero\Lapdash\Models\User','id','user_id')->withTrashed();
    }
}
