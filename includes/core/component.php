<?php

// Root class for components - object with settings, actions, menus

include dirname(dirname(__FILE__)).'/code/_safe.php';

abstract class Popup4Phone_Component extends Popup4Phone_Root
{
	public $settings;

	public function __construct()
	{
		parent::__construct();
    $this->settings = new Popup4Phone_Settings();
	}

	public function opt( $f )
	{
  	return $this->settings->opt( $f );
	}

	public function cfg_menus()
	{
		return array();
	}
}