<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JobStatus extends Model
{
    use HasFactory;
    protected $guarded = [];
    protected $casts = [
        'status' => 'boolean'
    ];
    public function scopeSuccess($query){
        $query->where(['status'=>true]);
    }
    public function scopeFail($query){
        $query->where(['status'=>false]);
    }
}
