<?php
use Illuminate\Database\Eloquent\Model as Eloquent;
class QuestionCategory extends Eloquent
{
	protected $guarded = [];
	protected $table = "questions_categories";
	public function questions(){
		return $this->hasMany('Question', 'category_id', 'id');
	}
}
