<!DOCTYPE HTML>
<html>
<head>
<title><?php echo $title?></title>
<meta http-equiv="content-type" content="text/html; charset=utf-8">
</head>

<body>

<h1><?php echo $title?></h1>

<?php
	foreach($ws_flds as $k=>$f)
	{
		echo "<b>$f[label]:</b> ";

		if ('ws_pages_path' != $k)
		{
			echo $f['value'];
		}
		else
		{
   		$ps = json_decode($f['value'], true);
			echo "<ul>";
			foreach($ps as $p)
			{
       	echo "<li>";
				echo "[".$p['time']."] ".$p['url'];
       	echo "</li>";
			}

			echo "</ul>";
		}

		echo "<br><br>";
	}

?>
</body>

</html>

<?php

