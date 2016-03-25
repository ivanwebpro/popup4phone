<?php
  $s = $this->settings;
?>

<div class="area area-loading" style='display: none'>
	<div class="progress">
		<div class="progress-bar progress-bar-striped active" role="progressbar" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100" style="width: 100%">
		</div>
	</div>
	<p style='text-align: center;'><?php echo $s->opt('form_message_sending'); ?> &hellip;</p>
</div>

<div class="area area-success alert alert-success" style='display: none'>
</div>

<div class="area area-error alert alert-danger" style='display: none'>
	<?php _e( 'Error', 'popup4phone' ); ?>
</div>


<div class="area area-form">
	<form method='post' action=''>
		<input type="hidden" name = 'popup4phone[ws_pages_submit_url]' value = '<?php echo esc_attr($page); ?>'/>
		<input type="hidden" name = 'popup4phone[ws_pages_submit_title]' value = ''/>

		<?php
			if (!empty($ps['show_name']))
			{
				?>
	      <div class='form-group'>
					<label for='popup4phone_name'><?php echo $s->opt('form_field_name_label'); ?></label><br>
					<input id = 'popup4phone_name' type="text" class = 'form-control' name = 'popup4phone[name]' placeholder='<?php echo esc_attr($s->opt('form_field_name_placeholder')); ?>' <?php if ($s->opt('form_field_name_required')) echo "required"; ?> />
				</div>
				<?php
			}
		?>

		<?php
			if (!empty($ps['show_phone']))
			{
				?>
					<div class='form-group'>
						<label for='popup4phone_phone'><?php echo $s->opt('form_field_phone_label'); ?></label>
						<input type="text" id = 'popup4phone_phone' name = 'popup4phone[phone]' placeholder='<?php echo esc_attr($s->opt('form_field_phone_placeholder')); ?>' <?php if ($s->opt('form_field_phone_required')) echo "required"; ?> class='form-control'/>
					</div>
				<?php
			}
		?>

		<?php
			if (!empty($ps['show_email']))
			{
				?>
	      <div class='form-group'>
					<label for='popup4phone_email'><?php echo $s->opt('form_field_email_label'); ?></label><br>
					<input id = 'popup4phone_email' type="email" class = 'form-control' name = 'popup4phone[email]' placeholder='<?php echo esc_attr($s->opt('form_field_email_placeholder')); ?>' <?php if ($s->opt('form_field_email_required')) echo "required"; ?>/>
				</div>
				<?php
			}
		?>

		<?php
			if (!empty( $ps['show_message']))
			{
				?>
	      <div class='form-group'>
					<label for='popup4phone_message'><?php echo $s->opt('form_field_message_label'); ?></label><br>
					<textarea id = 'popup4phone_message' class = 'form-control' name = 'popup4phone[message]' placeholder='<?php echo esc_attr($s->opt('form_field_message_placeholder')); ?>' rows='3'<?php if ($s->opt('form_field_message_required')) echo "required"; ?> style='height: 6em'></textarea>
				</div>
				<?php
			}
		?>

		<?php
      do_action(Popup4Phone_Actions::FORM_GENERAL_FIELDS_AFTER);
		?>

		<div class='form-group'>
			<input type="submit" class='btn btn-lg btn-primary run_submit' value = '<?php echo $s->opt('form_submit_label'); ?>'/>
		</div>
	</form>
	</div>

	<?php
	if ( $this->opt( 'show_copyright' ) )
	{
	?>
	<p style='text-align: right; margin: 0px 10px 0px 0px; line-height: 15px;'>
	<a style='color: midnightblue;' href="http://popup4phone.com/?utm_medium=copyright" target='_blank'>&copy; Popup4Phone</a>
	</p>
	<?php
	}
	?>
	<?php

