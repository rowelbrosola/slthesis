<?php namespace App;

use Illuminate\Database\Eloquent\Model as Eloquent;

class UserProfile extends Eloquent
{
    protected $fillable = array('user_id', 'firstname', 'middlename', 'lastname', 'gender', 'advisor_id', 'advisor_code', 'unit_id', 'status_id', 'dob', 'coding_date', 'client_number', 'image_path');

    public function uploadImage($request) {
        header('Content-Type: text/plain; charset=utf-8');

        try {
        
            // Undefined | Multiple Files | $_FILES Corruption Attack
            // If this request falls under any of them, treat it invalid.
            if (
                !isset($_FILES['upfile']['error']) ||
                is_array($_FILES['upfile']['error'])
            ) {
                throw new RuntimeException('Invalid parameters.');
            }

            // Check $_FILES['upfile']['error'] value.
            switch ($_FILES['upfile']['error']) {
                case UPLOAD_ERR_OK:
                    break;
                case UPLOAD_ERR_NO_FILE:
                    throw new RuntimeException('No file sent.');
                case UPLOAD_ERR_INI_SIZE:
                case UPLOAD_ERR_FORM_SIZE:
                    throw new RuntimeException('Exceeded filesize limit.');
                default:
                    throw new RuntimeException('Unknown errors.');
            }

            // You should also check filesize here.
            if ($_FILES['upfile']['size'] > 1000000) {
                throw new RuntimeException('Exceeded filesize limit.');
            }

            // DO NOT TRUST $_FILES['upfile']['mime'] VALUE !!
            // Check MIME Type by yourself.
            $finfo = new finfo(FILEINFO_MIME_TYPE);
            if (false === $ext = array_search(
                $finfo->file($_FILES['upfile']['tmp_name']),
                array(
                    'jpg' => 'image/jpeg',
                    'png' => 'image/png',
                    'gif' => 'image/gif',
                ),
                true
            )) {
                throw new RuntimeException('Invalid file format.');
            }

            // You should name it uniquely.
            // DO NOT USE $_FILES['upfile']['name'] WITHOUT ANY VALIDATION !!
            // On this example, obtain safe unique name from its binary data.
            if (!move_uploaded_file(
                $_FILES['upfile']['tmp_name'],
                sprintf('./uploads/%s.%s',
                    sha1_file($_FILES['upfile']['tmp_name']),
                    $ext
                )
            )) {
                throw new RuntimeException('Failed to move uploaded file.');
            }

            echo 'File is uploaded successfully.';

        } catch (RuntimeException $e) {

            echo $e->getMessage();

        }
    }

    public static function upload($request, $post) {
        try {
            $currentDirectory = getcwd();
            $uploadDirectory = "/uploads/";

            $errors = []; // Store errors here

            $fileExtensionsAllowed = ['jpeg','jpg','png']; // These will be the only file extensions allowed 

            $fileName = $_FILES['fileToUpload']['name'];
            $fileSize = $_FILES['fileToUpload']['size'];
            $fileTmpName  = $_FILES['fileToUpload']['tmp_name'];
            $fileType = $_FILES['fileToUpload']['type'];
            $tmp = explode('.', $fileName);
            $fileExtension = strtolower(end($tmp));
            // $fileExtension = strtolower(end(explode('.',$fileName)));

            $uploadPath = $currentDirectory . $uploadDirectory . basename($fileName); 

            if (isset($_POST['uploadImage'])) {

                if (! in_array($fileExtension,$fileExtensionsAllowed)) {
                    $errors[] = "This file extension is not allowed. Please upload a JPEG or PNG file";
                }

                if ($fileSize > 4000000) {
                    $errors[] = "File exceeds maximum size (4MB)";
                }

                if (empty($errors)) {
                    $didUpload = move_uploaded_file($fileTmpName, $uploadPath);

                    if ($didUpload) {
                        $image = UserProfile::where('user_id',  $post['user_id'])->first();
                        $image->image_path = 'uploads/'.basename($fileName);
                        $image->save();
                        // echo "The file " . basename($fileName) . " has been uploaded";
                        Session:: flash('error', 'Successfully uploaded photo!');
                        Redirect::to('profile.php?id='.$post['user_id'].'&tab=profile');
                    } else {
                        echo "An error occurred. Please contact the administrator.";
                    }
                } else {
                    foreach ($errors as $error) {
                        echo $error . "These are the errors" . "\n";
                    }
                    Redirect::to('profile.php?id='.$post['user_id'].'&tab=profile');
                }
            }
        } catch (RuntimeException $e) {
            echo $e->getMessage();
        }
    }

    public static function totalManPower() {
        $units = UserProfile::with('unit')->get();
        return $units->count();
    }

    public function user()
    {
        return $this->belongsTo('App\User');
    }
    
    public function advisor()
    {
        return $this->belongsTo('App\UserProfile', 'advisor_id', 'user_id');
    }

    public function advisee()
    {
        return $this->hasMany('App\UserProfile', 'advisor_id', 'id');
    }

    public function unit()
	{
		return $this->hasOne('App\Unit', 'id', 'unit_id');
    }
    
    public function status()
    {
        return $this->hasOne('App\Status', 'id', 'status_id');
    }

    public function production() {
        return $this->hasMany('App\Production', 'advisor_user_id', 'user_id');
    }

    public function payment() {
        return $this->hasMany('App\Payment', 'user_id', 'user_id');
    }

    public function latestPayment() {
        return $this->hasOne('App\Payment', 'user_id', 'user_id')->latest();
    }

    public function userPolicy() {
        return $this->hasOne('App\UserPolicy', 'user_id', 'user_id')->latest();
    }
}
