<html>
	<head>
		<title>Done</title>
	</head>
	
	<?php 
		echo $_SERVER['REMOTE_ADDR'] . "<br/>";
		$start_time = explode(',', $_POST['start-times']);
		$end_time = explode(',', $_POST['end-times']);


		$allowedExts = array("webm", "mp4", "ogg", "ogv");
		$extension = end(explode(".", $_FILES["file"]["name"]));

		if ($_FILES["file"]["size"] < 20000000 && in_array($extension, $allowedExts))
		  {
		  if ($_FILES["file"]["error"] > 0)
		    {
		    echo "Return Code: " . $_FILES["file"]["error"] . "<br>";
		    }
		  else
		    {
		    echo "Upload: " . $_FILES["file"]["name"] . "<br>";
		    echo "Type: " . $_FILES["file"]["type"] . "<br>";
		    echo "Size: " . ($_FILES["file"]["size"] / 1024) . " kB<br>";
		    echo "Temp file: " . $_FILES["file"]["tmp_name"] . "<br>";

		    $ip_addr = str_replace('.', '', $_SERVER['REMOTE_ADDR']);
		    $filename = $ip_addr . $_FILES["file"]["name"];
		    $filename = str_replace(' ', '', $filename);

		    if (file_exists("upload/" . $filename))
		      {
		      echo $_FILES["file"]["name"] . " already exists. ";
		      }
		    else
		      {
		      move_uploaded_file($_FILES["file"]["tmp_name"],
		      "upload/" . $filename);
		      echo "Stored in: " . "upload/" . $filename;
		      }
		    }
		  }
		else
		  {
		  echo "Invalid file";
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

		  $concat = "\"concat:";
		   for ($i=0; $i < sizeof($start_time); $i++) { 
		   		$start = toFormat($start_time[$i]);
		   		$length = $end_time[$i] - $start_time[$i];
		   		$length = toFormat($length);

		   		if(! is_dir("upload/tmp/" . $filename))
		   			mkdir("upload/tmp/" . $filename, 0777);
		   		$outputfile = "upload/tmp/" . $filename . "/" . $i . "." . $extension;
		   		$concat = $concat . $outputfile . "|";


		   		echo "<br/>ffmpeg -acodec copy -vcodec copy -ss $start -t $length -i upload/$filename $outputfile<br/>";
		   		shell_exec("ffmpeg -acodec copy -vcodec copy -ss $start -t $length -i upload/$filename $outputfile");
		   }
		   $concat[strlen($concat)-1] = "\"";
		   echo "<br/>ffmpeg -i $concat -c copy upload/tmp/$filename/final.$extension<br/>";
		   shell_exec("ffmpeg -i $concat -codec: copy upload/tmp/$filename/final.$extension");

	?>

</html>
