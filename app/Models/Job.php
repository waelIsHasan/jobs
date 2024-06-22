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
        'owner_id',
        'category_id'
    ];

    public function scopeSearsh($query,$searsh){
        if(!empty($searsh['title'])){
            $query->where('title','like',"%{$searsh['title']}%");
        }
        if(!empty($searsh['location'])){
            $query->where('location','like',"%{$searsh['location']}%");
        }
        if(!empty($searsh['dead_time'])){
            $query->where('dead_time','like',"%{$searsh['dead_time']}%");
        }
 
    }
    public function owner()
    {
        return $this->belongsTo(Owner::class);
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function applications(){
        return $this->hasMany(Application::class)->cascadeOnDelete();
        
    }

  
}
