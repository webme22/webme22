<?php
use Illuminate\Database\Eloquent\Model as Eloquent;
class Topup extends Eloquent
{
    protected $table = 'topups';
	protected $guarded = [];
    public function scopeMedia($query){
        $query->where(['type'=>'media']);
    }
    public function scopeNodes($query){
        $query->where(['type'=>'nodes']);
    }
    public function family(){
        return $this->belongsTo('Family');
    }
    public function payment(){
        return $this->hasOne('Payment', 'id', 'payment_id');
    }
    public function scopeConfirmed($query){
        $query->whereHas('payment', function (Illuminate\Database\Eloquent\Builder $q){
            $q->where(['confirmed'=>true]);
	});
    }
}
