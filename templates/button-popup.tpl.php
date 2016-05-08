<?php
//<?php echo $w;> <php echo $h;?

$w = $this->opt('popover_button_width');
$w_px = $w.'px';
$h = $this->opt('popover_button_height');
$h_px = $h.'px';

$fs_px = $this->opt('popup_button_caption_font_size')."px";

$offs_r_px = $this->opt('popup_button_offset_right').'px';
$offs_b_px = $this->opt('popup_button_offset_bottom').'px';

$c_ph = $this->opt('popover_button_phone_handset_color');
$c_bg = $this->opt('popover_button_background_color');
$use_caption = $this->opt('popup_button_caption_enabled');
$c_class = "popup4phone-popover-button";
$custom_icon = apply_filters(Popup4Phone_Filters::BUTTON_CUSTOM_ICON, '');

if (!empty($inline))
	$c_class = " popup4phone-popover-button-inline";

if (!$use_caption && !$custom_icon)
	$c_class .= " popup4phone-popover-button-icon";


//else
	//$c_class = ""
?>

<style>
.popup4phone-popover-button.popup4phone-popover-button-icon,
.popup4phone-popover-button-inline.popup4phone-popover-button-icon,
.popup4phone-popover-button .wrapper,
.popup4phone-popover-button-inline .wrapper,
.popup4phone-popover-button svg,
.popup4phone-popover-button-inline svg
{
	width: <?php echo $w_px;?>;
	height: <?php echo $h_px;?>;
}

.popup4phone-popover-button
{
	right: <?php echo $offs_r_px; ?>;
	bottom: <?php echo $offs_b_px; ?>;
}

.popup4phone-popover-button-icon-background
{
	fill: <?php echo $c_bg;?>;
}

.popup4phone-popover-button-caption
{
	background-color: <?php echo $c_bg;?>;
	color: <?php echo $c_ph;?>;
	padding: 10px;
	border-radius: 15px;
	font-weight: bold;
	font-size: <?php echo $fs_px;?>;
}

.popup4phone-popover-button-icon-handset
{
	fill: <?php echo $c_ph;?>;
}

.popup4phone-popover-button-animation-wrapper
{
	position: relative;
}

</style>
<div class='popup4phone'>
	<div class='<?php echo $c_class?>'>
	<div class='popup4phone-popover-button-animation-wrapper'>
		<?php
			if ($use_caption)
			{
				echo "<div class = 'popup4phone-popover-button-caption'>";
        echo $this->opt('popup_button_caption');
				echo "</div>";
			}
			else if ($custom_icon)
			{
				echo $custom_icon;
			}
      else
			{
				echo "<div class='wrapper'>";
				include 'popover-button-icon.php';
				echo "</div>";
			}
		?>
	</div>
	</div>
</div>