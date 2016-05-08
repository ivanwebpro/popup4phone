<?php

include dirname( dirname( __FILE__ ) ) . '/code/_safe.php';

// Controller-model for leads

class Popup4Phone_Leads extends Popup4Phone_Component
{
	public $tbl = 'popup4phone_leads';

	public function __construct()
	{
		parent::__construct();
		$this->track();
	}

	public function init()
	{
		// don't track admin pages
		if (!is_admin() && !session_id() )
		{
			session_start();
		}
	}

	public function cfg_menus()
	{
		return array(
			 array(
				'name' => __( 'Leads', 'popup4phone' ),
				'slug' => '',
				'callback' => 'page_leads'
			)
		);
	}

	public function page_leads()
	{
		$title   = 'Popup4Phone: '.__( 'Leads', 'popup4phone' );
		$table   = $this->table();
		$url_csv = $this->url_add_params( array(
			 $this->plugin_id . "_export_csv" => 1
		) );
		include $this->tpl_path( 'templates/admin/tbl.tpl.php' );
	}

	public function wp_mail_content_type_html( $content_type )
	{
		return 'text/html';
	}

	public function save_request( $r )
	{
		global $wpdb;
		$tbl        = $wpdb->prefix . $this->tbl;
		$r['ws_ip'] = $this->getIp();
		$r['time']  = current_time( 'mysql' );

		if ( !empty( $_SESSION[ $this->plugin_id ]['web_stat'] ) )
		{
			$ws = $_SESSION[ $this->plugin_id ]['web_stat'];
			foreach ( $ws as $k => $v )
				$r['ws_' . $k] = is_scalar( $v ) ? $v : json_encode( $v );
		}

		$r = apply_filters(Popup4Phone_Filters::FORM_SUBMIT_BEFORE_SAVE, $r);
		$wpdb->insert( $tbl, $r );

		$r_id = $wpdb->insert_id;

		// send notify
		$to = $this->opt( 'notify_email' );
		if ( $to )
		{
			$subject = str_ireplace('*|SITE|*', $_SERVER['HTTP_HOST'], $this->opt('notify_email_subject'));
			$body = str_ireplace('*|SITE|*', $_SERVER['HTTP_HOST'], $this->opt('notify_email_body'));

			$f_mct = array(&$this, 'wp_mail_content_type_html');
			add_filter( 'wp_mail_content_type', $f_mct );

			$fields = '';

			$fls = array();
			$fls['name'] = $this->field_fix($this->opt('form_field_name_label'));
			$fls['phone'] = $this->field_fix($this->opt('form_field_phone_label'));
			$fls['email'] = $this->field_fix($this->opt('form_field_email_label'));
			$fls['message'] = $this->field_fix($this->opt('form_field_message_label'));


			if ( isset($r['name']))
			{
				$fields .= $fls['name'] . ": " . $r['name'] . "<br>";
			}

			if ( isset( $r['phone'] ) )
			{
				$fields .= $fls['phone']. ": " . $r['phone'] . "<br>";
			}

			if ( isset( $r['email'] ) )
			{
				$fields .= $fls['email']. ": " . $r['email'] . "<br>";
			}

			if ( isset( $r['message'] ) )
			{
				$fields .= $fls['message'] . ": " . $r['message'] . "<br>";
			}

			$body = str_ireplace('*|FIELDS|*', $fields, $body);
			$headers = array('Content-Type: text/html; charset=UTF-8');

			if (strstr($to, ","))
			{
      	$emails = explode(",", $to);
				$emails = array_map('trim', $emails);
			}
			else
				$emails = array($to);

			$body .= apply_filters(
									Popup4Phone_Filters::EMAIL_NEW_LEAD_FOOTER, '');
			$body = apply_filters(
									Popup4Phone_Filters::EMAIL_NEW_LEAD_BEFORE_SEND, $body);

			foreach($emails as $to)
			{
				wp_mail( $to, $subject, $body, $headers );
			}
			
			remove_filter( 'wp_mail_content_type', $f_mct );
		}

		apply_filters(Popup4Phone_Filters::FORM_SUBMIT_AFTER_SAVE, $r);
		$sr = $this->opt('form_message_thank_you');
		return $sr;
	}

	function page_csv()
	{
		$leads = $this->table()->items_csv();
		$this->download_send_headers( $this->export_file_name( ".csv" ) );
		echo $this->array_to_csv( $leads, $use_keys_as_headers = false );
	}

	public function lead_get( $id )
	{
		global $wpdb;
		$tbl  = $wpdb->prefix . $this->tbl;
		$sql  = "SELECT * FROM `$tbl` WHERE id = %d";
		$p    = $wpdb->prepare( $sql, $id );
		$lead = $wpdb->get_row( $p, ARRAY_A );
		return $lead;
	}

	public function field_fix($f)
	{
		if (substr($f, -1) == ':')
			return substr($f, 0, strlen($f)-1);
		else
			return $f;
	}

	public function web_stat_fields()
	{
		$fs                          = array();
		$fs['ws_pages_submit_url']   = 'URL';
		$fs['ws_pages_submit_title'] = __( 'Title of the page', 'popup4phone' );
		$fs['ws_pages_referrer']     = __( 'Referer', 'popup4phone' );
		$fs['ws_pages_start']        = __( 'Landing page', 'popup4phone' );
		$fs['ws_pages_path']         = __( 'Visited pages', 'popup4phone' );
		$fs['ws_time_start']         = __( 'Time of beginning of the session', 'popup4phone' );
		$fs['ws_IP']                 = 'IP';
		$fs['ws_user_agent']         = __( 'Browser', 'popup4phone' );

		$fs['name'] = $this->field_fix($this->opt('form_field_name_label'));
		$fs['phone'] = $this->field_fix($this->opt('form_field_phone_label'));
		$fs['email'] = $this->field_fix($this->opt('form_field_email_label'));
		$fs['message'] = $this->field_fix($this->opt('form_field_message_label'));

		return $fs;
	}

	function page_web_stat( $id )
	{
		$lead = $this->lead_get( $id );

		$flds = $this->table()->get_columns();
		unset( $flds['cb'] );
		unset( $flds['web_stat'] );
		$flds = array_merge( $flds, $this->web_stat_fields() );

		$ws_flds = array();
		foreach ( $flds as $k => $f )
		{
			$ws_fld          = array();
			$ws_fld['label'] = $f;
			$ws_fld['value'] = $lead[ $k ];
			$ws_flds[ $k ]     = $ws_fld;
		}

		$title = __( "Web stat", 'popup4phone' );
		include $this->tpl_path( 'templates/admin/web-stat.tpl.php' );
	}

	function export_file_name( $ext )
	{
		return date( "Y-m-d" ) . "_" . $this->plugin_id . "_leads." . $ext;
	}

	function download_send_headers( $filename )
	{
		// disable caching
		$now = gmdate( "D, d M Y H:i:s" );
		header( "Expires: Tue, 03 Jul 2001 06:00:00 GMT" );
		header( "Cache-Control: max-age=0, no-cache, must-revalidate, proxy-revalidate" );
		header( "Last-Modified: {$now} GMT" );

		// force download
		header( "Content-Type: application/force-download" );
		header( "Content-Type: application/octet-stream" );
		header( "Content-Type: application/download" );

		// disposition / encoding on response body
		header( "Content-Disposition: attachment;filename={$filename}" );
		header( "Content-Transfer-Encoding: binary" );
	}

	public function track()
	{
		// don't track admin pages
		if (is_admin())
		{
    	return;
		}

		$id = $this->plugin_id;
		$s  = array();
		$ws = array();

		if ( isset( $_SESSION[ $id ] ) )
		{
			$s = $_SESSION[ $id ];
			if ( isset( $s['web_stat'] ) )
			{
				$ws = $s['web_stat'];
			}
		}

		if ( !isset( $ws[$k = 'time_start'] ) )
		{
			$ws[ $k ] = date( 'c' );
		}

		if ( !isset( $ws[ $k = 'user_agent' ] ) && isset($_SERVER['HTTP_USER_AGENT']))
		{
			$ws[ $k ] = $_SERVER['HTTP_USER_AGENT'];
		}

		if ( !isset( $ws[ $k = 'pages_referrer' ] ) )
		{
			if ( !empty( $_SERVER['HTTP_REFERER'] ) )
			{
				$ref = $_SERVER['HTTP_REFERER'];
				$h   = $_SERVER['HTTP_HOST'];
				if ( stristr( $ref, "//" . $h ) )
				{
					$ref = '';
				}
				$ws[ $k ] = $ref;
			}
		}

		if ( !isset( $ws[ $k = 'pages_start' ] ) )
		{
			$ws[ $k ] = $_SERVER['REQUEST_URI'];
		}

		if ( !isset( $ws[ $k = 'pages_path' ] ) )
		{
			$ws[ $k ] = array();
		}

		$url = $_SERVER['REQUEST_URI'];
		if (!strstr($url, 'wp-admin/admin-ajax.php'))
		{
			$ws[ $k ][] = array(
				'url' => $url,
				'time' => date( 'c' )
			);
		}

		$s['web_stat'] = $ws;
		$_SESSION[ $id ] = $s;
	}

	public function table()
	{
		$t            = new Popup4Phone_Leads_Table();
		$t->owner     = $this;
		$t->tbl       = $this->tbl;
		$t->plugin_id = $this->plugin_id;
		return $t;
	}

	public function action_admin_init()
	{
		if (!isset($_GET['page']) || !stristr($_GET['page'], $this->plugin_id))
			return;

		if (!current_user_can( 'manage_options' )  || !is_admin())
		{
			return;
		}

		$id     = $this->plugin_id;
		$ecsv_p = $id . "_export_csv";
		if ( !empty( $_GET[ $ecsv_p ] ) )
		{
			$this->page_csv();
			exit;
		}

		$ws_p = $id . "_web_stat";
		if ( !empty( $_GET[ $ws_p ] ) )
		{
			$ws_id = $_GET[ $id . '_web_stat_id' ];
			$this->page_web_stat( $ws_id );
			exit;
		}

		$this->table()->process_actions();

	}

	public function install()
	{
		global $wpdb;
		$tbl             = $wpdb->prefix . $this->tbl;
		$charset_collate = $wpdb->get_charset_collate();

		$sql = "CREATE TABLE $tbl (
     		id INT(9) NOT NULL AUTO_INCREMENT,
     		form_type_id INT(9) NULL,
				time DATETIME NOT NULL,
        name VARCHAR(255) NULL,
 	      phone VARCHAR(255) NULL,
   	    email VARCHAR(255) NULL,
        message TEXT NULL,
				processed TINYINT(1) DEFAULT 0 NOT NULL,
				deleted TINYINT(1) DEFAULT 0 NOT NULL,
				hidden TINYINT(1) DEFAULT 0 NOT NULL,
   	    admin_notes TEXT NULL,
        ws_pages_submit_url TEXT NULL,
        ws_pages_submit_title TEXT NULL,
				ws_pages_referrer TEXT NULL,
				ws_pages_start TEXT NULL,
				ws_pages_path TEXT NULL,
				ws_time_start DATETIME NULL,
        ws_IP VARCHAR(255) NULL,
				ws_user_agent TEXT NULL,
        email_response TEXT NULL,
				meta_data LONGTEXT NOT NULL,
       	UNIQUE KEY id (id)
   		) $charset_collate;";

		require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
		dbDelta( $sql );
	}

}
