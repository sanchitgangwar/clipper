<html>
<head>
	<meta charset="utf-8">
	<title>Workspace</title>

	<script type="text/javascript" src="js/jquery.min.js"></script>
	<script type="text/javascript" src="js/jquery-ui-1.9.0.custom.min.js"></script>
	<script type="text/javascript" src="js/stepper.js"></script>
	<script type="text/javascript" src="js/mousehold.js"></script>

	<link rel="stylesheet" type="text/css" href="css/style_video.css">
	<link rel="stylesheet" type="text/css" href="css/slider.css">


</head>

<body>
	<div id="container">
		<div id="videodiv">
			<video id="video" src="howfast.ogg" type="video/ogv">		
			</video>

			<div class="video-controls">
				<div class="slider" id="seekslider"></div>
			</div>

			<div id="selected-line-div">
			</div>
		</div>


		<div id="select-div">
			<button id="select-button" href="">Hold to Select</button>
		</div>

		<div id="data"></div>
		<div id="width"></div>

		<form action="done.php" method="post" enctype="multipart/form-data">
			<input type="file" name="file" />
			<input type="text" name="start-times" id="start-times-input" />
			<input type="text" name="end-times" id="end-times-input"  />
			<input type="submit" value="Submit" name="submit_form" />
		</form>
	</div>

	<script>
		var video = document.getElementById('video');
		var index = 0;
		var start_time = new Array();
		var end_time = new Array();
		var stop_anim = false;

		$("#select-button").mousedown(insertStartTime);
		$("#select-button").mouseup(insertEndTime);

		function changeWidth(index) {
			var pixels_per_sec = $(".slider").width()/video.duration;
			var div_name = "#line-" + index;
			var new_width = $(div_name).width() +  0.05 * pixels_per_sec;
			$(div_name).width(new_width + 'px');
		}

		function insertStartTime() {
			stop_anim = false;
			start_time.push(video.currentTime);
			var left = $(".slider").width()/video.duration * video.currentTime;
			$("#start-times-input").val(start_time);
			$("#selected-line-div").append("<div class='new-line' id='line-" + index +"' style='width: 0; left: " + left + ";'></div>");

			
			// var interval = setInterval(function() { 
			// 		changeWidth(index); 
			// 		if(stop_anim == true){
			// 			clearInterval(interval);
			// 			return;
			// 		}
			// 	}, 50);
		}

		function insertEndTime() {
			end_time.push(video.currentTime);
			$("#end-times-input").val(end_time);
			stop_anim = true;
			index++;
		}

		function submit() {
			alert(';ere"');
		}
	</script>

	<script type="text/javascript">
		var video = document.getElementById('video');


		/* Called when Play/Pause button is clicked. */
		function playOrPause() {
  			if (video.ended || video.paused) {
    			video.play();
  				$('#video').removeClass('ended');
  				$('#video').addClass('playing');
  			} else {
    			video.pause();
  				$('#video').removeClass('playing');
  			}
		}

		/* Converts a string containing time to seconds. */
		function getSecsFromTime(hms) {
			hms = String(hms);
			var re = new RegExp('[0-9]+', 'g')
			seconds = hms.match(re);
			var seconds = parseInt(seconds[0]) * 60 * 60 + parseInt(seconds[1]) * 60 + parseInt(seconds[2]);
			return seconds;
		}

		if(!(video.paused || video.ended || video.seeking 
				|| video.readyState < video.HAVE_FUTURE_DATA || video.currentTime > 0)) {
				video.pause();
				document.getElementById('playpauseimg').src = 'images/play-icon.png';
  				$('#video').removeClass('playing');
  			}

  		var seeksliding = true;
  		// Seek Slider

		/* We need to keep polling the video until it is ready, otherwise
		 we can't determine the duration, and can't create the slider.*/
		t = window.setInterval(function(){
			if(video.readyState > 0) {
				$( ".slider:eq(0)" ).slider({
				    animate: true,
	                range: "min",
	                value: 0,
	                min: 0,
	                max: video.duration,
					step: 0.01,
	                
					//this gets a live reading of the value and prints it on the page
	                slide: function( event, ui ) {
	                	seeksliding = true;
	                	video.currentTime = ui.value;
	                },

					//this updates the hidden form field so we can submit the data using a form
	                change: function(event, ui) { 
	                
	                },		

	                stop: function(event, ui) {
	                	seeksliding: false;
	                }		
				});
				seekUpdate();
				$(".total-time-span").text(timeFormat('hh:mm:ss', video.duration));
				clearInterval(t);
			}
		}, 1000);


			/* Formats the time provided in 'seconds' to the provided 'format'.
			Format should contain hh,mm,ss.
			hh will be replaced with hours, mm with minutes, and ss with 
			seconds. */
			function timeFormat(format, seconds){

				var hours = 0;
				var mins = 0;
				var secs = 0;

				if(format.indexOf("hh") != -1 || format.indexOf("HH") != -1) {
					hours = Math.floor(seconds/3600);
				} 

				if(format.indexOf("mm") != -1 || format.indexOf("MM") != -1) {
					var mins = Math.floor((seconds - hours * 3600)/60);
				}

				if(format.indexOf("ss") != -1 || format.indexOf("SS") != -1) {
					var secs = Math.floor(seconds - hours * 3600 - mins * 60);	
				}

				if(hours < 10) hours = "0" + hours;
				if(mins < 10) mins = "0" + mins;
				if(secs < 10) secs = "0" + secs;	

				format = format.replace('hh', hours);
				format = format.replace('HH', hours);
				format = format.replace('mm', mins);
				format = format.replace('MM', mins);
				format = format.replace('ss', secs);
				format = format.replace('SS', secs);
				return format;
			}


			/* Updates the seek slider and current time. */
			function seekUpdate() {
				var currenttime = video.currentTime;
				$(".slider:eq(0)").slider('value', currenttime);


				if(!stop_anim) {
					var div_name = "#line-" + index;
					var new_width = ($(".slider").width()/video.duration ) * (video.currentTime - start_time[index]);
					$(div_name).width(new_width + 'px');
				}
			};
			
			// Call seekUpdate() whenever time of the audio/video changes.
			$("#video").bind('timeupdate', seekUpdate);

			// When the video is ended.
			video.onended = function(event) {
				$('#video').removeClass('playing');
				$('#video').addClass('ended');
			}

			video.canplay = function(event) {
			}

			$("#video").click(playOrPause);
	</script>
</body>

</html>