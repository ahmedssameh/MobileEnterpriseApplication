<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class business_service extends Model
{
    protected $table = 'your_table_name';
    protected $primaryKey = 'id';
    public $timestamps = false;

    protected $fillable = [
        'name',
        'description',
        'lat',
        'lang',
    ];

    // Add any additional model logic or relationships here
}
