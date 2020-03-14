<?php namespace App;

use Illuminate\Database\Eloquent\Model as Eloquent;

class Benefit extends Eloquent
{
    protected $table = 'benefits';
    protected $primaryKey = 'id';

    public function policyBenefits() {
        return $this->hasMany('App\PolicyBenefit');
    }
}