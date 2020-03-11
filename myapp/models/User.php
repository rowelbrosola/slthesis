<?php namespace App;

use Illuminate\Database\Eloquent\Model as Eloquent;
use App\Session;
use App\Redirect;
use App\UserProfile;
use App\Hash;
use Swift_SmtpTransport;
use Swift_Mailer;
use Swift_Message;

class User extends Eloquent
{
	protected $fillable = array('email', 'password', 'role_id');

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
		$temporary_password = Hash::encrypt($random_password);
		$exists = User::where('email', '=', $request['email'])->first();
		if ($exists) {
			Session::flash('error', 'Email already exists!');
		} else {
			$user = self::create([
				'email' => $request['email'],
				'password' => $temporary_password,
				'role_id' => $request['role']
			]);
			
			UserProfile::create([
				'user_id' => $user->id,
				'firstname' => $request['firstname'],
				'lastname' => $request['lastname'],
				'advisor_id' => $request['advisor'],
				'advisor_code' => $request['advisor_code'],
				'unit_id' => $request['unit'],
				'status_id' => $request['status'],
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
	
			Session::flash('success', 'Succesfully added new user.');
			if ($request['addtounit']) {
				Redirect::to('unit.php?unit_id='.$request['unit']);
			}
		}
		Redirect::to('clients.php');
	}

	public static function addUnit($request) {
		$random_password = bin2hex(random_bytes(5)); 
		$temporary_password = Hash::encrypt($random_password);

		$user = self::create([
			'email' => $request['email'],
			'password' => $temporary_password,
			'role_id' => $request['role']
		]);

		$unit = Unit::create([
			'name' => $request['unit'],
			'created_by' => Session::get('user_id')
		]);
		
		UserProfile::create([
			'user_id' => $user->id,
			'firstname' => $request['firstname'],
			'lastname' => $request['lastname'],
			'unit_id' => $unit->id,
			'advisor_code' => $request['advisor_code'],
			'status_id' => $request['status'],
			'coding_date' => date('Y-m-d', strtotime($request['coding_date'])),
			'created_by' => Session::get('user_id')
		]);

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
			'lastname' => $request['lastname'],
			'dob' => date('Y-m-d', strtotime($request['clientDob'])),
			'advisor_id' => $request['advisor'],
			'status_id' => $request['status'],
			'client_number' => $request['client_number'] ? $request['client_number'] : null,
			'coding_date' => date('Y-m-d', strtotime($request['coding_date'])),
		]);

		$user = User::find($request['user_id'])
		->update([
			'email' =>  $request['email']
		]);
		$tab = 'profile';
		
		Session::flash('success', 'Succesfully updated user.');
		Redirect::to('profile.php?id='.$request['user_id'].'&tab='.$tab);
	}

	private function sendMail($content) {
		// Create the Transport
		$transport = (new Swift_SmtpTransport('smtp.gmail.com', 465, 'ssl'))
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
	}

	public function profile()
    {
        return $this->hasOne('App\UserProfile');
	}
	
	public function role()
    {
        return $this->hasOne('App\Role', 'id', 'role_id');
	}
}
