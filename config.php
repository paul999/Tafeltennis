<?php
$user	= 'ttv';
$pass	= '';
$db		= 'ttv';
$host	= 'mysql.hosthuis.nl';

session_start();

$d = mysql_connect($host, $user, $pass);

if (!$d)
{
	die(mysql_error());
}
$d2 = mysql_select_db($db, $d) or die('ERROR: ' . mysql_error());

error_reporting(E_ALL);
ini_set('display_errors', 1);

function logged()
{
	return (isset($_SESSION['login']) && $_SESSION['login'] == true) ? true : false;
}

function allow($level)
{
	
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
	
	if (!$result && function_exists('err'))
	{
		err(mysql_error());
	}
	else if (!$result)
	{
		?>
		<h1>mySQL Error</h1>
		<p>Er heeft een mySQL error opgetreden, neem contact op met de webmaster.<br />Error:<br /><?php echo mysql_error(); ?></p>
		</body></html>
		<?php
		exit;
	}
	
	$data = @mysql_fetch_assoc($result);
	
}
