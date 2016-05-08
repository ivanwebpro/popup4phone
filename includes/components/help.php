<?php

// Class for show notices in the admin area

include dirname(dirname(__FILE__)).'/code/_safe.php';

class Popup4Phone_Help extends Popup4Phone_Component
{
	public function cfg_menus()
	{
		return array(
			 array(
				'name' => $this->page_name(),
				'slug' => $this->page_slug(),
				'callback' => 'page_render',
			)
		);
	}

	public function page_render()
	{
		$u = wp_get_current_user();
		$admin_email = $u->user_email;
		$admin_name = '';//$u->user_nicename;
		$form_url = "http://" . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
		include $this->tpl_path( 'templates/admin/help.tpl.php' );
	}

	public function filter_plugin_action_links_xbasenamex($links)
	{
		$url = $this->page_url();
		$a   = "<a href='$url'>Help</a>";
		array_unshift( $links, $a );
		return $links;
	}

	public function page_name()
	{
		return __( 'Help', 'popup4phone' );
	}

	public function page_slug()
	{
		return 'help';
	}

	public function page_url()
	{
		return "admin.php?page=" . $this->page_id();
	}

	public function page_id()
	{
		return $this->plugin_id . '-' . $this->page_slug();
	}
}
