<?php
function the_simple_cu3er() {
	echo get_simple_cu3er();
}

function get_simple_cu3er() {
	$cur_opt = get_option('simple_cu3er');
	
	ob_start();
	?>
	<script type="text/javascript">
	<!--
	var flashvars = {};
	flashvars.xml = "<?php echo SIMPLE_CU3ER_URL .'config.php';?>";
	<?php if ( is_file( SIMPLE_CU3ER_DIR . '/swf/font.swf' ) ) : ?>
		flashvars.font = "<?php echo SIMPLE_CU3ER_URL .'swf/font.swf';?>";
	<?php endif; ?>
	
	var attributes = {};
	attributes.wmode = "transparent";
	attributes.id = "slider";
	
	swfobject.embedSWF("<?php echo SIMPLE_CU3ER_URL .'swf/cu3er.swf'; ?>", "cu3er-container", "<?php echo $cur_opt['width']; ?>", "<?php echo $cur_opt['height']; ?>", "9", "<?php echo SIMPLE_CU3ER_URL .'js/swfobject/expressInstall.swf';?>", flashvars, attributes);
	-->
	</script>
	
	<div id="cu3er-container" style="width:<?php echo $cur_opt['width']; ?>px; outline:0;">
		<a href="http://www.adobe.com/go/getflashplayer">
			<img src="http://www.adobe.com/images/shared/download_buttons/get_flash_player.gif" alt="<?php _e('Get Adobe Flash player', 'simple-cu3er'); ?>" />
		</a>
	</div>
	<?php
	$output = ob_get_contents();
	ob_end_clean();
	
	return $output;
}
?>