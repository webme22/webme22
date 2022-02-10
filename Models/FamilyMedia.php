<?php
use Illuminate\Database\Eloquent\Model as Eloquent;
class FamilyMedia extends Eloquent
{
    protected $table = 'familyMedia';
    protected $guarded = [];
    public function scopeGallery($query){
	$query->where(['family_type'=>'Gallery']);
    }
    public function scopeImage($query){
	$query->where(['file_type'=>'Image']);
    }

    public function scopeToday($query)
	{
		return $query->where('date', 'like', '%'. date('Y-m-d') .'%');
	}

}
