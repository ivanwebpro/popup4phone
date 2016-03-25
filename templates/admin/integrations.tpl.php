<div class="wrap popup4phone_list_pointed">
	<?php include 'admin_header.php'; ?>
	<h2><?php echo 'Popup4Phone: '.__( 'Integrations', 'popup4phone' );?></h2>

	<?php $url = "http://popup4phone.com/integrations/"; ?>
	<p><?php _e( 'Do you want to integrate this plugin with another system (helpdesk, CRM, etc.)?' );?><br>
	<?php printf(__("You can order it here: %s"), $this->html_a($url, $url, array('target' => '_blank')));?></p>
</div>