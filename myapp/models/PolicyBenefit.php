<?php namespace App;

use Illuminate\Database\Eloquent\Model as Eloquent;

class PolicyBenefit extends Eloquent
{
    protected $table = 'policy_benefits';
    protected $primaryKey = 'id';
    protected $fillable = ['policy_id', 'benefits_id', 'user_id'];

    public function benefits() {
        return $this->belongsTo('App\Benefit');
    }
}