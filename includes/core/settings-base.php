<?php

// Root class for settings

include dirname( dirname( __FILE__ ) ) . '/code/_safe.php';

abstract class Popup4Phone_Settings_Base extends Popup4Phone_Root
{

	public function values()
	{
		$res  = array();
		$opts = $this->opts_meta();
		foreach ( $opts as $section_id => $sd )
		{
			foreach ( $sd['settings'] as $k => $opt )
			{
				$res[$k] = $this->opt( $k );
			}
		}

		return $res;
	}

	public function page_slug()
	{
		return 'settings';
	}

	public function url_settings()
	{
		return "admin.php?page=" . $this->page_id();
	}

	public function page_id()
	{
		return $this->plugin_id . '-' . $this->page_slug();
	}

	public function opt( $key )
	{
		$opt = $this->opts_meta( $key );
		$k2  = $this->plugin_id . "_" . $key;

		if ( $opt && !empty( $opt['default'] ) )
		{
			return get_option( $k2, $opt['default'] );
		}
		else
		{
			return get_option( $k2 );
		}
	}

	abstract public function opts_meta( $key = '' );

	public function settings_field( $args )
	{
		$key = $args['key'];
		$opt = $this->opts_meta( $key );
		$v   = $this->opt( $key );
		$k2  = $opt['k_full'];
    $ps  = array();
    //$this->hr($opt);

		$attrs = '';
		if ( !empty( $opt['data'] ) )
		{
			foreach ( $opt['data'] as $ak => $av )
			{
				$attrs .= " data-$ak='$this->plugin_id" . "_" . esc_attr( $av ) . "' ";
        $ps["data-$ak"] = $this->plugin_id . "_" . esc_attr( $av );

				if ( $ak == 'req-on' )
				{
					$opt_id = $av;
					if ( $this->opt( $opt_id ) )
					{
						$attrs .= " required ";
						$ps['required'] = true;
					}
				}
			}
		}

		if ( 'how_to' == $opt['type'] )
		{
			echo $opt['how_to'];
		}
		else if ( 'checkbox' == $opt['type'] )
		{
			$ps['type'] = 'checkbox';
			$ps['name'] = $k2;
			$ps['value'] = 1;
			$ps['checked'] = checked( 1, $v, false );

			echo $this->html_input($ps);
			if ( !empty( $opt['comment'] ) )
			{
				echo "<br><i>$opt[comment]</i>";
			}
		}
		else if ( 'textarea' == $opt['type'] )
		{
			if ( !$v || !( $e_v = esc_attr( $v ) ) )
			{
				$e_v = '';
			}

			echo "<textarea $attrs style = 'width: 100%; height: 6em; margin-right: 20px;' name='$k2' >$e_v</textarea>";

			if ( !empty( $opt['comment'] ) )
			{
				echo "<i>$opt[comment]</i>";
			}
		}
		else if ( stristr($opt['type'], 'header') )
		{
				//echo "<i>$opt[name]</i>";
		}
		else
		{
			$ps['type'] = $opt['type'];
			$ps['name'] = $k2;
			$ps['value'] = $v;
			if ( !empty( $opt['placeholder'] ) )
			{
				$ps['placeholder'] = $opt['placeholder'];
			}
			if ( !empty( $opt['required'] ) )
			{
				$ps['required'] = $opt['required'];
			}
      echo $this->html_input( $ps );

			if ( !empty( $opt['comment'] ) )
			{
				echo "<br><i>$opt[comment]</i>";
			}
		}
	}



	public function settings_section( $arg )
	{


	}
}