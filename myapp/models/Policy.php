<?php namespace App;

use Illuminate\Database\Eloquent\Model as Eloquent;
use App\Session;
use App\Redirect;

class Policy extends Eloquent
{
    protected $table = 'policy';
    protected $fillable = ['name', 'face_amount', 'commission', 'excess_premium', 'type', 'created_by', 'updated_at', 'updated_by'];
    public static function add($request) {
        self::create([
            'name' => $request['policy'],
            'face_amount' => $request['face_amount'],
            'commission' => $request['commission'],
            'excess_premium' => $request['excess_premium'],
            'type' => $request['type'],
            'created_by' => Session::get('user_id')
        ]);

        Session::flash('success', 'Successfully added new policy!');
        Redirect::to('policies.php');
    }

    public static function updatePolicy($request) {
        self::where('id', $request['policy_id'])
          ->update([
              'name' => $request['policy'],
              'face_amount' => $request['face_amount']
            ]);

        Session::flash('success', 'Successfully updated Policy');
        Redirect::to('policies.php');
    }

    public static function deletePolicy($id) {
        $find = self::find($id);
        $find->delete();

        Session::flash('success', 'Successfully deleted product');
        Redirect::to('policies.php');
    }
}
