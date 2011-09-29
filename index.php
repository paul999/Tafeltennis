<?php
require('config.php');
?>
<!DOCTYPE html>
<html dir="ltr" lang="en-gb">
<head>
<meta charset="utf-8">
  <style type="text/css">
 .success {  
     display: none; /* hide the sucess div */  
     /* add some styling */  
     padding:10px;  
     color: #044406;  
     font-size:12px;  
     background-color: #B7FBB9;  
 }
 
 .error {  
 	display:none;
     /* add some styling */  
     padding:10px;  
     color: #044406;  
     font-size:12px;  
     background-color: red;  
 } 
 

/* jQuery popups
---------------------------------------- */
.phpbb_alert {
	background-color: #FFFFFF;
	border: 1px solid #999999;
	position: fixed;
	display: none;
	top: 100px;
	left: 35%;
	width: 30%;
	z-index: 50;
	padding: 25px;
	padding: 0 25px 20px 25px;
}

.phpbb_alert img.alert_close {
	float: right;
	margin-top: -7px;
	margin-right: -30px;
}

.phpbb_alert p {
	margin: 8px 0;
	padding-bottom: 8px;
}

#darkenwrapper {
	display: none;
}

#darken {
	position: fixed;
	left: 0;
	top: 0;
	width: 100%;
	height: 100%;
	background-color: #000000;
	opacity: 0.5;
} 

/* jQuery popups
---------------------------------------- */
.jalert {
	background-color: #FFFFFF;
	border: 1px solid #999999;
	position: fixed;
	display: none;
	top: 100px;
	left: 35%;
	width: 30%;
	z-index: 50;
	padding: 25px;
	padding: 0 25px 20px 25px;
}

.jalert p {
	margin: 8px 0;
	padding-bottom: 8px;
}
	</style>
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.6.2/jquery.min.js" type="text/javascript"></script>
	<title>Intern hosthuis</title>

    <script type="text/javascript">
var ENTER = 13, ESC = 27;
$.loading_alert = function() {
	if ($('#darkenwrapper').is(':visible'))
	{
		$('#loadingalert').fadeIn(100);
	}
	else
	{
		$('#loadingalert').show();
		$('#darkenwrapper').fadeIn(100, function() {
			setTimeout(function() {
				if ($('#loadingalert').is(':visible'))
				{
					$.alert("Error", "Processing Error, please try again.");
				}
			}, 5000);
		});
	}

	return $('#loadingalert');
}    

/**
 * Display a simple alert similar to JSs native alert().
 *
 * @param string title Title of the message, eg "Information"
 * @param string msg Message to display. Can be HTML.
 * @param bool fadedark Remove the dark background when done? Defaults
 * 	to yes.
 *
 * @returns object Returns the div created.
 */
$.alert = function(title, msg, fadedark) {
	var div = $('#phpbb_alert');
	div.find('h3').html(title);
	div.find('p').html(msg);

	div.bind('click', function(e) {
		e.stopPropagation();
		return true;
	});
	$('#darkenwrapper').one('click', function(e) {
		var fade = (typeof fadedark !== 'undefined' && !fadedark) ? div : $('#darkenwrapper');
		fade.fadeOut(100, function() {
			div.hide();
		});
		return false;
	});

	$(document).bind('keydown', function(e) {
		if (e.keyCode === ENTER || e.keyCode === ESC) {
			$('#darkenwrapper').trigger('click');
			return false;
		}
		return true;
	});

	div.find('.alert_close').one('click', function() {
		$('#darkenwrapper').trigger('click');
	});

	if ($('#loadingalert').is(':visible'))
	{
		$('#loadingalert').fadeOut(100, function() {
			$('#darkenwrapper').append(div);
			div.fadeIn(100);
		});
	}
	else if ($('#darkenwrapper').is(':visible'))
	{
		$('#darkenwrapper').append(div);
		div.fadeIn(100);
	}
	else
	{
		$('#darkenwrapper').append(div);
		div.show();
		$('#darkenwrapper').fadeIn(100);
	}

	return div;
}

    
		$(document).ready(function(){
			// Your code here
			<?php
				if (!logged())
				{
				?>
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
     		location.href = location.href;
     	}
     });
   });
			<?php
			}
			else
			{
			?>

			
			function selectBan()
			{
				$("#ban").html("");
				$.ajax({
					url: 'ajax.php',
					data: {mode: 'ban'},
					type: 'POST',
					beforeSend: function() { $('#wait5').show(); },
					complete: function() { $('#wait5').hide(); },
					success: function(xml){parseBan(xml); /*setTimeout(function() {selectBan();} , 5000);*/}
				});				
			}
			
			function parseServers(xml)
			{
				  $(xml).find("row").each(function()
				  {
					$("#servers").append("<li>" + $(this).find("srv").text() + "(" + $(this).find("ip").text() + ")</li>");
					
					// Select services for that server
					
					//
					var id = $(this).find("id").text();
					$("#servers2").append("<li id='serv" + id + "'>" + $(this).find("srv").text() + "<ul id=\"srv" + id + "\"><li id='waitS" + id + "'><img src='ajax-loader.gif'   /></li></ul></il>");
					$("#servers3").append("<li id='acc" + id + "'>" + $(this).find("srv").text() + "<ul id=\"ac" + id + "\"><li id='waitA" + id + "'><img src='ajax-loader.gif'   /></li></ul></il>");
					
				$.ajax({
					url: 'ajax.php',
					data: {mode: 'servicesServer', id: id },
					type: 'POST',
					beforeSend: function() { $('#waitS' + id).show(); },
					success: function(xml) { 
							var id = $("oid", xml).text();
							$('#waitS' + id).hide();
							$('#waitA' + id).hide();
							
							$("#srv" + id).append("<li><div id='srv_" + id + "_success' class='success'>Service opgeslagen</div><form>Service: <input type='text' id='srv_service_" + id  + "' /><br /><input type='submit' id='srv_sm_" + id + "' value='submit' /></form></li>");
							$('#srv_sm_' + id).click(function(e){
								e.preventDefault();
								srv_submit(id);
							});								
							
							$(xml).find("row").each(function()
							{
								var id2 = $(this).find("id").text();
								$("#srv" + id).append("<li>" + $(this).find("service").text() + "</li>");
								$("#ac" + id).append("<li>" + $(this).find("service").text() + "</li><ul id='ac_" + id2 + "'><li id='wait_A" + id2 + "'><img src='ajax-loader.gif'   /></li></ul>");
								
								$.ajax({
									url: 'ajax.php',
									data: {mode: 'access', id: id2 },
									type: 'POST',
									beforeSend: function() { $('#wait_A' + id).show(); },
									success: function(xml) { 
										var id = $("oid", xml).text();
										$('#wait_A' + id).hide();
							
										$(xml).find("row").each(function()
										{
											$("#ac_" + id).append("<li>" + $(this).find("ip").text() + "</li>");						
										});
										
										$("#ac_" + id).append("<li><div id='ac_" + id + "_success' class='success'>Access opgeslagen</div><form>IP: <input type='text' id='ac_ip_" + id  + "' /><br /><input type='submit' id='ac_sm_" + id + "' value='submit' /></form></li>");
										$('#ac_sm_' + id).click(function(e){
											e.preventDefault();
											ac_submit(id);
										});
									}
								});								
							});
						}
					});
				});	
					
				$('#wait3').hide();
				$('#wait4').hide(); 		 		
			}
			
			function ac_submit(id)
			{
					$('#ac_sm_' + id).attr({'disabled' : 'true', 'value' : 'Sending...' });	
					
					var ip = $("#ac_ip_" + id).attr("value");
					
					$.ajax({
						url: 'ajax.php',
						data: {mode: 'addaccess', 'ip': ip, 'server': id},
						type: 'POST',
						beforeSend: function() { $('#wait5').show(); },
						complete: function() { $('#wait5').hide(); },
						success: function()
						{
							
							
							$('#ac_sm_' + id).attr({'disabled' : false, 'value' : 'submit' });
							$('#ac_' + id + '_success').fadeIn(500);
							
							setTimeout(";$('#ac_" + id + "_success').fadeOut(500);", 1000);
							setTimeout(selectServers, 1000);
						}
					});		
			}
			
			function srv_submit(id)
			{
					$('#srv_sm_' + id).attr({'disabled' : 'true', 'value' : 'Sending...' });	
					
					var ip = $("#ac_srv_" + id).attr("value");
					
					$.ajax({
						url: 'ajax.php',
						data: {mode: 'serverservice', 'service': ip, 'server': id},
						type: 'POST',
						beforeSend: function() { $('#wait5').show(); },
						complete: function() { $('#wait5').hide(); },
						success: function()
						{
							
							
							$('#srv_sm_' + id).attr({'disabled' : false, 'value' : 'submit' });
							$('#srv_' + id + '_success').fadeIn(500);
							
							setTimeout(";$('#srv_" + id + "_success').fadeOut(500);", 1000);
							setTimeout(selectServers, 1000);
						}
					});		
			}			
			
			function parseServices(xml)
			{
				  $(xml).find("row").each(function()
				  {
					$("#services").append("<li>" + $(this).find("service").text() + "(" + $(this).find("port").text() + ")</li>");
				  });	
			}
			function parseBan(xml)
			{
				  $(xml).find("row").each(function()
				  {

					$("#ban").append("<li>" + $(this).find("ip").text() + "</li>");
				  });			
			}
			
			$('#send_message_ban').click(function(e){
					//stop the form from being submitted
					e.preventDefault();
		
					$('#send_message_ban').attr({'disabled' : 'true', 'value' : 'Sending...' });	
					
					var ip = $("#addBanIp").attr("value");
					
					$.ajax({
						url: 'ajax.php',
						data: {mode: 'addban', 'ip': ip},
						type: 'POST',
						beforeSend: function() { $('#wait5').show(); },
						complete: function() { $('#wait5').hide(); },
						success: function()
						{
							selectBan();
							
							$('#send_message_ban').attr({'disabled' : false, 'value' : 'Add ban' });
							$('#ban_success').fadeIn(500);
							
							setTimeout(";$('#ban_success').fadeOut(500);", 1000);
						}
					});					
					
			});
			
			$('#send_message_server').click(function(e){
					//stop the form from being submitted
					e.preventDefault();
		
					$('#send_message_server').attr({'disabled' : 'true', 'value' : 'Sending...' });	
					
					var ip = $("#addServerIp").attr("value");
					var server = $("#addServer").attr("value");
					
					$.ajax({
						url: 'ajax.php',
						data: {mode: 'addserver', 'ip': ip, 'server': server},
						type: 'POST',
						beforeSend: function() { $('#wait5').show(); },
						complete: function() { $('#wait5').hide(); },
						success: function()
						{
							selectServers();
							
							$('#send_message_server').attr({'disabled' : false, 'value' : 'Add server' });
							$('#server_success').fadeIn(500);
							
							setTimeout(";$('#server_success').fadeOut(500);", 1000);
						}
					});					
					
			});		
			
			$('#send_message_service').click(function(e){
					//stop the form from being submitted
					e.preventDefault();
		
					$('#send_message_service').attr({'disabled' : 'true', 'value' : 'Sending...' });	
					
					var ip = $("#addServicePort").attr("value");
					var server = $("#addService").attr("value");
					
					$.ajax({
						url: 'ajax.php',
						data: {mode: 'addservice', 'port': ip, 'service': server},
						type: 'POST',
						beforeSend: function() { $('#wait5').show(); },
						complete: function() { $('#wait5').hide(); },
						success: function()
						{
							selectServices();
							selectServers();
							
							$('#send_message_service').attr({'disabled' : false, 'value' : 'Add service' });
							$('#service_success').fadeIn(500);
							
							setTimeout(";$('#service_success').fadeOut(500);", 1000);
						}
					});					
					
			});				
			
 
			<?php
			}
			?>
		});  
    </script>
  </head>
  <body>
    <?php
    if (!logged())
    {
    	?>
    	<div id="login"><h1>Error</h1><p>Deze applicatie vereist javascript, zonder javascript zal hij <strong>niet</strong> werken.</p>
    	</div>
    	

		<div id="darkenwrapper">
			<div id="darken">&nbsp;</div>
			<div class="jalert" id="loadingalert"><h3>Laden...</h3><p>Een moment geduld...</p></div>
		 </div>    	
		<div id="phpbb_alert" class="phpbb_alert">
			<a href="#"><img src="images/alert_close.png" class="alert_close" /></a>
			<h3></h3><p></p>
		</div>
		<div id="phpbb_confirm" class="phpbb_alert">
			<a href="#"><img src="images/alert_close.png" class="alert_close" /></a>
			<p></p>
			<input type="button" class="button1" value="{L_YES}" />&nbsp;
			<input type="button" class="button2" value="{L_NO}" />
		</div>		 
    	</body>
    	</html>
    	<?php
    	exit;
    }
    ?>
    
<table border="0" width="100%">
<tr>
	<td width="50%" valign="top">
		<h1>Servers</h1>
		<img src="ajax-loader.gif" id="wait1" />
		
		<ul id="servers">
		</ul>
		
		<form id="sendServer">
		<div id='server_success' class='success'>Server opgeslagen</div>
		IP: <input type="text" id="addServerIp" /><br />
		Server: <input type="text" id="addServer" /><br />
		<input type="submit" id="send_message_server" value="Add server" />
		</form>		
		
	</td>
	<td valign="top">
		<h1>Services</h1>
		<img src="ajax-loader.gif" id="wait2" />
		<ul id="services">
		</ul>
		
		<form id="sendService">
		<div id='service_success' class='success'>Service opgeslagen</div>
		Port: <input type="text" id="addServicePort" /><br />
		Service: <input type="text" id="addService" /><br />
		<input type="submit" id="send_message_service" value="Add service" />
		</form>			
	</td>
</tr>
	<td valign="top">
		<h1>Services op servers</h1>
		<img src="ajax-loader.gif" id="wait3" />
		
		<ul id="servers2">
		</ul>		
	</td>
	<td valign="top">
		<h1>Access control</h1>
		<img src="ajax-loader.gif" id="wait4" />
		<ul id="servers3">
		</ul>
</tr>
<?php
if (allow('admin'))
{
?>
<tr>
	<td>
		<h1>Banned</h1>
		<img src="ajax-loader.gif" id="wait5" />	
		<ul id="ban">
			
		</ul>		
		
		<form id="sendBan">
		<div id='ban_success' class='success'>Ban opgeslagen</div>
		IP: <input type="text" id="addBanIp" /><br />
		<input type="submit" id="send_message_ban" value="Add ban" />
		</form>
	</td>
</tr>
<?php
}
?>
</table>
<div id="darkenwrapper">
	<div id="darken">&nbsp;</div>
	<div class="jalert" id="loadingalert"><h3>Laden...</h3><p>Een moment geduld...</p></div>
</div>  
	<div id="phpbb_alert" class="phpbb_alert">
		<a href="#"><img src="images/alert_close.png" class="alert_close" /></a>
		<h3></h3><p></p>
	</div>
	<div id="phpbb_confirm" class="phpbb_alert">
		<a href="#"><img src="images/alert_close.png" class="alert_close" /></a>
		<p></p>
		<input type="button" class="button1" value="{L_YES}" />&nbsp;
		<input type="button" class="button2" value="{L_NO}" />
	</div>
  </body>
</html>

