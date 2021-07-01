<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Log extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'value',
        'activity_id',
        'body_parameter_id'
    ];
    protected $table = 'log';
    protected $primaryKey = 'log_id';

    public function getCreatedAtAttribute($value)
    {
        return date('j M Y, G:i', strtotime($value));
    }
    public function getUpdatedAtAttribute($value)
    {
        return date('j M Y, G:i', strtotime($value));
    }
    public function activity()
    {
        return $this->belongsTo("App\Activity");
    }
    public function bodyParameter()
    {
        return $this->belongsTo("App\BodyParameter");
    }
    protected $casts = [
        'value' => 'float',
        'activity_id' => 'integer',
        'body_parameter_id' => 'integer'
    ];
}
