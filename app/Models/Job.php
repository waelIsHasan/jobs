<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Job extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'body',
        'required_skills',
        'location',
        'dead_time',
        'owner_id'
    ];

    public function owner()
    {
        return $this->belongsTo(Owner::class);
    }

    public function applications(){
        return $this->hasMany(Application::class)->cascadeOnDelete();
        
    }

  
}
