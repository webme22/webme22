<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MailTask extends Model
{
    use HasFactory;
    protected $fillable = ['group_id'];
    public function job_statuses(){
        return $this->hasMany(JobStatus::class);
    }
    public function group(){
        return $this->belongsTo(Group::class);
    }
}
