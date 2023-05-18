<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class fav_service extends Model
{
    use HasFactory;

    protected $table = 'fav_service';

    protected $fillable = [
        'user_id',
        'service_id',
    ];

    protected $primaryKey = ['user_id', 'service_id'];

    public $incrementing = false;

    public function service()
    {
        return $this->belongsTo(business_service::class, 'service_id');
    }

}
