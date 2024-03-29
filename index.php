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
<ul class="tabs">
	<?php
	if (allow(array(speler, coach, beheer, admin), false))
	{
	?>
    <li><a href="#competitie">Competitie overzicht</a></li>
    <?php
    }
    if (allow(array(admin, beheer), false))
    {
    ?>
    <li><a href="#gebruikertoevoegen">Gebruiker toevoegen</a></li>
    <li><a href="#teamtoevoegen">Teams toevoegen</a></li>
    <?php
	}
    ?>
    <li><a href="#tab2">Wijzig gegevens</a></li>
</ul>
<div class="tab_container">
<?php
if (allow(array(speler, coach, beheer, admin)))
{
	?><div id="competitie" class="tab_content"><?php
	
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
			<div id="teamselect">
			<h3>Selecteer team</h3>
			<p>Selecteer het team waarvan je het competitie overzicht wilt zien:</p>
			<form id="selectTeam">
			<select id="teams">
				<option>Selecteer team</option>
				<?php
				for ($i = 0; $i < sizeof($teams); $i++)
				{
					echo "<option value='{$teams[$i]['id']}'>Team {$teams[$i]['team']}</option>";
				}
				?>
			</select>
			<input type="submit" id="selectteam" value="Selecteer"/>
			</form>
			</div>
			<?php
		}
		if (isset($_SESSION['team']))
		{
		
			// Selecteer team data.
			$sql = 'SELECT * FROM teams WHERE id = '  . (int)$_SESSION['team'];
			$result = mysql_query($sql) or sqlE();
			$team = mysql_fetch_assoc($result);
		
			
			// Hier competitie overzicht.
			
			$sql = 'SELECT * FROM wedstrijden WHERE team = ' . (int)$_SESSION['team'];
			$result = mysql_query($sql) or sqlE();
			
			$wedstrijden = array();
			$ids = array();
			$uitslagen = array();
			$spelers = array();
			$sp = array();
			$speler = array();
			$vast = array();
			$coach = array();			
			
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
			
			$sql = 'SELECT * FROM teamuser WHERE team = ' . $team['id'];
			$result = mysql_query($sql) or sqlE();
			
			while ($row = mysql_fetch_assoc($result))
			{
				$sp[] = $row['user'];
				
				if ($row['functie'] == COACH)
				{
					$coach[] = $row;
				}
				else
				{
					$vast[] = $row;
				}
			}
			echo '<span class="left">';
			echo '<h2>Teamgegevens</h2>';
			
			if (sizeof($vast) < $team['minspelers'])
			{
				echo '<p id="geenspelers"><strong>LETOP:</strong>Aantal spelers in dit team is lager als het minimum!</p>';
			}	
			if (sizeof($coach) == 0)
			{
				echo "<p id='geencoach'>Er is nog geen coach voor dit team bepaald.</p>";
			}
			
			if (sizeof($sp))
			{
				// Selecteer naamgegevens ed van spelers.
				$sql = 'SELECT * FROM users WHERE id IN (' . implode($sp, ', ') . ')';
				
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
			echo '<strong>Coach(es)</strong>: <ul id="coach">';
			
			foreach ($coach as $c)
			{
				if (!isset($speler[$c['user']]))
				{
					echo "<p>Ik heb een coach gevonden, maar geen naam :(</p>";
					continue;
				}
				echo "<li>" . $speler[$c['user']]['username'] . "</li>"; 
			}
			echo '</ul>';
			
			echo "<strong>Spelers:</strong><ul id='spelers'>";
			foreach ($vast as $v)
			{
				if (!isset($speler[$v['user']]))
				{
					echo "<li>Ik heb een speler gevonden, maar geen naam :(</li>";
					continue;
				}
				echo "<li>" . $speler[$v['user']]['username'] . "</li>"; 			
			}
			echo "</ul>";
			
			if (allow(array(beheer, admin), false))
			{
				echo '<p><a href="#" id="coachlink">Coach toevoegen</a> || <a href="#" id="spelerlink">Speler toevoegen</a></p>';
			}			
			echo '</span><span class="right">';
			echo "<h2>Wedstrijden voor team " . $team['team'] . "</h2>";
			
			if (sizeof($wedstrijden) == 0)
			{
				echo "<p>Er zijn nog geen wedstrijden ingevuld voor team " . $team['team'] . "</p>";						
			}
			else
			{
			
			
			}
			echo '</span>';
		}
		else
		{
			?><p>Selecteer rechts bovenin een team.</p><?php
		}
	}
	echo "</div>";// End tab div.
}

if (allow(admin, false))
{
?><!--
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
</tr>-->
<?php
}

if (allow(array(admin, beheer), false))
{
	?>
	<div id="teamtoevoegen" class="tab_content">
	
		<form id="teamToevoegenform">
			<div id="teamToevoegenOk" class="success">Team toegevoegd</div>
			<div id="teamToevoegenErr" class="error"></div>
			Teamnummer: <input type="text" id="teamnummer" /><br>
			Klasse: <input type="text" id="teamklasse" /><br>
			Poule: <input type="text" id="teampoule" /><br>
			<input type="submit" id="sendteamtoevoegen" value="Opslaan"/>
		</form>
	</div>

	<div id="gebruikertoevoegen" class="tab_content">
	
		<form id="gebruikerToevoegenform">
			<div id="gebruikerToevoegenOk" class="success">Gebruiker toegevoegd</div>
			<div id="gebruikerToevoegenErr" class="error"></div>
			Naam: <input type="text" id="naam" /><br>
			Wachtwoord: <input type="password" id="wachtwoord" /><br>
			Email: <input type="text" id="email" /><br>
			Rechten:<br>
			<?php
			foreach ($levels as $recht => $naam)
			{			
				echo "<input type='checkbox' value='$recht' id='r$recht'/><label for='r$recht'>$naam</label><br>";
			}
			?>
			
			<input type="submit" id="sendgebruikertoevoegen" value="Opslaan"/>
		</form>
	</div>
	<?php
}
?>
</div>
<div id="darkenwrapper">
	<div id="darken">&nbsp;</div>
	<div class="jalert" id="loadingalert"><h3>Laden...</h3><p>Een moment geduld...</p></div>
</div>  
	<div id="phpbb_alert" class="phpbb_alert">
		<a href="#"><img src="images/alert_close.png" class="alert_close" alt="" /></a>
		<h3></h3><p></p>
	</div>
	<div id="phpbb_confirm" class="phpbb_alert">
		<a href="#"><img src="images/alert_close.png" class="alert_close" alt="" /></a>
		<p></p>
		<input type="button" class="button1" value="Ja" />&nbsp;
		<input type="button" class="button2" value="Nee" />
	</div>
	<div id="toevoegen" class="phpbb_alert">
		<a href="#"><img src="images/alert_close.png" class="alert_close" alt="" /></a>
		<div id="toevoegenErr" class="error"></div>
		<div id="toevoegenOk" class="success"></div>
		<p>Selecteer gebruiker om toe te voegen:</p>
		<select id="gebruikerslijst">

		</select>
		<input type="hidden" id="mode" value="" />
		<input type="button" id="gebruikersbutton" value="Opslaan" />
	</div>
  </body>
</html>

