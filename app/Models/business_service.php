<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class business_service extends Model
{
    protected $table = 'business_service';
    protected $primaryKey = 'id';
    public $timestamps = false;

    protected $fillable = [
        'name',
        'description',
        'lat',
        'lang',
    ];

    protected $attributes = [
        'lat' => 0,
        'lang' => 0,
        // Add default values for other fields here
    ];

    // Add any additional model logic or relationships here
}
