<div class='popup4phone'>
	<div class='popup4phone-form-inline' <?php echo $ps_attrs; ?>>
		<div class='areas'>
			<?php
				$ps = array();
				$ps['show_email'] = $this->opt('form_field_email_show_inline');
				$ps['show_message'] = $this->opt('form_field_message_show_inline');
				$ps['show_name'] = $this->opt('form_field_name_show_inline');
				$ps['show_phone'] = $this->opt('form_field_phone_show_inline');
				echo $this->html_form_general($ps);
			?>
		</div>
	</div>
</div>