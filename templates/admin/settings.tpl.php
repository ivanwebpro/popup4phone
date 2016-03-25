<style>
input[type=text]
{
	width: 100%;
}

</style>
<div class="wrap">
	<?php include 'admin_header.php'; ?>
	<div id="icon-themes" class="icon32"></div>
	<h2><?php echo "Popup4Phone: ".__( 'Settings', 'popup4phone' ); ?></h2>
	<?php settings_errors(); ?>

	<div>
		<h2 class="popup4phone nav-tab-wrapper">
	 	<?php
			$tab = $this->tab();

			foreach($opts as $s_id=>$sd)
			{
				if ($s_id == $tab)
					$ex_class = 'nav-tab-active';
				else
        	$ex_class = '';
				$url = "?page=$p_id&tab=$s_id";

				if (isset($_GET['l']))
				{
        	$url .= "&l=".$_GET['l'];
				}
				?>
        	<a href="<?php echo $url?>" style='border-bottom: 1px solid transparent' data-tab="<?php echo $s_id?>" class="nav-tab <?php echo $ex_class?>"><?php echo $sd['title']?></a>
				<?php
			}
		?>
  	</h2>
	</div>

	<form method="POST" action="options.php">
	<?php
		echo $this->html_field_hidden("tab", $tab);		
		$sp_id = $this->page_id();
		settings_fields($sp_id);
		//print "<b>$tab</b><br>";
		if ('fields' == $tab)
		{
     	$this->print_settings_fields();
		}
		else
			do_settings_sections($sp_id);
		submit_button();
	?>
	</form>

</div>