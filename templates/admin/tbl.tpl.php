<style>
.wp-list-table th {
	text-transform: capitalize;
}


</style>
<div class="wrap">
	<?php include 'admin_header.php'; ?>
	<h2><?php echo $title?></h2>

	<p>
		<A href='<?php echo $url_csv?>' target='_blank'><?php _e( 'Export in CSV', 'popup4phone' ); ?></a>
	</p>

 	<div class="metabox-holder columns-2">
		<div id="post-body-content">
			<div class="meta-box-sortables ui-sortable <?php echo $table->plugin_id?>">
				<form method="post">
					<?php
						$table->prepare_items();
						$table->display();
					?>
				</form>
			</div>
		</div>
	</div>

</div>
