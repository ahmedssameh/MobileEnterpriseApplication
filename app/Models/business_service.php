<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class business_service extends Model
{
 use HasFactory;
    protected $table = 'business_service';
    protected $primaryKey = 'id';
    public $timestamps = false;

    protected $fillable = [
        'name',
        'description',
        'user_id',
    ];


    // Add any additional model logic or relationships here
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
