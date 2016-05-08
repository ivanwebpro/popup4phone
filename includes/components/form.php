<?php
include dirname( dirname( __FILE__ ) ) . '/code/_safe.php';

// Controller for popup / inline forms

class Popup4Phone_Form extends Popup4Phone_Component
{
	public $settings;
	public $cookie_popup_shown_param = 'POPUP4PHONE_SHOWN';

	public function hook()
	{
		$id = $this->plugin_id;
		add_shortcode( $id . '_inline_form', array(
			 &$this,
			'shortcode_inline_form'
		) );
		add_shortcode( $id . '_inline_form_no_popup', array(
			 &$this,
			'shortcode_inline_form_no_popup'
		) );

		add_shortcode( $id . '_button_inline', array(
			 &$this,
			'shortcode_button_inline'
		) );
	}

	public function action_wp_enqueue_scripts()
	{
		$v  = $this->plugin_version;
		$id = $this->plugin_id;

		$script_id  = $id . '-popup';
		$d          = $this->settings->values();
		$d['state'] = array( 'popup_show' => true );

		$p = $this->cookie_popup_shown_param;
		if ( isset( $_COOKIE[ $p ] ) && $_COOKIE[ $p ] )
		{
			$d['state']['popup_show'] = false;
		}

		$d = apply_filters(Popup4Phone_Filters::SETTINGS_JS_BEFORE_PUBLISH, $d);

		wp_enqueue_script( $script_id, $this->url_js( 'popup4phone.js' ), array(
			'jquery',
			'jquery-effects-core'
		), $v );
		wp_localize_script( $script_id, $id . '_settings', $d );

		wp_enqueue_style( $id . '-popup', $this->url_css( 'popup4phone.css' ), array(), $v );

		$bs  = '/vendor/bootstrap-partial/';
		$bsp = $this->url_plugin( $bs . 'bootstrap-partial.css' );
		wp_enqueue_style( $id . '-popup_bootstrap-partial', $bsp, array(), $v );
		$bstp = $this->url_plugin( $bs . 'bootstrap-theme-partial.css' );
		wp_enqueue_style( $id . '-popup_bootstrap-theme-partial', $bstp, array(), $v );

	}

	public function html_form_general( $ps = array() )
	{
		$page = $_SERVER['REQUEST_URI'];
		ob_start();
		include $this->tpl_path( 'templates/_form-general.tpl.php' );
		return ob_get_clean();
	}

	public function action_wp_footer()
	{
		do_action(Popup4Phone_Actions::FOOTER_BEFORE);

		$title = $this->settings->opt( 'title' );
		include $this->tpl_path( 'templates/form-popup.tpl.php' );

		if ( $this->opt( 'popup_button_enabled' ) )
		{
			include $this->tpl_path( 'templates/button-popup.tpl.php' );
		}

		$ga_id = $this->opt( 'ga_id' );
		if ( $this->opt( 'ga_add_code' ) && $ga_id )
		{
			include $this->tpl_path( 'templates/ga.tpl.php' );
		}

		do_action(Popup4Phone_Actions::FOOTER_AFTER);
	}

	public function action_init()
	{
		$id = $this->plugin_id;

		if ( !empty( $_POST[ $id . "-shown" ] ) )
		{
			$cookie_name  = $this->cookie_popup_shown_param;
			$cookie_value = true;
			$expire       = time() + $this->settings->opt( 'cookie_popup_shown_remember_time' ) * 24 * 3600;
			$path         = '/';
			do_action(Popup4Phone_Actions::POPUP_SHOWN_AUTO);
			setcookie( $cookie_name, $cookie_value, $expire, $path );
			exit;
		}

		if ( !empty( $_POST[ $id ] ) )
		{
			$pls = new Popup4Phone_Leads();

			$sr = apply_filters(Popup4Phone_Filters::FORM_POST_REQUEST_VALIDATE, $_POST);
			if (!is_string($sr))
			{
				$sr  = $pls->save_request( $_POST[ $id ] );
				$success = true;
			}
			else
			{
				$success = false;
			}

			if ( !empty( $_REQUEST['ajax'] ) || ( !empty( $_SERVER['HTTP_X_REQUESTED_WITH'] ) && strtolower( $_SERVER['HTTP_X_REQUESTED_WITH'] ) == 'xmlhttprequest' ) )
			{
				$res             = array();
				$res['success']  = $success;
				$res['response'] = $sr;
				print json_encode( $res );
			}
			else
				print $sr;

			exit;
		}
	}

	public function shortcode_button_inline( $attrs )
	{
		$inline = true;
		include $this->tpl_path( 'templates/button-popup.tpl.php' );
	}

	public function shortcode_inline_form_no_popup( $atts )
	{
		$atts['no_popup'] = true;
		return $this->shortcode_inline_form( $atts );
	}

	public function shortcode_inline_form( $atts )
	{
		$ps_attrs = '';
		if ( !empty( $atts['no_popup'] ) )
		{
			$ps_attrs = " data-no-popup = '1' ";
		}

		$id = $this->plugin_id;
		ob_start();
		include $this->tpl_path( 'templates/form-inline.tpl.php' );
		return ob_get_clean();
	}
}