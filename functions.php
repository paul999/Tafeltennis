<?php
function logged()
{
	return (isset($_SESSION['login']) && $_SESSION['login'] == true) ? true : false;
}

function allow($level, $die = true, $override = false)
{
	$allow = false;
	if (is_array($level))
	{
		// Special case.
		
		foreach ($level as $lvl)
		{
			if (allow($lvl, false))
			{
				$allow = true;
				break;
			}
			else
			{
				$allow = false; // Set explicit to false
			}
		}
	}
	else
	{
		if ($override !== false)
		{
			$allow = (($overide & $level)) ? true : false;
		}
		else
		{
			$allow = ((level & $level)) ? true : false;
		}
	}

	global $levels;
	if ($die)
	{
		if (!$allow)
		{
			$rest = '';
			if (is_array($level))
			{
				$i = 0;
				foreach ($level as $lvl)
				{
					if ($i > 0)
					{
						$rest .= ' of'; 
					}
					$rest .= ' ' . $levels[$lvl];
					$i++;
				} 
			}
			else
			{
				$rest = ' ' . $levels[$level];
			}
			displayError('Je hebt geen toegang tot deze functie. Voor deze functie is het toegangslevel' . $rest . ' nodig.', 'Geen toegang');;
		}
		else
		{
			return true;
		}
	}
	else
	{
		return (bool)$allow;
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

function sqlE()
{
	displayError('Er heeft een mySQL error opgetreden, neem contact op met de webmaster.<br />Error:<br />' . mysql_error(), 'mySQL Error');
}

function teamAllow($uid = false, $permissie = false)
{
	global $data;
	
	if ($permissie !== true && $uid === false)
	{
		return false;
	}
	
	if ($permissie === false)
	{
		$permissie = level;
	}
	if ($uid === false)
	{
		$uid = $data['id'];
	}
	
	if (allow(array(beheer, admin), false, $permissie))
	{
		$sql = 'SELECT team FROM teams';
	}
	else
	{
		$sql = 'SELECT team, id FROM teams t LEFT JOIN teamuser u ON t.team = u.team WHERE u.user = '. (int)$uid;
	}
	
	$result = mysql_query($sql) or sqlE();
	
	if (!mysql_num_rows($result))
	{
		return false;
	}
	$teams = array();
	while ($row = mysql_fetch_assoc($result))
	{
		$teams[] = array(
			'id' => $row['id'],
			'team' => $row['team'],
		);
	}
	return $teams;
}

