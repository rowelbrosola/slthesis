<?php namespace App;

use Illuminate\Database\Eloquent\Model as Eloquent;
use App\Session;
use App\Redirect;
use App\UserProfile;
use Carbon\Carbon;
use App\Unit;
use App\Production;
use App\User;
use Dompdf\Dompdf;
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

    public static function currentProduction($id = null) {
        $end_date = Carbon::now()->startOfMonth()->addMonth(3);
        $end_date->endOfMonth();

        $start_date = Carbon::now()->startOfMonth(); 
        $start_date->startOfMonth();

        if ($id) {
            $total_production = Production::select('amount','created_at')
                ->whereBetween('created_at',[$start_date, $end_date])
                ->where('advisor_user_id', $id)
                ->sum('amount');
        } else {
            $total_production = Production::select('amount','created_at')
                ->whereBetween('created_at',[$start_date, $end_date])
                ->sum('amount');
        }

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

    public static function unitTotalCampaign($id) {

        $total_campaign = Production::select('amount','created_at')
            ->whereBetween('created_at',[$start_date, $end_date])
            ->where('advisor_unit_id', $value->id)
            ->sum('amount');
    }

    public static function currentCampaign($id = null) {
        $now = Carbon::now();
        $love_month = ['01', '02', '03'];
        $summer_campaign = ['04', '05', '06'];
        $august_champions = ['07', '08', '09'];
        $presidents_month = ['10', '11', '12'];
        $current_month = $now->format('m');
        if (array_search($current_month, $love_month) !== FALSE) {
            $start = Carbon::parse('first day of January');
            $end = Carbon::parse('last day of March');
        } else if (array_search($current_month, $summer_campaign) !== FALSE) {
            $start = Carbon::parse('first day of April');
            $end = Carbon::parse('last day of June');
        } else if (array_search($current_month, $august_champions) !== FALSE) {
            $start = Carbon::parse('first day of July');
            $end = Carbon::parse('last day of September');
        } else if (array_search($current_month, $presidents_month) !== FALSE) {
            $start = Carbon::parse('first day of October');
            $end = Carbon::parse('last day of December');
        }

        if ($id) {
            $total_production = Production::select('amount','created_at')
                ->whereBetween('created_at',[$start, $end])
                ->where('advisor_unit_id', $id)
                ->sum('amount');
        } else {
            $total_production = Production::select('amount','created_at')
                ->whereBetween('created_at',[$start, $end])
                ->sum('amount');
        }

        return $total_production;
        
    }

    public static function currentYTD($id = null) {
        $date = Carbon::now();
        $startOfYear = $date->copy()->startOfYear();
        $endOfYear   = $date->copy()->endOfYear();

        if ($id) {
            $total_production = Production::select('amount','created_at')
                ->whereBetween('created_at',[$startOfYear, $endOfYear])
                ->where('advisor_unit_id', $id)
                ->sum('amount');
        } else {
            $total_production = Production::select('amount','created_at')
                ->whereBetween('created_at',[$startOfYear, $endOfYear])
                ->sum('amount');
        }

        return $total_production;
    }

    public static function exportUnits() {
        $units = Unit::with('creator', 'owner', 'members', 'production')->get();
        $production = Production::eachUnitProduction();
        
        $total_sum = 0;
        $total = 0;
        $total_campaign = 0;
        $total_members = 0;
        foreach($units as $key => $value) {
            $campaign = isset($production[$value->name]) ? $production[$value->name] : 0;
            $members_count = isset($value->members) ? $value->members->count() : 0;
            foreach($value->production as $k => $v)
            {
                $total_sum += $v->amount;
            }
            $total += $total_sum;
            $total_campaign += $campaign;
            $total_members += $members_count;
        }


        //create new dompdf object
        $html = ' <!doctype html>
        <html>
            <head>
                <meta charset="utf-8">
                <title>Units Report</title>
            </head>
            <style>
                table {
                    font-family: arial, sans-serif;
                    border-collapse: collapse;
                    width: 100%;
                }
                td, th {
                    border: 1px solid #dddddd;
                    text-align: left;
                    padding: 8px;
                }
                tr:nth-child(even) {
                    background-color: #dddddd;
                }
            </style>
            <img src="img/sunlife-logo.png" />
            <p style="position:absolute; top:0;right:0;">Date: '.date('Y-m-d', time()).'</p>
            <body>
                <table>
                    <tr>
                        <th>Unit Name</th>
                        <th>Advisor Code</th>
                        <th>Unit Manager</th>
                        <th>Man Power</th>
                        <th>YTD Production</th>
                        <th>Campaign</th>
                    </tr>';
                    foreach($units as $key => $value) {
                        $sum = 0;
                        $name = isset($value->name) ? $value->name : '';
                        $owner_firstname = isset($value->owner->firstname) ? $value->owner->firstname : '';
                        $owner_lastname = isset($value->owner->lastname) ? $value->owner->lastname : '';
                        $members_count = isset($value->members) ? $value->members->count() : 0;
                        $campaign = isset($production[$value->name]) ? $production[$value->name] : 'N/A';
                        foreach($value->production as $k => $v)
                        {
                            $sum += $v->amount;
                        }
                        $html .= '<tr>
                            <td>'.$name.'</td>
                            <td>'.$value->owner->advisor_code.'</td>
                            <td>'.$owner_firstname.' '.$owner_lastname.'</td>
                            <td>'.$members_count.'</td>
                            <td>'.$sum.'</td>
                            <td>'.$campaign.'</td>
                        </tr>';
                    }
                    $html .= '<tr>
                                <td></td>
                                <td></td>
                                <td style="font-weight:700">Total</td>
                                <td>'.$total_members.'</td>
                                <td>'.$total.'</td>
                                <td>'.$total_campaign.'</td>
                            </tr>';
        $html .= '</table>
            </body>
        </html> ' ;

        // instantiate and use the dompdf class
        $dompdf = new Dompdf();
        $dompdf->loadHtml($html);

        // (Optional) Setup the paper size and orientation
        $dompdf->setPaper('A4', 'landscape');

        // Render the HTML as PDF
        $dompdf->render();

        // Output the generated PDF to Browser
        $dompdf->stream('units-report');
    }

    public static function exportUnit() {
        $unit_members = UserProfile::where('unit_id', $_GET['unit_id'])
            ->with('status', 'unit', 'advisor', 'production')
            ->get();

        $unit_manager = User::with('profile')->find(Session::get('owner_id'));

        $unit = Unit::find($unit_manager->profile->unit_id);

        $ytd_production = 0;
        $campaign_prod = 0;
        foreach($unit_members as $key => $value) {
            foreach($value->production as $k => $v)
            {
                $ytd_production+= $v->amount;
            }
            $campaign_prod += $current_production = self::currentProduction($value->user_id);
        }
        
        //create new dompdf object
        $html = ' <!doctype html>
        <html>
            <head>
                <meta charset="utf-8">
                <title>'.$unit->name.' Report</title>
            </head>
            <style>
                table {
                    font-family: arial, sans-serif;
                    border-collapse: collapse;
                    width: 100%;
                }
                td, th {
                    border: 1px solid #dddddd;
                    text-align: left;
                    padding: 8px;
                }
                tr:nth-child(even) {
                    background-color: #dddddd;
                }
            </style>
            <body>
                <img src="img/sunlife-logo.png" />
                <p style="position:absolute; top:0;right:0;">Date: '.date('Y-m-d', time()).'</p>
                <h1>'.$unit->name.'</h1>
                <table>
                    <tr>
                        <th>Name</th>
                        <th>Advisor Code</th>
                        <th>Status</th>
                        <th>Unit Manager</th>
                        <th>YTD Production</th>
                        <th>Campaign</th>
                    </tr>';
                    foreach($unit_members as $key => $value) {
                        $current_prod = 0;
                        $sum = 0;
                        $firstname = isset($value->firstname) ? $value->firstname : '';
                        $lastname = isset($value->lastname) ? $value->lastname : '';
                        $advisor_code = isset($value->advisor_code) ? $value->advisor_code : '';
                        $um_firstname = isset($unit_manager->profile->firstname) ? $unit_manager->profile->firstname : '';
                        $um_lastname = isset($unit_manager->profile->lastname) ? $unit_manager->profile->lastname : '';
                        foreach($value->production as $k => $v)
                        {
                            $sum+= $v->amount;
                        }
                        $current_prod += self::currentProduction($value->user_id);
                        $html .= '<tr>
                            <td>'.$firstname.' '.$lastname.'</td>
                            <td>'.$value->advisor_code.'</td>
                            <td>'.$value->status->name.'</td>
                            <td>'.$um_firstname.' '.$um_lastname.'</td>
                            <td>'.$sum.'</td>
                            <td>'.$current_prod.'</td>
                        </tr>';
                    }
                    $html .= '<tr>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td style="font-weight:700">Total</td>
                                <td>'.$ytd_production.'</td>
                                <td>'.$campaign_prod.'</td>
                            </tr>';
        $html .= '</table>
            </body>
        </html> ' ;

        // instantiate and use the dompdf class
        $dompdf = new Dompdf();
        $dompdf->loadHtml($html);

        // (Optional) Setup the paper size and orientation
        $dompdf->setPaper('A4', 'landscape');

        // Render the HTML as PDF
        $dompdf->render();
        $date = date('Y-m-d', time());
        // Output the generated PDF to Browser
        $dompdf->stream($unit->name.'-report-'.$date);
    }

    public static function userCampaign($id) {
        $now = Carbon::now();
        $love_month = ['01', '02', '03'];
        $summer_campaign = ['04', '05', '06'];
        $august_champions = ['07', '08', '09'];
        $presidents_month = ['10', '11', '12'];
        $current_month = $now->format('m');
        if (array_search($current_month, $love_month) !== FALSE) {
            $start = Carbon::parse('first day of January');
            $end = Carbon::parse('last day of March');
        } else if (array_search($current_month, $summer_campaign) !== FALSE) {
            $start = Carbon::parse('first day of April');
            $end = Carbon::parse('last day of June');
        } else if (array_search($current_month, $august_champions) !== FALSE) {
            $start = Carbon::parse('first day of July');
            $end = Carbon::parse('last day of September');
        } else if (array_search($current_month, $presidents_month) !== FALSE) {
            $start = Carbon::parse('first day of October');
            $end = Carbon::parse('last day of December');
        }

        $total_production = Production::select('amount','created_at')
            ->whereBetween('created_at',[$start, $end])
            ->where('advisor_user_id', $id)
            ->sum('amount');
        return $total_production;
    }

    public static function userYTD($id) {
        $date = Carbon::now();
        $startOfYear = $date->copy()->startOfYear();
        $endOfYear   = $date->copy()->endOfYear();

        $total_production = Production::select('amount','created_at')
            ->whereBetween('created_at',[$startOfYear, $endOfYear])
            ->where('advisor_user_id', $id)
            ->sum('amount');

        return $total_production;
        
    }
}