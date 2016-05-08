<?php

include dirname( dirname( __FILE__ ) ) . '/code/_safe.php';

// Controller-model for settings

class Popup4Phone_Settings extends Popup4Phone_Settings_Base
{
	public function filter_plugin_action_links_xbasenamex($links)
	{
		$url           = $this->url_settings();
		$settings_link = "<a href='$url'>Settings</a>";
		array_unshift( $links, $settings_link );
		return $links;
	}

	public function action_admin_enqueue_scripts()
	{
		$v  = $this->plugin_version;
		$id = $this->plugin_id;

		wp_enqueue_style( $id . '-admin-settings', $this->url_css( 'popup4phone-admin.css' ), array(), $v );
	}

	public function cfg_menus()
	{
		return array(
			 array(
				'name' => __( 'Settings', 'popup4phone' ),
				'slug' => $this->page_slug(),
				'callback' => 'page_settings',
				'capability' => $this->plugin_id_.'_manage_options',
			)
		);
	}

	public function settings_section( $args )
	{
		$id  = $args['id'];
		$mos = $this->opts_meta();
		if ( !empty( $mos[ $id ]['comment'] ) )
		{
			echo "<p><i>" . $mos[ $id ]['comment'] . "</i></p>";
		}
	}

	public function page_settings()
	{
		$p_id = $this->page_id();
		$opts = $this->opts_meta();
		include $this->tpl_path( 'templates/admin/settings.tpl.php' );
	}

	public function tab()
	{
		$opts = $this->opts_meta();
		reset( $opts );
		$tab = isset( $_REQUEST['tab'] ) ? $_REQUEST['tab'] : key( $opts );
		return $tab;
	}

	public function action_admin_init()
	{
		$sp_id = $this->page_id();
		$opts  = $this->opts_meta();
		$tab   = $this->tab();

		foreach ( $opts as $section_id => $sd )
		{
			// add only sections with settings and belonging to the selected tab
			if ( empty( $sd['settings'] ) || $tab != $section_id)
			{
				continue;
			}

			add_settings_section( $section_id, $sd['title'], array(
				 &$this,
				"settings_section"
			), $sp_id );

			foreach ( $sd['settings'] as $k => $opt )
			{
				add_settings_field( $opt['k_full'], $opt['name'], array(
					 &$this,
					"settings_field"
				), $sp_id, $section_id, array(
					 'key' => $k
				) );

				register_setting( $sp_id, $opt['k_full'] );
			}
		}
	}

	public function fields_types()
	{
		$fs = array(
			'name' => __('Name', 'popup4phone'),
			'phone' => __('Phone', 'popup4phone'),
			'email' => 'Email',
			'message' => __('Message', 'popup4phone'),
		);
		return $fs;
	}
	public function fields_params()
	{
		$ps = array(
			'' => __('Popup form', 'popup4phone'),
			'_show_inline' => __('Inline form', 'popup4phone'),
			'_required' => __('Required', 'popup4phone'),
			'_label' => __('Label', 'popup4phone'),
			'_placeholder' => __('Placeholder', 'popup4phone'),
		);
		return $ps;
	}


	public function print_settings_fields()
	{
		$fs = $this->fields_types();
		$ps = $this->fields_params();

		$out = '';
		$out .= "<style>";
		$out .= ".popup4phone-fields tr th { text-transform: capitalize; text-align: center; }";
		$out .= "</style>";
		$out .= "<table class='wp-list-table widefat striped popup4phone-fields'>";

		$out .= "<tr> <th>".__('Field', 'popup4phone')."</th>";
		foreach($ps as $pk => $pl)
		{
			$out .= "<th>";
			$out .= $pl;
			$out .= "</th>";
		}
		$out .= "</tr>";


		foreach($fs as $fk => $fl)
		{
			$out .= "<tr>";
			$out .= "<td>";
			$out .= $fl;
			$out .= "</td>";

			foreach($ps as $pk => $pl)
			{
				$out .= "<td style='text-align: center'>";
				$key = 'form_field_'.$fk.$pk;
				ob_start();
				$this->settings_field(array('key' => $key));
				$out .= ob_get_clean();
				$out .= "</td>";
			}
			$out .= "</tr>";
		}
		$out .= "</table>";
		print $out;
	}

	public function opts_meta( $key = '' )
	{
		$id        = $this->plugin_id;

		$rl_page = __( 'Changes in the settings will take effect only after reloading of the page containing the form/button', 'popup4phone' );

		$opts = array(
			'main' => array(
				'title' => __( "Main", 'popup4phone' ),
				'settings' => array()
			),
			'popup' => array(
				'title' => __( "Popup form", 'popup4phone' ),
				'comment' => $rl_page,
				'settings' => array()
			),
			'popover_button' => array(
				'title' => __( "Button", 'popup4phone' ),
				'comment' => $rl_page,
				'settings' => array()
			),
			'form' => array(
				'title' => __( "Form", 'popup4phone' ),
				'comment' => $rl_page,
				'settings' => array()
			),
			'fields' => array(
				'title' => __( "Fields", 'popup4phone' ),
				'comment' => $rl_page,
				'settings' => array()
			),
			'notify' => array(
				'title' => __( "Notifications", 'popup4phone' ),
				'settings' => array()
			),
			'ga-tracking' => array(
				'title' => "Google Analytics / " . __( "Tracking", 'popup4phone' ),
				'settings' => array()
			)
		);

		$opts = apply_filters(Popup4Phone_Filters::SETTINGS_TABS, $opts);

		$k = 'popup';

		$opts[$k]['settings']['auto_popup_enabled'] = array(
			'name' => __( 'Auto popup', 'popup4phone' ),
			'default' => 0,
			'type' => 'checkbox'
		);
		$opts[$k]['settings']['delay'] = array(
			'name' => __( 'Delay for auto popup, seconds', 'popup4phone' ),
			'default' => 5,
			'type' => 'number'
		);
		$opts[$k]['settings']['cookie_popup_shown_remember_time'] = array(
			'name' => __( 'Pause between the repeated shows for the same visitor, days', 'popup4phone' ),
			'default' => 5,
			'type' => 'number'
		);

		$opts[$k]['settings']['title']  = array(
			'name' => __( 'Title', 'popup4phone' ),
			'default' => __( "Can't find something you want?", 'popup4phone' ),
			'type' => 'textarea',
			'comment' =>
			"<div class='popup4phone_list_pointed'>" .
			__( "Examples: ", 'popup4phone' ).
			"<ul> <li>".
			__( "ask if user can't find interesting details", 'popup4phone' ).
			"</li> <li>".
			__( "invite to test", 'popup4phone' ).
			"</li> <li>".
			__( "offer to request a call back", 'popup4phone' ).
			"</li> <li>".
			__( "highlight current promotion", 'popup4phone' ).
			"</li> <li>".
			__( "give special promotion for the form submission", 'popup4phone' ).
 			"</li> </ul>" . "</div>",
		);

		$k = 'fields';

		$fs = $this->fields_types();
		$ps = $this->fields_params();

		foreach($fs as $fk => $fl)
		{
			foreach($ps as $pk => $pl)
			{
				if (in_array($pk, array('', '_show_inline', '_required')))
				{
					$def = 1;
					$type = 'checkbox';
				}
				else
				{
					$def = '';//$fl;
					$type = 'text';
					if ('_label' == $pk)
						$def = $fl;
				}

				$opts[$k]['settings']['form_field_'.$fk.$pk] = array(
					'name' => "$fl: $pl",
					'default' => $def,
					'type' => $type,
				);

			}
		}

		$k = 'form';
		$opts[$k]['settings']['form_submit_label'] = array(
			'name' => __( 'Label for submit button', 'popup4phone' ),
			'default' => 'Submit',
			'type' => 'text'
		);
		$opts[$k]['settings']['form_message_sending']   = array(
			'name' => __( 'Message for show during form submission', 'popup4phone' ),
			'default' => __( 'Form is being sent', 'popup4phone' ),
			'type' => 'textarea',
		);
		$opts[$k]['settings']['form_message_thank_you']   = array(
			'name' => __( 'Message for show after the form submission', 'popup4phone' ),
			'default' => __( 'Thanks for contacting us! We will get in touch with you shortly', 'popup4phone' ),
			'type' => 'textarea',
		);


		$opts[$k]['settings']['show_copyright']                   = array(
			'name' => __( 'Show copyright link', 'popup4phone' ),
			'default' => 0,
			'type' => 'checkbox',
		);


		$k = 'popover_button';

		$opts[$k]['settings']['popup_button_enabled'] = array(
			'name' => __( 'Popover button enabled', 'popup4phone' ),
			'default' => 0,
			'type' => 'checkbox'
		);

		$opts[$k]['settings']['popup_button_caption_enabled'] = array(
			'name' => __( 'Use label instead icon', 'popup4phone' ),
			'default' => 0,
			'type' => 'checkbox'
		);

		$opts[$k]['settings']['popup_button_caption'] = array(
			'name' => __( 'Label', 'popup4phone' ),
			'default' => '',
			'type' => 'text'
		);

		$opts[$k]['settings']['popup_button_caption_font_size'] = array(
			'name' => __( 'Font size', 'popup4phone' ),
			'comment' => __( 'Pixels', 'popup4phone'),
			'default' => 15,
			'type' => 'number'
		);

		$opts[$k]['settings']['popup_button_animation_bounce'] = array(
			'name' => __( 'Animate (bounce)', 'popup4phone' ),
			'default' => 1,
			'type' => 'checkbox'
		);

		$opts[$k]['settings']['popup_button_offset_bottom'] = array(
			'name' => __( 'Bottom offset', 'popup4phone' ),
			'default' => 20,
			'type' => 'number',
			'comment' => __( 'Pixels', 'popup4phone'),
		);
		$opts[$k]['settings']['popup_button_offset_right'] = array(
			'name' => __( 'Right offset', 'popup4phone' ),
			'default' => 20,
			'type' => 'number',
			'comment' => __( 'Pixels', 'popup4phone'),
		);
		$opts[$k]['settings']['popover_button_width'] = array(
			'name' => __( 'Width', 'popup4phone' ),
			'default' => 100,
			'type' => 'number',
			'comment' => __( 'Pixels', 'popup4phone'),
		);
		$opts[$k]['settings']['popover_button_height'] = array(
			'name' => __( 'Height', 'popup4phone' ),
			'default' => 100,
			'type' => 'number',
			'comment' => __( 'Pixels', 'popup4phone'),
		);
		$c_c_f = __( 'Hexadecimal value, for example, %s', 'popup4phone' );

		$c_c_h = sprintf($c_c_f, '#FFFFFF');
		$opts[$k]['settings']['popover_button_phone_handset_color'] = array(
			'name' => __( 'Label / phone handset color', 'popup4phone' ),
			'default' => '#FFFFFF',
			'type' => 'color',
			'comment' => $c_c_h,
		);

		$c_c_b = sprintf($c_c_f, '#4169E1');
		$opts[$k]['settings']['popover_button_background_color'] = array(
			'name' => __( 'Background color', 'popup4phone' ),
			'default' => '#4169E1',
			'type' => 'color',
			'comment' => $c_c_b,
		);

		$k = 'notify';
		$opts[$k]['settings']['notify_email'] = array(
			'name' => __( 'Send notify about new lead to email', 'popup4phone' ),
			'default' => '',
			'type' => 'text',
			'comment' => __( 'Multiple comma-separated emails can be specified', 'popup4phone' ),
		);
		$subj = "Popup4Phone: ".__("new request on web-site (*|SITE|*)", 'popup4phone' );
		$opts[$k]['settings']['notify_email_subject'] = array(
			'name' => __( 'Subject of email', 'popup4phone' ),
			'default' => $subj,
			'type' => 'text',
			//'custom_attributes' => array(
				//'style' =>
				//),
		);
		$opts[$k]['settings']['notify_email_body'] = array(
			'name' => __( 'Body of email', 'popup4phone' ),
			'default' => $subj."<br><br>*|FIELDS|*",
			'type' => 'textarea'
		);


		$k = 'ga-tracking';
		$opts[$k]['settings']['ga_send_events']             = array(
			 'name' => __( 'Send events', 'popup4phone' ),
			'default' => 0,
			'type' => 'checkbox'
		);
		$opts[$k]['settings']['ga_category']            = array(
			 'name' => __( 'Events *category*', 'popup4phone' ),
			'default' => 'popup4phone',
			'type' => 'text'
		);
		$opts[$k]['settings']['ga_action_open_header']         = array(
			'name' => __( 'Events on popup form opening', 'popup4phone' ),
			'type' => 'header'
		);
		$opts[$k]['settings']['ga_action_open']         = array(
			 'name' => __( 'Action', 'popup4phone' ),
			'default' => 'open',
			'type' => 'text'
		);
		$opts[$k]['settings']['ga_label_open_auto']     = array(
			 'name' => __( 'Label on *auto* opening', 'popup4phone' ),
			'default' => 'auto',
			'type' => 'text'
		);
		$opts[$k]['settings']['ga_label_open_click']    = array(
			 'name' => __( 'Label on opening *by click*', 'popup4phone' ),
			'default' => 'click',
			'type' => 'text'
		);
		$opts[$k]['settings']['ga_action_submit_header']       = array(
			'name' => __( 'Events on submit', 'popup4phone' ),
			'type' => 'header'
		);
		$opts[$k]['settings']['ga_action_submit']       = array(
			 'name' => __( 'Action', 'popup4phone' ),
			'default' => 'submit',
			'type' => 'text'
		);
		$opts[$k]['settings']['ga_label_submit_inline'] = array(
			 'name' => __( 'Label for *inline* form', 'popup4phone' ),
			'default' => 'inline',
			'type' => 'text'
		);
		$opts[$k]['settings']['ga_label_submit_popup']  = array(
			 'name' => __( 'Label for *popup* form', 'popup4phone' ),
			'default' => 'popup',
			'type' => 'text'
		);

		$dn = __( "Isn't needed if you had already inserted Google Analytics code", 'popup4phone' );

		$opts[$k]['settings']['ga_code_header']       = array(
			'name' => __( 'Google Analytics code', 'popup4phone' ),
			'type' => 'header'
		);
		$opts[$k]['settings']['ga_add_code'] = array(
			 'name' => __( 'Add code', 'popup4phone' ),
			'default' => 0,
			'type' => 'checkbox',
			'comment' => $dn,
		);
		$doc_url = 'http://popup4phone.com/docs-where-to-get-google-analytics-id/';

		$opts[$k]['settings']['ga_id'] = array(
			'name' => 'GA ID',
			'default' => '',
			'type' => 'text',
			'placeholder' => 'UA-XXXXXXX-X',
			'comment' => sprintf( __( "Looks like: %s (where to get it: %s).", 'popup4phone' ), "UA-XXXXXXX-X", "<a target = '_blank' href='$doc_url'>$doc_url</a>" )." ".$dn,
		);

		$opts[$k]['settings']['tracking_header']   = array(
    	'name' => __( "Tracking", 'popup4phone' ),
			'type' => 'header',
		);
		$opts[$k]['settings']['js_run_on_submit']   = array(
			'name' => __( 'JavaScript code for execution after the form submission', 'popup4phone' ),
			'default' => "//console.log('Form submitted!');",
			'type' => 'textarea',
			'comment' => __( "To integrate with other analytic / conversion tracking systems, etc.", 'popup4phone' ),
		);

		$opts[$k]['settings']['on_submit_tag']   = array(
			'name' => __( 'Tag on submit', 'popup4phone' ),
			'default' => "<!-- <img src='http://a.com/tracking-pixel.gif' /> -->",
			'type' => 'textarea',
			'comment' => __( "For example, Google Conversion Tag",
							'popup4phone' ),
		);


		$opts = apply_filters(Popup4Phone_Filters::SETTINGS_FIELDS, $opts);

		foreach ( $opts as $section_id => $sd )
		{
			if (empty($sd['settings']) || !is_array($sd['settings']))
			{
				unset( $opts[$section_id] );
				continue;
			}

			foreach ( $sd['settings'] as $k => $opt )
			{
				$opts[ $section_id ]['settings'][ $k ]['k_full'] = $this->plugin_id . "_" . $k;
				if ( $key == $k )
				{
					return $opts[ $section_id ]['settings'][ $k ];
				}
			}
		}

		// not found
		if ( $key )
			return null;

		return $opts;
	}

}