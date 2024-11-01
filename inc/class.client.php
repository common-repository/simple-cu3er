<?php
class Simple_Cu3er_Client {
	function Simple_Cu3er_Client() {
		$cur_opt = get_option('simple_cu3er');
			
		// Add JS for flash
		add_action( 'wp_print_scripts', array(&$this, 'swfobject') );
		
		// Add thumb sizes
		add_theme_support( 'post-thumbnails' );
		add_image_size( 'cuber-thumbnail', $cur_opt['width'], $cur_opt['height'], true );
		
		// Add short code ?
		add_shortcode( 'cu3er', 'get_simple_cu3er' );
		
		if ( $cur_opt['post_type'] == 'cu3er' ) {
			// Register post_type
			register_post_type( 'cu3er', array(
				'labels' 				=> array(
											'name' => _x('Cu3er Items', 'post type general name', 'simple-cu3er'),
											'singular_name' => _x('Cu3er Item', 'post type singular name', 'simple-cu3er'),
											'add_new' => _x('Add New', 'post', 'simple-cu3er'),
											'add_new_item' => __('Add New Cu3er Item', 'simple-cu3er'),
											'edit_item' => __('Edit Cu3er Item', 'simple-cu3er'),
											'new_item' => __('New Cu3er Item', 'simple-cu3er'),
											'view_item' => __('View Cu3er Item', 'simple-cu3er'),
											'search_items' => __('Search Cu3er Items', 'simple-cu3er'),
											'not_found' => __('No Cu3er items found', 'simple-cu3er'),
											'not_found_in_trash' => __('No Cu3er items found in Trash', 'simple-cu3er'),
											'parent_item_colon' => __('Parent Cu3er Item:', 'simple-cu3er')
										),
				'description' 			=> __('Items for Cu3Er flash.', 'simple-cu3er'),
				'publicly_queryable' 	=> true,
				'exclude_from_search' 	=> true,
				'public' 				=> true,
				'capability_type' 		=> 'post',
				'capabilities' 			=> array(),
				'hierarchical' 			=> false,
				'rewrite' 				=> false,
				'query_var' 			=> false,
				'supports' 				=> array( 'title', 'editor', 'thumbnail' ),
				'taxonomies' 			=> array(),
				'show_ui' 				=> true,
				'menu_position' 		=> null,
				'menu_icon' 			=> null,
				'can_export' 			=> true,
				'show_in_nav_menus'		=> false
			) );
		}
	}
	
	function swfobject() {
		if( !is_admin() ) {
			wp_enqueue_script( 'swfobject' );
		}
	}
}
?>