<?php
require('../config.php');

session_start();

$d = mysql_connect($host, $user, $pass);

if (!$d)
{
	die(mysql_error());
}
$d2 = mysql_select_db($db, $d) or die('ERROR: ' . mysql_error());

error_reporting(E_ALL);
ini_set('display_errors', 1);

define('USER', 1, true);
define('ADMIN', 2, true);
define('COACH', 4, true);
define('BEHEER', 8, true);
define('OUDER', 16, true);
define('SPELER', 32, true);

//Beetje dubbelop maybe.
$levels = array(
	1 => 'User',
	2 => 'Admin',
	4 => 'Coach',
	8 => 'Beheer',
	16 => 'Ouder/verzorger',
	32 => 'Speler',
);

function logged()
{
	return (isset($_SESSION['login']) && $_SESSION['login'] == true) ? true : false;
}

function allow($level, $die = true)
{
	global $levels;
	if ($die)
	{
		if (!(level & $level))
		{
			displayError('Je hebt geen toegang tot deze functie. Voor deze functie is het toegangslevel ' . $levels[$level] . ' nodig.', 'Geen toegang');;
		}
	}
	else
	{
		return (bool)(level & $level);
	}
}
if (!isset($_SESSION['id']) || !is_numeric($_SESSION['id']) || $_SESSION['id'] <= 0)
{
	$_SESSION['login'] = false;
}
else
{
	$uid = (int)$_SESSION['id'];
	$sql = 'SELECT * FROM users WHERE id = ' . $uid;
	$result = @mysql_query($sql);
	
	if (!$result)
	{
		displayError('Er heeft een mySQL error opgetreden, neem contact op met de webmaster.<br />Error:<br />' . mysql_error(), 'mySQL Error');
	}
	
	$data = @mysql_fetch_assoc($result);
	define('LEVEL', $data['access'], true);
	
	if (!(level & USER))
	{
		displayError('Something went wrong, no user level set, but required.');	
	}
	
}

function displayError($msg, $tit = 'Error')
{
	if (function_exists('err'))
	{
		err($msg);
		exit;
	}
	?>
	<h1><?php echo $tit; ?></h1>
	<p><?php echo $msg; ?></p>
	</body></html>
	<?php
	exit;
}
