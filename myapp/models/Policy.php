<?php namespace App;

use Illuminate\Database\Eloquent\Model as Eloquent;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Session;
use App\Redirect;

class Policy extends Eloquent
{
    use SoftDeletes;
    protected $table = 'policy';
    protected $fillable = ['name', 'commission', 'excess_premium', 'type', 'created_by', 'updated_at', 'updated_by'];
    public static function add($request) {
        self::create([
            'name' => $request['policy'],
            'commission' => $request['commission'],
            'excess_premium' => $request['excess_premium'],
            'type' => $request['type'],
            'created_by' => Session::get('user_id')
        ]);

        Session::flash('success', 'Successfully added new policy!');
        Redirect::to('products.php');
    }

    public static function updatePolicy($request) {
        self::where('id', $request['policy_id'])
          ->update([
              'name' => $request['policy'],
              'commission' => $request['commission'],
              'excess_premium' => $request['excess_premium'],
              'type' => $request['type']
            ]);

        Session::flash('success', 'Successfully updated Policy');
        Redirect::to('products.php');
    }

    public static function deletePolicy($id) {
        $find = self::find($id);
        $find->delete();

        Session::flash('success', 'Successfully deleted product');
        Redirect::to('products.php');
    }
}
