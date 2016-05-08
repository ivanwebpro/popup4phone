<?php
include 'code/_safe.php';

// Top-level main class for plugin

class Popup4Phone_Main extends Popup4Phone_Main_Base
{
	public $settings;

	public function plugin_dir()
	{
		return dirname( dirname( __FILE__ ) );
	}

	public function action_plugins_loaded()
	{
		$id = $this->plugin_id;
 		load_plugin_textdomain( $id, false, $id.'/lang/' );
	}

	public function page_blank()
	{

	}

	public function page_integrations()
	{
    include $this->tpl_path( 'templates/admin/integrations.tpl.php' );
	}

	public function components()
	{
		static $res = array();
    if ( !empty( $res ) )
		{
			return $res;
		}

		$cls = array(
			'Popup4Phone_Leads',
			'Popup4Phone_Settings',
			'Popup4Phone_Form',
			'Popup4Phone_Help',
		);
		foreach( $cls as $cl )
		{
			$res[] = new $cl;
		}

		return $res;
	}

	public function action_admin_menu()
	{
		$title = "Popup4Phone";
		$menu = 'Popup4Phone';
		$cap = 'popup4phone_edit_leads';
		if (current_user_can('manage_options'))
    	$cap = 'manage_options';

   	$id = $this->plugin_id;

		$menu = apply_filters("popup4phone_menu_name", $menu);
		/*$this->hre("P: ".$this->is_premium_installed());

		if ($this->is_premium_installed())
		{
    	$menu = $this->get_premium_name();
		} */

		add_menu_page( $title, $menu, $cap, $id,
						array(&$this, 'page_blank') );

		$cs = $this->components();
		foreach( $cs as $c )
		{
			$ms = $c->cfg_menus();

			foreach($ms as $m)
			{
				$n = $m['name'];
				if ( $m['slug'] )
				{
					$slug = $id . '-' . $m['slug'];
				}
				else
				{
					$slug = $id;
				}

				//$this->hr($m);

				$cap_x = $cap;
				if (!empty($m['capability']))
					$cap_x = $m['capability'];
				if (current_user_can('manage_options'))
    			$cap_x = 'manage_options';

				add_submenu_page( $id, 'Popup4Phone '.$n, $n, $cap_x, $slug,
						array($c, $m['callback']) );
			}
		}

		$n_m = __( "Integrations", 'popup4phone' );
		$n_t = "Popup4Phone: ".$n_m;
		$slug = 'integrations';
		add_submenu_page( $id, $n_t, $n_m, $cap, $id . '-' . $slug,
						array(&$this, 'page_integrations') );

		/*$n_m = __( "Help", 'popup4phone' );
		$n_t = "Popup4Phone: ".$n_m;
		$slug = 'help';
		add_submenu_page( $id, $n_t, $n_m, $cap, $id . '-' . $slug,
						array(&$this, 'page_help') );*/
	}


	public function admin_notices()
	{
		$n = new Popup4Phone_Notices_Admin;
		print $n->out();
	}
}