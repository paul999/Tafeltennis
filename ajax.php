<?php
require('config.php');
$average = $count = null;
	$xml = "<ajax>%s</ajax>";
	header('Content-type: text/xml'); 
	
	switch ($_REQUEST['mode'])
	{
		case 'addteam':
			$naam = 'team';
			$nummer = (int)$_POST['team'];
			$poule = mysql_real_escape_string($_POST['poule']);
			$klass = mysql_real_escape_string($_POST['klasse']);
			
			$sql = 'INSERT INTO ban SET ip = \'' . $ip . '\'';
			mysql_query($sql) or err(mysql_error());
			
			tc();
			
			$xml = sprintf('<text>banned</text>', $xml);
		break;

		case 'addserver':
		     $ip = mysql_real_escape_string($_POST['ip']);
		     $srv = mysql_real_escape_string($_POST['server']);

                        $sql = 'INSERT INTO servers SET ip = \'' . $ip . '\', srv = \'' . $srv . '\'';
			mysql_query($sql) or err(mysql_error());
			
			tc();

		        $xml = sprintf('<text>added</text>', $xml);
		break;
		
		case 'addservice':
			$ip = intval($_POST['port']);
			$srv = mysql_real_escape_string($_POST['service']);

			$sql = 'INSERT INTO services SET port = ' . $ip . ', service = \'' . $srv . '\'';
			mysql_query($sql) or err(mysql_error());

			tc();

			$xml = sprintf('<text>added</text>', $xml);
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
		break;
		
		case 'servers':
			$sql = 'SELECT * FROM servers ORDER BY srv';
			$result = mysql_query($sql);
			$tmp = xml($result);
			
			$xml = sprintf($xml, $tmp);
		break;

		case 'ban':
			$sql = 'SELECT * FROM ban ORDER BY ip';
			$result = mysql_query($sql) or err(mysql_error());
			$tmp = xml($result);
			
			$xml = sprintf($xml, $tmp);
		break;


		case 'services':
			$sql = 'SELECT * FROM services ORDER BY service';
			$result = mysql_query($sql) or err(mysql_error());
			$tmp = xml($result);
				
			$xml = sprintf($xml, $tmp);
		break;
		case 'servicesServer':

			$server = (int )$_POST['id'];
			$sql = 'SELECT r.* FROM runned r, servers s WHERE s.id = ' . $server . ' AND s.srv = r.srv ORDER BY service';
			$result = mysql_query($sql) or err(mysql_error());
			$tmp = "<oid>$server</oid>" . xml($result);
			
			$xml = sprintf($xml, $tmp);
		
		break;
		
		case 'access':

			$server = (int)$_REQUEST['id'];
			
			$sql = 'SELECT DISTINCT r.id, r.* FROM service r, runned s WHERE (s.srv = r.srv OR r.srv = \'*\') AND s.id = ' . $server .'  ORDER BY service';

			$result = mysql_query($sql) or err(mysql_error());
			$tmp = "<oid>$server</oid><sql>$sql</sql>" . xml($result);
			
			$xml = sprintf($xml, $tmp);
		
		break;		
	}
	echo $xml;
exit;


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
function err($str)
{
	global $xml;
	
	printf($xml, "<error>1</error><text>mySQL error, neem contact op met de webmaster. Error melding: \n$str</text>");
	die;
}
