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
        'user_id',
    ];


    // Add any additional model logic or relationships here
    public function userr()
    {
        return $this->belongsTo(User::class);
    }
}
