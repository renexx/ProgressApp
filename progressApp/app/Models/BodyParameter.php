<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BodyParameter extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'body_parameter_name',
        'user_id',
        'unit'
    ];
    protected $table = 'body_parameters';
    protected $primaryKey = 'body_parameter_id';

    public function getCreatedAtAttribute($value)
    {
        return date('j M Y, G:i', strtotime($value));
    }
    public function getUpdatedAtAttribute($value)
    {
        return date('j M Y, G:i', strtotime($value));
    }
    public function user()
    {
        return $this->belongsTo("App\User");
    }
    public function log()
    {
        return $this->hasMany("App\Log");
    }
    protected $casts = [
        'user_id' => 'integer'
    ];
}
