<?php

// autoload - TODO cache file structure on first load
function popup4phone_autoload( $class )
{
	/*if (stristr($class, 'table') && stristr($class, 'popup4phone'))
	{
		print $class."<hr>";
  	xdebug_print_function_stack();
		exit;
	} */

	$class = strtolower( $class );
	$class = str_replace( '_', '-', $class );
  $class2 = str_replace( "popup4phone-", '', $class );

	$subdirs = array(
		'',
		'components',
		'core',
		);
	foreach( $subdirs as $sd )
	{
		if ($sd)
			$sd .= "/";

		$inc = dirname( __FILE__ ) . "/{$sd}class-$class.php";
		if ( file_exists( $inc ) )
		{
			include_once $inc;
		}

		$inc2 = dirname( __FILE__ ) . "/{$sd}$class2.php";
		//print "<hr>$inc2<hr>";
		if ( file_exists( $inc2 ) )
		{
			include_once $inc2;
		}

	}
}

spl_autoload_register( 'popup4phone_autoload' );