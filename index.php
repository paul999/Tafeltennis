<?php
require('config.php');
?>
<!DOCTYPE html>
<html dir="ltr" lang="en-gb">
<head>
<meta charset="utf-8">
	<link href="css/main.css" rel="stylesheet" type="text/css" media="screen, projection" />
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.6.4/jquery.min.js" type="text/javascript"></script>
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
			<input type="button" class="button1" value="Ja" />&nbsp;
			<input type="button" class="button2" value="Nee" />
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

<?php
if (allow(array(speler, coach)))
{
	echo '<h1>Competitie overzicht</h1>';
	$teams = teamAllow();
	
	if ($teams === false)
	{
		echo "Geen teams gevonden met toegang.";
	}
	else
	{
		if (sizeof($teams) == 1 && !isset($_SESSION['team']))
		{
			$_SESSION['team'] = $teams[0];
		}
	
		if (sizeof($teams) > 1)
		{
			?>
			<h2>Selecteer team</h2>
			<p>Selecteer het team waarvan je het competitie overzicht wilt zien:</p>
			<select id="teams">
				<option>Selecteer team</option>
				<?php
				for ($i = 0; $i < sizeof($teams); $i++)
				{
					echo "<option value='{$teams['id']}'>{$teams['team']}</option>";
				}
				?>
			</select>
			
			<?php
		}
		if (isset($_SESSION['team']))
		{
			// Hier competitie overzicht.
			
			$sql = 'SELECT * FROM wedstrijden WHERE team = ' . (int)$_SESSION['team'];
			$result = mysql_query($sql) or sqlE();
			
			if (!mysql_num_rows($result))
			{
				echo "<p>Er zijn nog geen wedstrijden ingevuld voor dit team</p>";
			}
			else
			{
				$wedstrijden = array();
				$ids = array();
				$uitslagen = array();
				$spelers = array();
				$sp = array();
				$speler = array();
				while ($row = mysql_fetch_assoc($result))
				{
					$ids[] = $row['id']; // Selecteer uitslagen zo :)
					$wedstrijden[] = $row;
				}
				
				
				if (sizeof($ids))
				{
					// Selecteer uitslagen voor deze wedstrijden.
					$sql = 'SELECT * FROM uitslagen WHERE wedstrijd IN (' . implode($ids, ', ') . ')';
					$result = mysql_query($sql) or sqlE();
					
					if (mysql_num_rows($result))
					{
						while ($row = mysql_fetch_assoc($result))
						{
							$uitslagen[$row['wedstrijd']] = $row;
						}
					}
					
					// Selecteer alle benodigde spelers enzo.
					$sql = 'SELECTEER * FROM spelerwedstrijd WHERE wedstrijd IN (' . implode($ids, ', ') . ')';
					$result = mysql_query($sql) or sqlE();
					
					if (mysql_num_rows($result))
					{
						while ($row = mysql_fetch_assoc($result))
						{
							$spelers[$row['wedstrijd']] = $row;
							$sp[] = $row['spelerid'];
						}
					}
				}
				
				if (sizeof($sp))
				{
					// Selecteer naamgegevens ed van spelers.
					$sql = 'SELECT * FROM spelers WHERE id IN (' . implode($sp, ', ') . ')';
					
					$result = mysql_query($sql) or sqlE();
					
					if (mysql_num_rows($result))
					{
						while ($row = mysql_fetch_assoc($result))
						{
							$speler[$row['id']] = $row;
						}
					}
				}
				// Zo, alle data is daar. Nu overzichtjes maken.
				
				
			}
		}
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
		<input type="button" class="button1" value="Ja" />&nbsp;
		<input type="button" class="button2" value="Nee" />
	</div>
  </body>
</html>

