<?php

include dirname( dirname( __FILE__ ) ) . '/code/_safe.php';

// Leads table, "view" in admin panel + export for csv

if ( !class_exists( 'WP_Screen' ) )
{
	require_once( ABSPATH . 'wp-admin/includes/screen.php' );
}

require_once( ABSPATH . 'wp-admin/includes/template.php' );
if ( !class_exists( 'WP_List_Table' ) )
{
	require_once( ABSPATH . 'wp-admin/includes/class-wp-list-table.php' );
}


class Popup4Phone_Leads_Table extends WP_List_Table
{
	public $owner;
	public $plugin_id;
	public $tbl;
	public $db;
	public $nonce_id = 'popup4phone_table_nonce';
	public $settings;

	public function root()
	{
  	return $this->settings;
	}

	public function __construct()
	{
		global $wpdb;
		$this->db = $wpdb;
    $this->settings = new Popup4Phone_Settings();

		parent::__construct( array(
			'singular' => __( 'Lead', 'popup4phone' ),
			'plural' => __( 'Leads', 'popup4phone' ),
			'ajax' => false,
			'screen' => 'popup4phone-leads'
		) );
	}

	public function items_csv()
	{
		$items     = $this->items_get( $per_page = false );
		$cols      = $this->get_columns();
		$cols_meta = $this->columns_meta();

		$res   = array();
		$res[] = $cols;
		unset( $res[0]['cb'] );
		unset( $res[0]['web_stat'] );
		$res[0]['id'] = " " . $res[0]['id']; // to avoid SYLK files Excel bug

		foreach ( $items as $row )
		{
			$rx = array();
			foreach ( $cols as $k => $c )
			{
				if ( isset( $cols_meta[ $k ] ) && $cols_meta[ $k ]['csv'] && array_key_exists( $k, $row ) )
				{
					$rx[ $k ] = $row[ $k ];
				}
			}

			$res[] = $rx;
		}

		return $res;
	}

	public function items_get( $per_page = 5, $page_number = 1 )
	{
		$sql = "SELECT * FROM {$this->db->prefix}{$this->tbl}";

		$order_by = 'id';
		if ( !empty( $_REQUEST['orderby'] ) )
			$order_by = $_REQUEST['orderby'];
		$order = 'DESC';
		if ( !empty( $_REQUEST['order'] ) )
			$order = $_REQUEST['order'];


		$sql .= ' ORDER BY ' . esc_sql( $order_by ) . ' ';
		if ( $order )
		{
			$sql .= ' ' . esc_sql( $order );
		}
		else
		{
			$sql .= ' ASC';
		}

		//print $sql; exit;

		if ( $per_page )
		{
			$sql .= " LIMIT $per_page";
			$sql .= ' OFFSET ' . ( $page_number - 1 ) * $per_page;
		}

		$result = $this->db->get_results( $sql, 'ARRAY_A' );
		return $result;
	}

	public function items_delete( $id )
	{
		$this->db->delete( "{$this->db->prefix}{$this->tbl}", array(
			 'id' => $id
		), array(
			 '%d'
		) );
	}

	public function items_count()
	{
		$sql = "SELECT COUNT(*) FROM {$this->db->prefix}{$this->tbl}";
		return $this->db->get_var( $sql );
	}

	public function no_items()
	{
		_e( 'No leads', 'popup4phone' );
	}

	public function column_name( $item )
	{
		// create a nonce
		$delete_nonce = wp_create_nonce( $this->nonce_id );

		$title = '<strong>' . $item['name'] . '</strong>';

		$actions = array(
			 'delete' => sprintf( '<a href="?page=%s&action=%s&id=%s&_wpnonce=%s">Delete</a>', esc_attr( $_REQUEST['page'] ), 'delete', absint( $item['id'] ), $delete_nonce )
		);

		return $title . $this->row_actions( $actions );
	}

	public function column_default( $item, $column_name )
	{
		switch ( $column_name )
		{
			case 'web_stat':
				$id  = $this->plugin_id;
				$url = $this->owner->url_add_params( array(
					$id . '_web_stat' => 1,
					$id . '_web_stat_id' => $item['id']
				) );
				$v = sprintf( "<a href='$url' target='blank'>%s</a>", __( 'Web stat', 'popup4phone' ) );
				return $v;

			default:
				return $item[ $column_name ];
		}
	}

	public function column_cb( $item )
	{
		return sprintf( '<input type="checkbox" name="bulk-delete[]" value="%s" />', $item['id'] );
	}

	public function field_fix($f)
	{
		if (substr($f, -1) == ':')
			return substr($f, 0, strlen($f)-1);
		else
			return $f;
	}

	public function columns_meta()
	{
		$s = $this->settings;
		$cs = array();

		$c                = array();
		$c['id']          = 'id';
		$c['name']        = 'ID';
		$c['default_asc'] = true;
		$cs[$c['id']]     = $c;

		$c            = array();
		$c['id']      = 'phone';
		$c['name']    = $this->field_fix($s->opt('form_field_phone_label'));
		$cs[$c['id']] = $c;

		$c            = array();
		$c['id']      = 'name';
		$c['name']    = $this->field_fix($s->opt('form_field_name_label'));
		$cs[$c['id']] = $c;

		$c            = array();
		$c['id']      = 'email';
		$c['name']    = $this->field_fix($s->opt('form_field_email_label'));
		$cs[$c['id']] = $c;

		$c            = array();
		$c['id']      = 'message';
		$c['name']    = $this->field_fix($s->opt('form_field_message_label'));
		$cs[$c['id']] = $c;

		$c            = array();
		$c['id']      = 'time';
		$c['name']    = __( 'Time', 'popup4phone' );
		$cs[$c['id']] = $c;

		$c            = array();
		$c['id']      = 'ws_pages_submit_url';
		$c['name']    = 'URL';
		$cs[$c['id']] = $c;

		$c            = array();
		$c['id']      = 'ws_pages_submit_title';
		$c['name']    = __( 'Title', 'popup4phone' );
		$cs[$c['id']] = $c;

		$c             = array();
		$c['id']       = 'web_stat';
		$c['name']     = __( 'Web stat', 'popup4phone' );
		$c['sortable'] = false;
		$c['csv']      = false;
		$cs[$c['id']]  = $c;

		foreach ( $cs as &$c )
		{
			if ( !isset( $c['csv'] ) )
			{
				$c['csv'] = true;
			}
			if ( !isset( $c['sortable'] ) )
			{
				$c['sortable'] = true;
			}
		}

		//var_dump($cs); exit;

		return $cs;
	}

	public function get_columns()
	{
		$cs        = $this->columns_meta();
		$res       = array();
		$res['cb'] = '<input type="checkbox" />';

		foreach ( $cs as $k => $c )
		{
			$res[ $k ] = $c['name'];
		}

		return $res;
	}

	public function get_sortable_columns()
	{
		$cs = $this->columns_meta();

		$res       = array();
		$res['cb'] = '<input type="checkbox" />';

		foreach ( $cs as $k => $c )
		{
			if ( $c['sortable'] )
			{
				if ( isset( $c['default_asc'] ) )
					$res[$k] = array(
						$k,
						true
					);
				else
				{
					$res[ $k ] = $k;
				}
			}
		}

		return $res;
	}

	public function get_bulk_actions()
	{
		$actions = array(
			 'bulk-delete' => __( 'Delete', 'popup4phone' )
		);

		return $actions;
	}

	public function prepare_items()
	{
		$columns               = $this->get_columns();
		$hidden                = array();
		$sortable              = $this->get_sortable_columns();
		$this->_column_headers = array(
			 $columns,
			$hidden,
			$sortable
		);

		$per_page     = $this->get_items_per_page( $this->plugin_id . 'per_page_leds', 5 );
		$current_page = $this->get_pagenum();
		$total_items  = $this->items_count();

		$this->set_pagination_args( array(
			'total_items' => $total_items,
			'per_page' => $per_page
		) );

		$this->items = $this->items_get( $per_page, $current_page );
	}

	public function process_actions()
	{
		if ( 'delete' === $this->current_action() )
		{
			$nonce = esc_attr( $_REQUEST['_wpnonce'] );

			if ( !wp_verify_nonce( $nonce, $this->nonce_id ) )
			{
				exit( 'Nonce verification error' );
			}

			$id         = absint( $_GET['id'] );
			$delete_ids = array(
				 $id
			);
		}

		if ( ( isset( $_POST['action'] ) && $_POST['action'] == 'bulk-delete' ) || ( isset( $_POST['action2'] ) && $_POST['action2'] == 'bulk-delete' ) )
		{
			$delete_ids = esc_sql( $_POST['bulk-delete'] );
		}

		if ( !empty( $delete_ids ) )
		{
			foreach ( $delete_ids as $id )
			{
				$this->items_delete( $id );
			}

			$msg_tpl = __( "Lead(s) #%s have been deleted", 'popup4phone' );
			$msg     = sprintf( $msg_tpl, implode( ", ", $delete_ids ) );
			$n       = new Popup4Phone_Notices_Admin();
			$n->add( $msg );

			$current_url = add_query_arg( 'page', $_GET['page'], admin_url( 'admin.php' ) );
			wp_redirect( esc_url( $current_url ) );
			exit;
		}
	}

}