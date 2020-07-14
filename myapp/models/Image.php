<?php namespace App;

use Illuminate\Database\Eloquent\Model as Eloquent;
use App\Session;
use App\Redirect;
class Image extends Eloquent
{
    protected $fillable = ['user_id', 'path'];
    
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
                    Image::create([
                        'user_id' => $post['user_id'],
                        'path' => $uploadDirectory.basename($fileName)
                    ]);
                    // echo "The file " . basename($fileName) . " has been uploaded";
                    Session:: flash('error', 'Email does not exists!');
                    Redirect::to('profile.php?id='.$post['user_id'].'&tab=profile');
                } else {
                    echo "An error occurred. Please contact the administrator.";
                }
            } else {
                foreach ($errors as $error) {
                echo $error . "These are the errors" . "\n";
                }
            }

            }

        } catch (RuntimeException $e) {
            echo $e->getMessage();
        }
    }
}
