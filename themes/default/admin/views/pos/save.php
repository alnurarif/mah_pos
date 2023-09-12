<?php

$dir = '../../../../../assets/customer_sign/'; //the folder to place your signature files in
//$file_name = 'signature_'.time().'.png'; // just the file name
$file_name = $_POST['signature_file_name']; //getting file name from pos add page
$file = $dir. $file_name; //the filename of your new signature

if (empty($_POST['signature'])) {
	//echo '<p>Error: No signature submitted!</p>';
} else {
	$data = trim(strip_tags($_POST['signature']));
	if (substr($data,0,15) != 'data:image/png;') { echo '<p>Error: Invalid signature file!</p>'; }
	else {
		$encoded_image = explode(',',$data)[1];
		$decoded_image = base64_decode($encoded_image);
		file_put_contents($file,$decoded_image) or die('<p>Error: Could not save file.</p>');
		echo $file_name;
	}
}

?>