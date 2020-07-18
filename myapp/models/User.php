<?php namespace App;

use Illuminate\Database\Eloquent\Model as Eloquent;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Session;
use App\Redirect;
use App\UserProfile;
use App\Hash;
use Swift_SmtpTransport;
use Swift_Mailer;
use Swift_Message;
use App\UserPolicy;
use App\PolicyBenefit;
use App\Production;
use App\Beneficiaries;
use App\Unit;
use App\AuditTrail;
use Carbon\Carbon;
use Dompdf\Dompdf;
use Illuminate\Database\Eloquent\Builder;

class User extends Eloquent
{
	use SoftDeletes;
	protected $fillable = array(
		'email',
		'password',
		'email_verified',
		'reset_password',
		'token',
		'token_expiry',
		'role_id'
	);
	protected $hidden = ['password'];

    public static function login($email = null, $password = null)
	{
        $user = self::where('email',$email)->first();
		if($user)
		{
			if(Hash::verifyPassword($password, $user->password))
			{
				if (!Session::exists('user_id'))
					Session::put('user_id', $user->id);
				if ($user->role_id == 1)
				{
					Redirect::to('index.php');
				}
				else
				{
					Redirect::to('index.php');
                }
			}
			else
			{
				Session::flash('error', 'Your username and password did not match');
				Redirect::to('login.php');
			}
		}
		else
		{
			Session::flash('error', 'You\'re trying to login a non-existent account.');
			Redirect::to('login.php');
		}
	}

	public static function logout() {
		Session::delete('user_id');
		Redirect::to('login.php');
	}

	public static function add($request) {
		$random_password = bin2hex(random_bytes(5)); 
		$temporary_password = Hash::encrypt('admin');
		$exists = User::where('email', '=', $request['email'])->first();
		if ($exists) {
			if ($request['addtounit']) {
				$user = User::find($exists->id);
				$user->role_id = 2;
				$user->save();
				
				$profile = UserProfile::where('user_id', $user->id)->first();
				$profile->advisor_id = $request['advisor'];
				$profile->advisor_code = $request['advisor_code'];
				$profile->unit_id = $request['unit'];
				$profile->status_id = isset($request['status']) ? $request['status'] : null;
				$profile->save();

				AuditTrail::add('Added new user to unit');

				Session::flash('success', 'Succesfully added new user!');
				Redirect::to('unit.php?unit_id='.$request['unit']);
			} else {
				Session::flash('error', 'Email already exists!');
			}
		} else {
			$user = self::create([
				'email' => $request['email'],
				'password' => $temporary_password,
				'role_id' => $request['role']
			]);
			
			UserProfile::create([
				'user_id' => $user->id,
				'firstname' => $request['firstname'],
				'middlename' => $request['middlename'],
				'lastname' => $request['lastname'],
				'advisor_id' => isset($request['advisor']) && $request['advisor'] ? $request['advisor'] : null,
				'advisor_code' => $request['advisor_code'],
				'unit_id' => isset($request['unit']) && $request['unit'] ? $request['unit'] : null,
				'status_id' => isset($request['status']) ? $request['status'] : null,
				'dob' => isset($request['dob']) ? date('Y-m-d', strtotime($request['dob'])) : null,
				'gender' => isset($request['gender']) ? $request['gender'] : null,
				'coding_date' => date('Y-m-d', strtotime($request['coding_date'])),
				'created_by' => Session::get('user_id')
			]);
	
			$content = [
				'message' => 'THIS IS YOUR PASSWORD',
				'from' => [
					getenv('EMAIL') => getenv('EMAIL_NAME')
				],
				'to' => [$request['email']],
				'body' => 'Use this as your temporary password: '.$random_password
			];
	
			self::sendMail($content);
			AuditTrail::add('Added new user');
			Session::flash('success', 'Succesfully added new user!');
			if ($request['addtounit']) {
				Redirect::to('unit.php?unit_id='.$request['unit']);
			} elseif($user->role_id) {
				Redirect::to('users.php');
			}
		}
		Session::flash('success', 'Succesfully added new client!');
		Redirect::to('clients.php');
	}

	public static function addUnit($request) {
		$random_password = bin2hex(random_bytes(5)); 
		$temporary_password = Hash::encrypt('admin');

		// check if existing
		$email = trim($request['email']);
		$user = User::where('email', $email)->first();
		if ($user) {
			// $user->role_id = $request['role'];
			// $user->save();

			$unit = Unit::create([
				'name' => $request['unit'],
				'owner_id' => $user->id,
				'created_by' => Session::get('user_id')
			]);

			$user_profile = UserProfile::where('user_id', $user->id)->first();
			$user_profile->firstname = $request['firstname'];
			$user_profile->middlename = $request['middlename'];
			$user_profile->lastname = $request['lastname'];
			$user_profile->dob = date('Y-m-d', strtotime($request['dob']));
			$user_profile->unit_id = $unit->id;
			$user_profile->advisor_code = $request['advisor_code'];
			$user_profile->status_id = $request['status'];
			$user_profile->coding_date = date('Y-m-d', strtotime($request['coding_date']));
			$user_profile->created_by = Session::get('user_id');
			$user_profile->save();

			AuditTrail::add('Added existing user as unit manager');
		} else {
			$user = self::create([
				'email' => $request['email'],
				'password' => $temporary_password,
				'role_id' => $request['role']
			]);
			
			$unit = Unit::create([
				'name' => $request['unit'],
				'owner_id' => $user->id,
				'created_by' => Session::get('user_id')
			]);

			UserProfile::create([
				'user_id' => $user->id,
				'firstname' => $request['firstname'],
				'middlename' => $request['middlename'],
				'lastname' => $request['lastname'],
				'dob' => date('Y-m-d', strtotime($request['dob'])),
				'unit_id' => $unit->id,
				'advisor_code' => $request['advisor_code'],
				'status_id' => $request['status'],
				'coding_date' => date('Y-m-d', strtotime($request['coding_date'])),
				'created_by' => Session::get('user_id')
			]);

			AuditTrail::add('Added new user to unit');
		}

		
		$content = [
			'message' => 'You have been appointed as a Unit Manager',
			'from' => [
				getenv('EMAIL') => getenv('EMAIL_NAME')
			],
			'to' => [$request['email']],
			'body' => 'You have been appointed as a Unit Manager. To log in, use this as your temporary password: '.$random_password
		];

		self::sendMail($content);

		Session::flash('success', 'Succesfully added new user.');
		Redirect::to('units.php');
	}

	public static function isLogged($name = 'user_id') {
		if(!Session::exists($name)) {
			Redirect::to('login.php');
		}
	}

	public static function updateUser($request) {
		$profile = UserProfile::where('user_id', $request['user_id'])
		->update([
			'firstname' => $request['firstname'],
			'middlename' => $request['middlename'],
			'lastname' => $request['lastname'],
			'dob' => date('Y-m-d', strtotime($request['clientDob'])),
			'advisor_id' => $request['advisor'],
			'unit_id' => $request['unit'] ?: null,
			'status_id' => $request['status'] ?: null,
			// 'gender' => $request['gender'] ?: null,
			'client_number' => $request['client_number'],
		]);

		if (isset($request['role'])) {
			$find = User::find($request['user_id']);
			$find->role_id = $request['role'];
			$find->save();
		}

		$user = User::find($request['user_id'])
		->update([
			'email' =>  $request['email']
		]);
		$tab = 'profile';

		AuditTrail::add('Updated user info');
		
		Session::flash('success', 'Succesfully updated user.');
		Redirect::to('profile.php?id='.$request['user_id'].'&tab='.$tab);
	}

	private static function sendMail($content) {
		try {
			// Create the Transport
			$transport = (new Swift_SmtpTransport('smtp.gmail.com', 587, 'ssl'))
			->setUsername(getenv('EMAIL'))
			->setPassword(getenv('PASSWORD'))
			;

		// Create a message
		$message = (new Swift_Message($content['message']))
			->setFrom($content['from'])
			->setTo($content['to'])
			->setBody($content['body'])
			;

		// Create the Mailer using your created Transport
		$mailer = new Swift_Mailer($transport);

		// Send the message
		$result = $mailer->send($message);
		} catch (\Throwable $th) {
			//throw $th;
		}
		
	}

	public static function requestPasswordRequest($request) {
		$user = User::where('email', $request['email'])->first();
		if ($user) {
			$token = md5(uniqid(rand(), true));
			$today = time();
			$token_expiry = date('Y-m-d H:i:s', strtotime('+1 day', $today));

			$user = User::find($user->id)
			->update([
				'token' =>  $token,
				'token_expiry' => $token_expiry
			]);
			
			$content = [
				'message' => 'Password Reset Request',
				'from' => [
					getenv('EMAIL') => getenv('EMAIL_NAME')
				],
				'to' => [$request['email']],
				'body' =>
					'To change your password, click the following link.'.


					'https://slthesis.herokuapp.com/myapp/reset-password.php?token='.$token.'
					
					This link will expire in 24 hours, so be sure to use it right away.'
			];
	
			self::sendMail($content);
			Redirect::to('reset-link.php');
		} else {
			Session::flash('error', 'Email does not exists!');
			Redirect::to('login.php');
		}
	}

	public static function resetPassword($request) {
		$password = Hash::encrypt($request['password']);
		$user = User::find($request['user_id'])
		->update([
			'password' => $password,
			'reset_password' => 1
		]);
		AuditTrail::add('Successfully reset password');
		
		Redirect::to('reset-password-successful.php');
	}

	public static function addClient($request) {
		$time = strtotime($request['issue_date']);
		$premium_due_date = date("Y-m-d", strtotime("+1 month", $time));
		$benefits = $request['benefits'];

		$user = self::create([
			'email' => $request['email']
		]);

		UserProfile::create([
			'user_id' => $user->id,
			'firstname' => $request['firstname'],
			'middlename' => $request['middlename'],
			'lastname' => $request['lastname'],
			'advisor_id' => Session::get('user_id'),
			'dob' => isset($request['dob']) ? date('Y-m-d', strtotime($request['dob'])) : null,
			'gender' => $request['gender'],
			'created_by' => Session::get('user_id')
		]);

		
		if (!empty($request['fullname'][0]) &&
			!empty($request['beneficiary_relationship'][0]) &&
			!empty($request['beneficiaries_dob'][0]) &&
			!empty($request['designation'][0])
		) {
			foreach ($request['fullname'] as $key => $value) {
				Beneficiaries::create([
					'user_id' => $user->id,
					'name' => $value,
					'relationship' => $request['beneficiary_relationship'][$key],
					'birthdate' => $request['beneficiaries_dob'][$key],
					'designation' => $request['designation'][$key]
				]);
			}
		}

		$time = strtotime($request['issue_date']);
		$premium_due_date = self::getDue($time, $request['mode_of_payment']);

		UserPolicy::create([
			'user_id' => $user->id,
			'policy_id' => $request['product'],
			'face_amount' => $request['face_amount'],
			'benefits' => $request['benefits'],
			// 'advisor_id' => Session::get('user_id'),
			'annual_premium_amount' => $request['annual_premium'],
			'policy_number' => $request['policy_number'],
			'mode_of_payment' => $request['mode_of_payment'],
			'issue_date' => date('Y-m-d', strtotime($request['issue_date'])),
			'premium_due_date' => $premium_due_date
		]);
		
		foreach ($benefits as $value) {
			PolicyBenefit::create([
				'policy_id' => $request['product'],
				'benefits_id' => $value,
				'user_id' => $user->id
			]);
		}
		
		AuditTrail::add('Added a new client');

		Session::flash('success', 'Successfully added client');
		Redirect::to('clients.php');
	}

	/**
	 * var $date is date issued
	 * var $mop is Mode of Payment
	 */
	public static function getDue($date, $mop) {
			switch ($value->policy->mode_of_payment) {
				case 'Annual':
					$premium_due_date = date("Y-m-d", strtotime("+365 day", $date));
					break;
				case 'Semi-Annual':
					$premium_due_date = date("Y-m-d", strtotime("+6 month", $date));
					break;
				case 'Quarterly':
					$premium_due_date = date("Y-m-d", strtotime("+3 month", $date));
					break;
				default:
					$premium_due_date = date("Y-m-d", strtotime("+1 month", $date));
					break;
			}
		return $clients;
	}

	public static function getDueDates() {
		$clients = User::whereNull('role_id')
			->with('profile', 'profile.userPolicy', 'profile.latestPayment', 'userPolicy' ,'userPolicy.policy')
			->whereHas('profile', function (Builder $query) {
				$query->where('advisor_id', Session::get('user_id'));
			})
			->get();
		foreach ($clients as $key => $value) {
			$latest_payment = isset($value->profile->latestPayment->created_at) ? $value->profile->latestPayment->created_at : $value->profile->userPolicy->issue_date;
			$latest_payment = new Carbon($latest_payment);
			switch ($value->profile->userPolicy->mode_of_payment) {
				case 'Annual':
					$premium_due_date = $latest_payment->addMonths(12);
					break;
				case 'Semi-Annual':
					$premium_due_date = $latest_payment->addMonths(6);
					break;
				case 'Quarterly':
					$premium_due_date = $latest_payment->addMonths(3);
					break;
				default:
					$premium_due_date = $latest_payment->addMonths(1);
					break;
			}
			$clients[$key]->premium_due_date = $premium_due_date->toDateString();
		}

		return $clients;
	}

	public static function deleteClient($request) {
		$client = User::find($request);
		$client->delete();

		$profile = UserProfile::where('user_id', $request)->first();
		$profile->delete();

		if ($client->role_id) {
			AuditTrail::add('delete a user');
			$message = 'Successfully deleted user';
		} else {
			AuditTrail::add('delete a client');
			$message = 'Successfully deleted client';
		}

		Session::flash('success', $message);
		if ($client->role_id) {
			Redirect::to('users.php');
		} else {
			Redirect::to('clients.php');
		}
	}

	public static function deleteUnit($post) {
		$sales_manager = User::where('role_id', 4)->with('profile')
			->whereHas('profile', function (Builder $query) {
				$query->whereNotNull('unit_id');
			})->first();

		$unit_members = UserProfile::where('unit_id', $post['delete_unit'])->get();

		if ($unit_members) {
			foreach($unit_members as $key => $value) {
				$member = UserProfile::where('user_id', $value->user_id)->first();
				$member->unit_id = isset($sales_manager->profile->unit_id) ? $sales_manager->profile->unit_id : null;
				$member->save();
			}
		}

		$unit = Unit::find($post['delete_unit']);
		$unit->delete();

		AuditTrail::add('Deleted a unit');

		Session::flash('success', 'Successfully deleted unit');
		Redirect::to('units.php');
	}

	public static function seeUsers($role, $action = null)
	{
		$users = [];
		$user = User::with('profile')->find(Session::get('user_id'));
		if ($action) {
			if ($role === 3) {
				$users = User::with('profile', 'role', 'profile.advisor', 'profile.unit', 'profile.status')
					->whereNotNull('role_id')
					->whereHas('profile', function (Builder $query) use ($user) {
						$query->where('unit_id', $user->profile->unit_id);
					})
					->where('role_id', '<>', 1)
					->get();
			} else if ($role === 1 || $role === 4) {
				$users = User::with('profile', 'role', 'profile.advisor', 'profile.unit', 'profile.status')
					->whereNotNull('role_id')
					->where('role_id', '<>', 1)
					->get();
			}
		} else {
			if ($role === 3) {
				$users = User::with('profile', 'role', 'profile.advisor', 'profile.unit', 'profile.status')
					->whereNotNull('role_id')
					->whereHas('profile', function (Builder $query) use ($user) {
						$query->where('unit_id', $user->profile->unit_id);
					})
					->get();
			} else if ($role === 1 || $role === 4) {
				$users = User::with('profile', 'role', 'profile.advisor', 'profile.unit', 'profile.status')
					->whereNotNull('role_id')
					->get();
			}
		}

		return $users;
	}


	public static function chartData()
	{
		$count = [1,2,3,4,5,6,7,8,9,10,11,12];
		$data = [];
		$date = new Carbon();
		$year = $date->startOfYear();
		foreach ($count as $key => $value) {
			$currentMonth = $year->format('m');
			$month_data = User::whereRaw('MONTH(created_at) = ?',[$currentMonth])
				->whereHas('profile', function($q) {
					$q->where('advisor_id', Session::get('user_id'));
				})
				->whereNull('role_id')
				->get();
			$data['year'][] = $month_data->count();
			$year->addMonth(1);
		}

		$last_year = $date->subYear(2);
		foreach ($count as $key => $value) {
			$start_date = $last_year;
			$end_date = $last_year->endOfMonth();
			$month_data = User::whereBetween('created_at',[$start_date, $end_date])
			->whereHas('profile', function($q) {
				$q->where('advisor_id', Session::get('user_id'));
			})
			->whereNull('role_id')
			->get();
			$data['last_year'][] = $month_data->count();
			$last_year->addMonth(1);
		}

		return $data;
	}

	public static function exportClients() {
        $clients = User::with('profile', 'role', 'profile.advisor', 'profile.unit', 'profile.status', 'profile.latestPayment')
			->whereHas('profile', function($q) {
				$q->where('advisor_id', Session::get('user_id'));
			})->whereNull('role_id')->get();

		AuditTrail::add('Exported client list');

        //create new dompdf object
        $html = ' <!doctype html>
        <html>
            <head>
                <meta charset="utf-8">
                <title>Clients Report</title>
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
						<th>Name</th>
						<th>Email</th>
						<th>Date of Birth</th>
						<th>Advisor</th>
						<th>Gender</th>
						<th>Latest Payment</th>
                    </tr>';
                    foreach($clients as $key => $value) {
						$firstname = isset($value->profile->firstname) ? $value->profile->firstname : '';
						$lastname = isset($value->profile->lastname) ? $value->profile->lastname : '';
						$email = isset($value->email) ? $value->email : '';
						$dob = isset($value->profile->dob) ? date('Y-m-d', strtotime($value->profile->dob)) : '';
						$advisor_firstname = isset($value->profile->advisor->firstname) ? $value->profile->advisor->firstname : '';
						$advisor_lastname = isset($value->profile->advisor->lastname) ? $value->profile->advisor->lastname : '';
						$gender = isset($value->profile->gender) ? $value->profile->gender : '';
						$payment_date = 'N/A';
						if (isset($value->profile->lastPayment->payment_date)) {
							$payment_date = date('Y-m-d', strtotime($value->profile->lastPayment->payment_date));
						}
                        $html .= '<tr>
							<td>'.$firstname.' '.$lastname.'</td>
							<td>'.$email.'</td>
							<td>'.$dob.'</td>
							<td>'.$advisor_firstname.' '.$advisor_lastname.'</td>
							<td>'.$gender.'</td>
							<td>'.$payment_date.'</td>
                        </tr>';
                    }
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
        $dompdf->stream('clients-report');
	}
	
	public static function exportUsers() {
		$user = User::find(Session::get('user_id'));
		$users = self::seeUsers($user->role_id, 'export');
		AuditTrail::add('Exported user list');
        //create new dompdf object
        $html = ' <!doctype html>
        <html>
            <head>
                <meta charset="utf-8">
                <title>Users Report</title>
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
			<h1>Users List</h1>
			<body>
                <table>
                    <tr>
						<th>Name</th>
						<th>Email</th>
						<th>Manager</th>
						<th>Unit Name</th>
						<th>Status</th>
						<th>Role</th>
                    </tr>';
                    foreach($users as $key => $value) {
						$user_firstname = '';
						$user_lastname = '';
						$email = '';
						$advisor_firstname = '';
						$advisor_lastname = '';
						$unit_name = '';
						$status = '';
						$role = '';
						if (isset($value->profile->firstname)) {
							$user_firstname = $value->profile->firstname;
						}
						if (isset($value->profile->lastname)) {
							$user_lastname = $value->profile->lastname;
						}
						if (isset($value->email)) {
							$email = $value->email;
						}
						if (isset($value->profile->advisor->firstname)) {
							$advisor_firstname = $value->profile->advisor->firstname;
						}
						if (isset($value->profile->advisor->lastname)) {
							$advisor_lastname = $value->profile->advisor->lastname;
						}
						if (isset($value->profile->unit->name)) {
							$unit_name = $value->profile->unit->name;
						}
						if (isset($value->profile->status->name)) {
							$status = $value->profile->status->name;
						}
						if (isset($value->role->name)) {
							$role = $value->role->name;
						}
                        $html .= '<tr>
							<td>'.$user_firstname.' '.$user_lastname.'</td>
							<td>'.$email.'</td>
							<td>'.$advisor_firstname.' '.$advisor_lastname.'</td>
							<td>'.$unit_name.'</td>
							<td>'.$status.'</td>
							<td>'.$role.'</td>
                        </tr>';
                    }
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
        $dompdf->stream('users-report');
	}
	
	public static function exportDueDates() {
		$clients = self::getDueDates();
		AuditTrail::add('Exported due dates list');
        //create new dompdf object
        $html = ' <!doctype html>
        <html>
            <head>
                <meta charset="utf-8">
                <title>Due Dates Report</title>
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
			<h1>Due Date List</h1>
			<body>
                <table>
                    <tr>
						<th>Name</th>
						<th>Plan Name</th>
						<th>Last Payment</th>
						<th>Next Due Date</th>
						<th>Payment Method</th>
                    </tr>';
                    foreach($clients as $key => $value) {
						$firstname = '';
						$lastname = '';
						$policy = '';
						$last_payment = '';
						$due_date = '';
						$mode_of_payment = '';
						if (isset($value->profile->firstname)) {
							$firstname = $value->profile->firstname;
						}
						if (isset($value->profile->lastname)) {
							$lastname = $value->profile->lastname;
						}
						if (isset($value->userPolicy->policy->name)) {
							$policy = $value->userPolicy->policy->name;
						}
						if (isset($value->profile->latestPayment->created_at)) {
							$last_payment = date('Y-m-d', strtotime($value->profile->latestPayment->created_at));
						}
						if (isset($value->premium_due_date)) {
							$due_date = $value->premium_due_date;
						}
						if (isset($value->userPolicy->mode_of_payment)) {
							$mode_of_payment = $value->userPolicy->mode_of_payment;
						}
                        $html .= '<tr>
							<td>'.$firstname.' '.$lastname.'</td>
							<td>'.$policy.'</td>
							<td>'.$last_payment.'</td>
							<td>'.$due_date.'</td>
							<td>'.$mode_of_payment.'</td>
                        </tr>';
                    }
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
        $dompdf->stream('due-dates-report');
    }

	public function profile()
    {
        return $this->hasOne('App\UserProfile');
	}
	
	public function role()
    {
        return $this->hasOne('App\Role', 'id', 'role_id');
	}
	
	public function userPolicy()
	{
		return $this->hasOne('App\UserPolicy');
	}
	
	public function image()
	{
		return $this->hasOne('App\Image');
	}
}
