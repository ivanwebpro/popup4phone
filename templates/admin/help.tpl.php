<div class="wrap popup4phone_list_pointed">
	<?php include 'admin_header.php'; ?>
	<h2><?php echo "Popup4Phone: ".__( 'Help', 'popup4phone' );?></h2>


	<div style='float: right; width: 50%; border: 1px solid green; padding: 5px;'>
	<p><b><?php printf( __( 'If you like Popup4Phone please leave a %s rating.', 'popup4phone' ), $this->html_a('&#9733;&#9733;&#9733;&#9733;&#9733;', "https://wordpress.org/support/view/plugin-reviews/popup4phone?filter=5#postform", array('target' => "_blank", 'style' => "text-decoration:none") ) );
	print "<br>";
	_e("Thank you very much in advance!", 'popup4phone');
	?></b></p>

	<p><b><?php
		$url = "http://popup4phone.com/contact/";

		printf( __( 'If you have any problems - please contact me: %s', 'popup4phone' ), $this->html_a($url, $url, array('target' => "_blank", 'style' => "text-decoration:none")));


	?></b></p>

	<p><b><?php
		$url = "https://translate.wordpress.org/projects/wp-plugins/popup4phone";

		printf( __( 'Help to translate plugin on your language: %s', 'popup4phone' ), $this->html_a($url, $url, array('target' => "_blank", 'style' => "text-decoration:none")));


	?></b></p>

	</div>


	<p><?php _e( 'There are three ways to use Popup4Phone plugin', 'popup4phone' );?>:</p>
	<ul>
		<li><?php _e( 'Auto popup with some delay after loading of the page', 'popup4phone' );?></li>
		<li><?php _e( 'Popover button, where the user can click and open the form', 'popup4phone' );?></li>
		<li><?php _e( 'Shortcode/inline form', 'popup4phone');?></li>
	</ul>

	<p><?php _e( 'Shortcodes', 'popup4phone' );?>:</p>
	<ul>
		<li><b>[popup4phone_inline_form_no_popup]</b> &ndash; <?php _e( 'show inline form and lock auto popup form', 'popup4phone' );?></li>
		<li><b>[popup4phone_inline_form]</b> &ndash; <?php _e( 'show inline form, NOT lock auto popup form', 'popup4phone' );?></li>
	</ul>

	<p><?php _e( 'Also I recommend to use some trigger email service (like Mandrill) for a better deliverability', 'popup4phone' );?>. Mandrill: <A href = 'https://mandrillapp.com' target='_blank'>https://mandrillapp.com</A>, <?php _e( 'plugin', 'popup4phone' )?>: <A href='https://wordpress.org/plugins/wpmandrill/' target='_blank'>https://wordpress.org/plugins/wpmandrill/</A></p>

	<h2><?php _e( 'Subscribe to mailing list and get priority support', 'popup4phone' ); ?>:</h2>

<form method="post" action="http://popup4phone.com/subscribe/?plugin_id=popup4phone" target='_blank'>
	<input type="hidden" name="form_url" value="<?php echo $form_url;?>">
	<table class="form-table">
		<tbody>
			<tr>
				<th scope="row">
					<?php _e( 'Name', 'popup4phone' ); ?><span style='color: red'>*</span>:</th>
				<td>
					<input type="text" name="name" value="<?php echo esc_attr($admin_name); ?>" placeholder="" required>
				</td>
			</tr>

			<tr>
				<th scope="row">
					Email<span style='color: red'>*</span>:</th>
				<td>
					<input type="text" name="email" value="<?php echo esc_attr($admin_email); ?>" placeholder="" required>
				</td>
			</tr>

		</tbody>
	</table>

	<p class="submit">
		<input type="submit" name="submit" id="submit" class="button button-primary" value="<?php _e( 'Subscribe', 'popup4phone' ); ?>">
	</p>
</form>
<br>


</div>

