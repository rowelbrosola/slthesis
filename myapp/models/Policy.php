<?php namespace App;

use Illuminate\Database\Eloquent\Model as Eloquent;
use App\Session;
use App\Redirect;

class Policy extends Eloquent
{
    protected $table = 'policy';
    protected $fillable = ['name', 'created_by', 'updated_at', 'updated_by'];
    public static function add($request) {
        self::create([
            'name' => $request['policy'],
            'created_by' => Session::get('user_id')
        ]);

        Session::flash('success', 'Successfully added new policy!');
        Redirect::to('policies.php');
    }
}
