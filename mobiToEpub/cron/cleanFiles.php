<?php

$path = '../files';
$now = time(); // returns the current time in the number of seconds since the Unix Epoch
$delete_time = $now - (3600*1); // Clean if the file was last changed more or equal to one hour ago
//echo 'now: '.$now.'<br>';
//echo 'delete_time: '.$delete_time.'<br>';

$files = glob($path.'/*.{mobi,epub}', GLOB_BRACE);
foreach($files as $file)
{
  //echo $file.' - '.date("U",filectime($file)).'<br>';
  if(date("U",filectime($file)) <= $delete_time) // Clean if the file was last changed more or equal to one hour ago
  {
      //echo $file.' REMOVE <br>';
      unlink($file);
  }
}

?>
