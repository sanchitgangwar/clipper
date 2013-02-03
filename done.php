<html>
<head>
	<title>Done</title>

	<link rel="stylesheet" type="text/css" href="css/style_video.css">

</head>
	
<?php 
	$start_time = explode(',', $_POST['start-times']);
	$end_time = explode(',', $_POST['end-times']);


	$allowedExts = array("webm", "mp4", "ogg", "ogv");
	$extension = end(explode(".", $_FILES["file"]["name"]));
	$error = 0;
	if ($_FILES["file"]["size"] < 20000000 && in_array($extension, $allowedExts))
	  {
	  if ($_FILES["file"]["error"] > 0)
	    {
	    	$error = 1;
	    }
	  else
	    {
	    $ip_addr = str_replace('.', '', $_SERVER['REMOTE_ADDR']);
	    $filename = $ip_addr . $_FILES["file"]["name"];
	    $filename = str_replace(' ', '', $filename);

	    if (file_exists("upload/" . $filename))
	      {
	      }
	    else
	      {
	      move_uploaded_file($_FILES["file"]["tmp_name"],
	      "upload/" . $filename);
	      }
	    }
	  }
	else
	  {
	  	$error = 1;
	  }

	  function toFormat($time)
	  {
	  	$time = floor($time);
	  	$seconds = $time % 60;
		$time = ($time - $seconds) / 60;
		$minutes = $time % 60;
		$hours = ($time - $minutes) / 60;

		return $hours . ":" . $minutes . ":" . $seconds;
	  }

	   for ($i=0; $i < sizeof($start_time); $i++) { 
	   		$start = toFormat($start_time[$i]);
	   		$length = $end_time[$i] - $start_time[$i];
	   		$length = toFormat($length);

	   		if(! is_dir("upload/tmp/" . $filename))
	   			mkdir("upload/tmp/" . $filename, 0777);
	   		$outputfile = "upload/tmp/" . $filename . "/" . $i . "." . $extension;
	   		$concat = $concat . $outputfile . " ";


	   		shell_exec("ffmpeg -acodec copy -vcodec copy -ss $start -t $length -i upload/$filename $outputfile");
	   }
	   shell_exec("cat $concat > upload/tmp/$filename/final.$extension");
	   $outputfile = "upload/tmp/$filename/final.$extension";
	   $address = $outputfile;

?>

<body>
	<div id="container">
		<div id="top-div">
			<button id="back-button" type="button" onClick="window.location = 'workspace.php'">&lt; Home</button>
		</div>

		<div id="content">
			<?php if($error != 1) { ?>
			<button id="download-button" onclick="window.open('<?php echo $address ?>')" >
				Download
			</button>
			<? } else {?>
			<div id="error">
				Some Error Occurred
			</div>
			<? } ?>
		</div>
	</div>

	<script type="text/javascript">

	</script>
</body>
</html>
