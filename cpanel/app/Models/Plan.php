<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Plan extends Model
{
    use HasFactory;
    protected $table = 'plans';
    protected $casts = [
        'popular' => 'boolean',
        'highlight' => 'boolean',
    ];
    public function scopeActive($query){
        $query->where(['display'=>1]);
    }
    public function scopeHighlight($query){
        $query->where(['highlight'=>true]);
    }
    public function scopeConfirmed($query){
        $query->where(['price'=>0])->orWhereHas('payments', function ($q){
            $q->where(['confirmed'=>true]);
        });
    }
    public function payments(){
        return $this->belongsToMany('Payment', 'family_plans', 'plan_id', 'payment_id')
            ->whereDate('family_plans.end_date', '>=', date('Y-m-d'))
            ->orderBy('family_plans.end_date', 'desc')
            ->orderBy('family_plans.created_at', 'desc')
            ->withPivot('family_id', 'end_date');
    }
}
