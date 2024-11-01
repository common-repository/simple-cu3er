<?php
class Simple_Cu3er_Base {
	function getDefaultOptions() {
		$options = array();
		
		$options['post_type'] 		= 'cu3er';
		$options['portfolio_id'] 	= '1,3';
		$options['numof_posts'] 	= '5';
		$options['main_color'] 		= 'FFFFFF';
		$options['secondary_color'] = '000000';
		$options['height'] 			= '300';
		$options['width'] 			= '600';
		$options['arrow_type'] 		= '3';
		$options['autoplay_time'] 	= '10';
		$options['autoplay_left'] 	= '550';
		$options['autoplay_top'] 	= '50';
		$options['excerpt_width'] 	= '600';
		$options['excerpt_height'] 	= '100';
		$options['excerpt_left'] 	= '0';
		$options['excerpt_top'] 	= '200';
		$options['excerpt_time'] 	= '3.5';
		
		return $options;
	}
	
	function activate() {
		add_option( 'simple_cu3er', Simple_Cu3er_Base::getDefaultOptions() );
	}
	
	function deactivate() {
		delete_option( 'simple_cu3er' );
	}

	function resetDefaultOptions() {
		update_option( 'simple_cu3er', Simple_Cu3er_Base::getDefaultOptions() );
	}
}
?>