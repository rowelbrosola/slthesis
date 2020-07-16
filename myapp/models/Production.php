<?php namespace App;

use Illuminate\Database\Eloquent\Model as Eloquent;
use App\Session;
use App\Redirect;
use App\UserProfile;
use Carbon\Carbon;
use App\Unit;
use App\Production;
class Production extends Eloquent
{
    protected $table = 'production';

    protected $fillable = ['user_id', 'amount', 'advisor_user_id', 'advisor_unit_id', 'created_by'];

    public static function add($request) {
        $user = UserProfile::where('user_id', $request['user_id'])->first();
        self::create([
            'user_id' => $request['user_id'],
            'amount' => $request['amount'],
            'start' => $request['start'],
            'end' => $request['end'],
            'unit_id' => $user->unit_id,
            'created_by' => Session::get('user_id')
        ]);

        Session::flash('success', 'You have successfully added production');
        Redirect::to('sales-production.php?id='.$request['user_id']);
    }

    public static function currentProduction() {
        $end_date = Carbon::now()->startOfMonth()->addMonth(3);
        $end_date->endOfMonth();

        $start_date = Carbon::now()->startOfMonth(); 
        $start_date->startOfMonth();

        $total_production = Production::select('amount','created_at')
            ->whereBetween('created_at',[$start_date, $end_date])
            ->sum('amount');

        return $total_production;
    }
    
    public static function eachUnitProduction() {
        $units = Unit::all();

        $end_date = Carbon::now()->startOfMonth()->addMonth(3);
        $end_date->endOfMonth();

        $start_date = Carbon::now()->startOfMonth(); 
        $start_date->startOfMonth();
        $total_production = [];
        if ($units) {
            foreach ($units as $key => $value) {
                $total_production[$value->name] = Production::select('amount','created_at')
                    ->whereBetween('created_at',[$start_date, $end_date])
                    ->where('advisor_unit_id', $value->id)
                    ->sum('amount');
            }
        }
        return $total_production;

    }

    // public static function loveMonth() {
    //     $now = Carbon::now();
    //     $year = $now->year;
    //     $month = $now->month;

    //     print_r($year);exit;
    // }
}