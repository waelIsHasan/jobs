<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Application extends Model
{
    use HasFactory;

    protected $fillable = [
        'resume',
        'status',
        'email',
        'name',
        'freelancer_id',
        'job_id',
    ];

    public function jobPosting(){
        return $this->belongsTo(Job::class);
    }

    public function freelancer(){
        return $this->belongsTo(freelancer::class);
    }
}
