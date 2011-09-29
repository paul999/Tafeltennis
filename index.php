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
	<script src="js/core.js" type="text/javascript"></script>
	<?php
		if (logged())
		{
			?>
			<script src="js/logged.js" type="text/javascript"></script>
			<?php
		}
		else
		{
			?>
			<script src="js/login.js" type="text/javascript"></script>
			<?php		
		}
	?>
	
	<title>Tafeltennis</title>
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
    
<h1>Hoi <?php echo $data['username']; ?></h1>
Je hebt toegang tot de volgende userlevels: <?php 
$has = false;

foreach ($levels as $lvl => $naam)
{
	if (allow($lvl, false))
	{
		if ($has)
		{
			echo ", ";
		}
		else
		{
			$has = true;
		}
		echo $naam;
	}
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
if (allow(admin, false))
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

