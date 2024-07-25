<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Review extends Model
{
    use HasFactory;

    protected $fillable = ['seeker_id' , 'service_id','rating','comment'];

    public function seeker()
    {
        return $this->belongsTo(Seeker::class);
    }


    public function service()
    {
        return $this->belongsTo(Service::class);
    }
}
