<?php

require_once 'vendor/autoload.php';

$uploadedImageName = "";

$target_dir = "";
$target_file = $target_dir . basename(time());
$uploadOk = 1;
// Check if image file is a actual image or fake image
if(isset($_FILES)) {
    $check = getimagesize($_FILES["fileToUpload"]["tmp_name"]);
	$imageFileType = explode("/", $check["mime"])[1];
    if($check !== false) {
        //echo "File is an image - " . $check["mime"] . ".";
        $uploadOk = 1;
    } else {
        //echo "File is not an image.";
        $uploadOk = 0;
    }
}
// Check if file already exists
if (file_exists($target_file)) {
    //echo "Sorry, file already exists.";
    $uploadOk = 0;
}
// Check file size
if ($_FILES["fileToUpload"]["size"] > 500000) {
    //echo "Sorry, your file is too large.";
    $uploadOk = 0;
}
// Allow certain file formats
if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
&& $imageFileType != "gif" ) {
    //echo "Sorry, only JPG, JPEG, PNG & GIF files are allowed.<br>";
    $uploadOk = 0;
}
// Check if $uploadOk is set to 0 by an error
if ($uploadOk == 0) {
    //echo "Sorry, your file was not uploaded.";
// if everything is ok, try to upload file
} else {
	$target_file .= ".".$imageFileType;
    if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file)) {
        //echo "The file ". basename( $_FILES["fileToUpload"]["name"]). " has been uploaded.";
		$uploadedImageName = $target_file;
    } else {
        //echo "Sorry, there was an error uploading your file.<br>";
    }
}

if($uploadedImageName != ""){
	\Cloudinary::config(array(
		"cloud_name" => "deylrqt2d",
		"api_key" => "186927498773735",
		"api_secret" => "PFb-4vRPEP6lbekOeFd7ZPIPyfE"
	));

	$userImage = $uploadedImageName;

	$imagePath = array("user_pfp" => getcwd(). DIRECTORY_SEPARATOR . $userImage);

	$default_upload_options = array("folder" => "user_pfps");
		
	$files = array();

	# This function, when called uploads all files into your Cloudinary storage and saves the
	# metadata to the $files array.

	function do_uploads() {
	  global $files, $imagePath, $default_upload_options;
	  
	  # public_id will be generated on Cloudinary's backend.
	  $files = \Cloudinary\Uploader::upload($imagePath["user_pfp"], array_merge($default_upload_options, array("width" => 200,"height" => 200,"crop" => "fit")));
	}

	do_uploads();

	echo $files['secure_url'];
	
	unlink($userImage); //deletes the uploaded image from the server, so is just on cloudinary
}else{
	echo false;
}
