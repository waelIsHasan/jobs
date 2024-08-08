<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Service extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'price',
        'estimated_time',
        'freelancer_id',
        'category_id'
    ];

    public function freelancer()
    {
        return $this->belongsTo(Freelancer::class);
    }

    public function reviews(){
        return $this->hasMany(Review::class)->cascadeOnDelete();
        
    }
    
    public function scopeSearsh($query,$searsh){
        if(!empty($searsh['name'])){
            $query->where('name','like',"%{$searsh['name']}%");
        }
        if(!empty($searsh['price'])){
            $query->where('price','like',"%{$searsh['price']}%");
        }
        if(!empty($searsh['description'])){
            $query->where('description','like',"%{$searsh['description']}%");
        }
 
    }

}

