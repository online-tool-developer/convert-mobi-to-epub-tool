<?php
header('Content-Type: application/json; charset=utf-8');

$allowedKeys = array("YOUR_KEY");

// Validate the key

if(!isset($_POST['key']))
{
  echo json_encode(array(
    'error' => true,
    'msg' => 'The key is missing.',
  ));
	exit();
}

$key = $_POST['key'];

if(!in_array($key, $allowedKeys))
{
  echo json_encode(array(
    'error' => true,
    'msg' => 'The key is invalid.',
  ));
	exit();
}

// Validate the file

if(!isset($_FILES['file']))
{
  echo json_encode(array(
    'error' => true,
    'msg' => 'The book file is missing.',
  ));
	exit();
}

$book = $_FILES['file'];

if($book['error'] > 0)
{
  echo json_encode(array(
    'error' => true,
    'msg' => 'Book file upload failed.',
  ));
	exit();
}

$allowedExts = array("mobi");
$temp = explode(".", $book["name"]);
$ext = end($temp);
if(!in_array($ext, $allowedExts))
{
  echo json_encode(array(
    'error' => true,
    'msg' => 'The file extension is not mobi.',
  ));
	exit();
}

if($book['type'] !== "application/x-mobipocket-ebook"
&& $book['type'] !== "application/octet-stream") // Fix for browser
{
  echo json_encode(array(
    'error' => true,
    'msg' => 'The file is not of type mobi.',
  ));
	exit();
}

// Move the book to the files folder

$file_name = md5($book['tmp_name']).'-'.time();
move_uploaded_file($book['tmp_name'], 'files/'.$file_name.'.mobi');

// Convert the file using "ebook-convert" command from Calibre

exec('ebook-convert "files/'.$file_name.'.mobi" "files/'.$file_name.'.epub" --no-default-epub-cover'); // this will wait for command to finish 

// But an additional timeout is given to detect that the file exists
$round=0;
$file_exists = file_exists('files/'.$file_name.'.epub');
while(!$file_exists)
{
  $file_exists = file_exists('files/'.$file_name.'.epub');
  sleep(1);
  if($round++ > 5)
  {
    echo json_encode(array(
      'error' => true,
      'msg' => 'There was an error converting the book.',
    ));
    exit();
  }
}

$url = 'https://YOUR_URL';

//error_log("[".date("Y-m-d H:i:s")."] $key - $file_name \n", 3, dirname(__FILE__)."/mobiToEpubLog.log"); // For log

echo json_encode(array(
  'error' => false,
  'msg' => 'The conversion was successful, you can download the book.',
  'url' => $url.'/mobiToEpub/files/'.$file_name.'.epub',
));

?>
