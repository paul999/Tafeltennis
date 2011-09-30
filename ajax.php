<?php
require('config.php');
error_reporting(0);
$average = $count = null;
$xml = "<ajax>%s</ajax>";
header('Content-type: text/xml'); 
register_shutdown_function ('printer');
	
switch ($_REQUEST['mode'])
{
	case 'addteam':
		$naam = 'team';
		$nummer = (int)$_POST['team'];
		$poule = mysql_real_escape_string($_POST['poule']);
		$klasse = mysql_real_escape_string($_POST['klasse']);
		
		if (empty($nummer))
		{
			error("Teamnummber is leeg");
		}
		if (empty($poule))
		{
			error("Poule is leeg");
		}
		if (empty($klasse))
		{
			error("Klasse is leeg");
		}
		
		$sql = "INSERT INTO teams SET naam = '$naam', team = $nummer, minspelers = 3, poule = '$poule', klasse = '$klasse'";
		mysql_query($sql) or err(mysql_error());
		
		$xml = sprintf('<text>Team toegevoegd</text>', $xml);
		exit;
	break;
	
	case 'selectteam':
		$_SESSION['team'] = (int)$_POST['team'];
		
		$xml = sprintf('<text>Done</text>', $xml);
		exit;
	break;

	case 'login':
		$user = mysql_real_escape_string($_POST['user']);
		$pass = md5($_POST['password']);
		
		$sql = 'SELECT * FROM users WHERE username = \'' . $user . '\'';
		$result = mysql_query($sql) or err(mysql_error());
		
		if (!mysql_num_rows($result))
		{
			$xml = sprintf($xml, '<error>1</error><text>Foute gebruiker</text>');
			$_SESSION['login'] = false;					
		}
		else
		{
			$row = mysql_fetch_assoc($result);
			
			if (!$row || $row['password'] !== $pass)
			{
				$xml = sprintf($xml, '<error>1</error><text>Foute gebruiker</text>');
				$_SESSION['login'] = false;					
			}
			else
			{
				$xml = sprintf($xml, '<text>Ingelogd</text>');
				$_SESSION['login'] = true;	
				$_SESSION['id'] = $row['id'];					
			}
		}
		exit;
	break;
	
	case 'addgebruiker':
		$naam = mysql_real_escape_string($_POST['naam']);
		$recht = (int)$_POST['recht'];
		$ww = md5($_POST['wachtwoord']);
		$email = mysql_real_escape_string($_POST['email']);
		
		if (empty($naam))
		{
			error("Naam is leeg");
		}
		if (empty($_POST['wachtwoord']))
		{
			error("Wachtwoord is leeg");
		}
		if (empty($email))
		{
			error("Email is leeg");
		}
		
		$sql = "INSERT INTO users SET username = '$naam', access = $recht, `password` = '$ww', email = '$email'";
		mysql_query($sql) or err(mysql_error());
		
		$xml = sprintf('<text>Gebruiker toegevoegd</text>', $xml);
		exit;	
	
		exit;
	break;
		
}
	
error("No mode");
exit;

function printer()
{
	global $xml;
	echo $xml;
	exit;
}


function xml($result)
{
	$tmp = '';
	while ($row = mysql_fetch_assoc($result))
	{
		extract($row);
		$tmp .= "<row>";
	
		foreach ($row as $k => $v)
		{
			$tmp .= "<$k>$v</$k>";
		}
		$tmp .= '</row>';
	}
	return $tmp;
}
function tc()
{
	`touch /tmp/upd_fire2`;
}

function error($str)
{
	global $xml;
	$xml = sprintf($xml, "<error>1</error><text>$str</text>");
	exit;
}
function err($str)
{
	error("mySQL error, neem contact op met de webmaster. Error melding: \n$str");
	exit;
}
