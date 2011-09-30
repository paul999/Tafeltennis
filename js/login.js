$(document).ready(function(){

	// generate markup
	$("#login").html("<div id='loginerr' class='error'></div><form id='formid'>Username: <input type='text' name='user' id='user' /><br />Password: <input type='password' name='ps' id='ps' /><br /><a href='#'>Login</a>");


	// add markup to container and apply click handlers to anchors
	$("#formid a").click(function(e){
		// stop normal link click
		e.preventDefault();
		$.loading_alert();


		// send request
		$.post("ajax.php", {mode: 'login', user: $("#user").attr("value"), password: $("#ps").attr("value")}, function(xml) {
			$('#loadingalert').fadeOut(100);
			$('#darkenwrapper').fadeOut(100);     	

			if ($("error",xml).text() == "1")
			{
				$("#loginerr").html($("text", xml).text());    	
				$('#loginerr').fadeIn(500);

				setTimeout(";$('#loginerr').fadeOut(500);", 5000);     		

			}
			else
			{
				$("#formid").html($("text", xml).text());
				location.href = location.href
			}
		});
	});
});
