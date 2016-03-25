
<div class="popup4phone">
	<div class="popup4phone-fade"></div>

	<div class="popup4phone-form-popup">
		<div class="close">&times;</div>
		<div class="top"><div class='popup4phone-form-popup-title'><?php echo $title;?></div></div>
		<div class="areas"></div>
	</div>

	<div class="popup4phone-areas-init" style='display:none'>
		<?php
			$ps = array();
			$ps['show_email'] = $this->opt('form_field_email');
			$ps['show_message'] = $this->opt('form_field_message');
			$ps['show_name'] = $this->opt('form_field_name');
			$ps['show_phone'] = $this->opt('form_field_phone');
			print $this->html_form_general($ps);
		?>
	</div>

</div>
