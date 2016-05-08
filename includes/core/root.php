<?php

// Root class for include common functions

include dirname( dirname( __FILE__ ) ) . '/code/_safe.php';

abstract class Popup4Phone_Root
{
	public $cfg_var = 'POPUP4PHONE_CFG';
	public $plugin_id;
	public $plugin_id_;
	public $plugin_name;
	public $plugin_basename;
	public $plugin_version;
	public $plugin_file;
	public $plugin_url;

	public function root()
	{
  	return $this;
	}

	public function get_premium_name()
	{
		if (is_plugin_active("popup4phone-premium-g"."old") && file_exists($d = $this->plugins_dir()."/popup4phone-premium-p"."latinum"))
		{
    	return file_get_contents($d."/name.txt");
		}


		if (is_plugin_active("popup4phone-premium-p"."latinum") && file_exists($d = $this->plugins_dir()."/popup4phone-premium-g"."old"))
		{
      return file_get_contents($d."/name.txt");
		}

    throw new Exception("Can't detect name of the Premium version");
	}

	public function is_premium_installed()
	{
		return (is_plugin_active("popup4phone-premium-g"."old")
					|| is_plugin_active("popup4phone-premium-p"."latinum"));

		/*
  	$plugins_dir = dirname($this->plugin_dir());
		if (file_exists($plugins_dir."/popup4phone-premium-g"."old")
					|| file_exists($plugins_dir."/popup4phone-premium-p"."latinum"))
		{
			return true;
		}
		else
		{
			return false;
		}   */
	}

	public function __get($k)
	{
  	throw new Exception("$k attribute is not defined");
	}

	public function __set($k, $v)
	{
  	throw new Exception("$k attribute is not defined");
	}

	public function __call($f, $ps)
	{
  	throw new Exception("$f method is not defined");
	}

	public function hook()
	{

	}

	public function __construct()
	{
		$cfg = json_decode( constant( $this->cfg_var ), true );
		foreach ( $cfg as $k => $v )
		{
			$f        = 'plugin_' . $k;
			$this->$f = $v;
		}

		$this->plugin_id_ = str_ireplace('-', '_', $this->plugin_id);
	}

	public function version_current()
	{
		return get_option( $this->plugin_id . '_version' );
	}

	public function version_is_requires_updating()
	{
		return $this->version_current() != $this->plugin_version;
	}

	public function version_mark_updated()
	{
		return update_option( $this->plugin_id . '_version', $this->plugin_version );
	}

	public function getIp()
	{
		if ( !empty( $_SERVER['HTTP_CLIENT_IP'] ) )
		{
			$ip = $_SERVER['HTTP_CLIENT_IP'];
		}
		elseif ( !empty( $_SERVER['HTTP_X_FORWARDED_FOR'] ) )
		{
			$ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
		}
		else
		{
			$ip = $_SERVER['REMOTE_ADDR'];
		}
		return $ip;
	}

	public function tpl_path( $f )
	{
		return $this->plugin_dir() . '/' . $f;
	}

	public function url_plugin( $f )
	{
		return plugins_url( $f, $this->plugin_main_file() );
	}

	public function url_css( $f )
	{
		return $this->url_plugin( "css/$f" );
	}

	public function url_js( $f )
	{
		return $this->url_plugin( "js/$f" );
	}

	public function url_image( $f )
	{
		return $this->url_plugin( "images/$f" );
	}

	public function plugin_main_file()
	{
		return $this->plugin_dir() . "/" . $this->plugin_id . '.php';
	}

	public function plugins_dir()
	{
  	return dirname($this->plugin_dir());
	}

	public function plugin_dir()
	{
		return dirname( dirname( dirname( __FILE__ ) ) );
	}

	public function stack( $exit = true )
	{
		xdebug_print_function_stack();
		if ( $exit )
			exit;
	}

	public function hre( $v )
	{
		print "<hr>";
		var_dump( $v );
		exit;
	}

	public function hr( $v, $lbl = '' )
	{
		print "<hr><b>$lbl</b>:<br>";
		var_dump( $v );
	}

	// append params to URL
	public function url_add_params( $psx, $url = '' )
	{
		if ( !$url )
		{
			$url = $_SERVER['REQUEST_URI'];
		}
		$up       = parse_url( $url );
		$url_path = '';

		if ( isset( $up['host'] ) )
		{
			if ( isset( $up['scheme'] ) )
			{
				$url_path .= $up['scheme'] . ':';
			}
			$url_path .= '//' . $up['host'];
		}
		$url_path .= $up['path'];
		$pars = array();
		if ( isset( $up['query'] ) )
		{
			$url_query = $up['query'];
			parse_str( $url_query, $pars );
		}

		foreach ( $psx as $k => $v )
		{
			$pars[ $k ] = $v;
		}
		$url2 = $url_path . '?' . http_build_query( $pars );
		return $url2;
	}


	public function install()
	{
	}

	public function html_input( $attrs )
	{
  	$res = '';
		$res .= "<input ";
		foreach( $attrs as $k=>$v)
		{
			$k2 = esc_attr( $k );
			$v2 = esc_attr( $v );

			if (! in_array( $k, array( 'checked', 'required' ) ) )
      {
				$res .= " $k2 = '$v2' ";
			}
			else
			{
				if ( $v )
				{
      		$res .= " $k ";
				}
			}
		}

		$res .= " >";
		return $res;
	}

	public function html_field_hidden( $k, $v )
	{
		$k = esc_attr( $k );
		$v = esc_attr( $v );
		return "<input type='hidden' name='$k' value='$v'>";
	}

	public function html_a( $lbl, $url, $attrs = array() )
	{
		$attrs['href'] = $url;
		$out           = "<a";
		foreach ( $attrs as $k => $v )
			$out .= " $k = '" . esc_attr( $v ) . "' ";
		$out .= ">";
		$out .= $lbl;
		$out .= "</a>";
		return $out;
	}

	function array_to_csv( &$array, $use_keys_as_headers = true )
	{
		if ( count( $array ) == 0 )
		{
			return null;
		}

		ob_start();
		$df        = fopen( "php://output", 'w' );
		$delimiter = ";";

		if ( $use_keys_as_headers )
		{
			fputcsv( $df, array_keys( reset( $array ) ) ); //, $delimiter);
		}

		foreach ( $array as $row )
		{
			fputcsv( $df, $row ); //, $delimiter);
		}

		fclose( $df );

		return ob_get_clean();
	}

}
