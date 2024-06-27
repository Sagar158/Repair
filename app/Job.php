<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Job extends Model
{
    public function customer(){
        return $this->belongsTo('App\Customer');
    }

    public function user(){
        return $this->belongsTo('App\User','assigned_to');
    }

    public function booked_by(){
        return $this->belongsTo('App\User','user_id');
    }

    public function _despatched_by(){
        return $this->belongsTo('App\User','despatched_by');
    }

    public function comments(){
        return $this->hasMany('App\Comment')->orderBy('id','desc');
    }
}
