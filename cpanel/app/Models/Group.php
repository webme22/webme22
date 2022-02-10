<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Group extends Model
{
    use HasFactory;
    public function emails(){
        return $this->hasMany(GroupEmail::class);
    }
    public function country(){
        return $this->belongsTo(Country::class);
    }
    public function language(){
        return $this->belongsTo(Language::class, 'lang_id');
    }
}
