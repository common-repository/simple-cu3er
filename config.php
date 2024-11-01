<?php
header("Status: 200");
header("Content-type: text/xml");

if ( !defined('ABSPATH') )
	require_once( '../../../wp-load.php' );

$slice_value 			= array("horizontal", "vertical");
$rand_slice_keys 		= array_rand($slice_value, 2);

$direction_value 		= array("left", "right", "up", "down");
$rand_direction_keys 	= array_rand($direction_value, 4);

$cur_opt 				= get_option('simple_cu3er');

/**
 * Retrieve Post Thumbnail SRC Only
 *
 * @since 2.9.0
 *
 * @param int $post_id Optional. Post ID.
 * @param string $size Optional. Image size.  Defaults to 'thumbnail'.
 * @param string|array $attr Optional. Query string or array of attributes.
  */
function get_the_post_thumbnail_src( $post_id = NULL, $size = 'post-thumbnail', $attr = '' ) {
	global $id;
	$post_id = ( NULL === $post_id ) ? $id : $post_id;
	$post_thumbnail_id = get_post_thumbnail_id($post_id);
	$size = apply_filters( 'post_thumbnail_size', $size );
	if ( $post_thumbnail_id ) {
		do_action( 'begin_fetch_post_thumbnail_html', $post_id, $post_thumbnail_id, $size ); // for "Just In Time" filtering of all of wp_get_attachment_image()'s filters
		$img = wp_get_attachment_image_src( $post_thumbnail_id, $size, false, $attr );
		$html = $img[0];
		do_action( 'end_fetch_post_thumbnail_html', $post_id, $post_thumbnail_id, $size );
	} else {
		$html = '';
	}
	return apply_filters( 'post_thumbnail_html', $html, $post_id, $post_thumbnail_id, $size, $attr );
}

// Build HTML
$xml  = '';
$xml .= '<?xml version="1.0" encoding="utf-8" ?>' . "\n";
$xml .= '<cu3er>' . "\n";
	$xml .= '<settings>' . "\n";
		$xml .= '<auto_play>' . "\n";
			$xml .= '<defaults symbol="circular" time="' . $cur_opt['autoplay_time'] . '" />' . "\n";
			$xml .= '<tweenIn x="' . $cur_opt['autoplay_left'] . '" y="' . $cur_opt['autoplay_top'] . '" width="35" height="35" tint="0x' . $cur_opt['main_color'] . '" />' . "\n";
		$xml .= '</auto_play>' . "\n";
	
		$xml .= '<description>' . "\n";
			$xml .= '<defaults round_corners="5, 5, 5, 5" heading_font="Trebuchet MS, Arial, Helvetica, sans-serif" heading_text_size="22" heading_text_color="0x' . $cur_opt['main_color'] . '" heading_text_margin="10, 0, 0,10" paragraph_font="Trebuchet MS, Arial, Helvetica, sans-serif" paragraph_text_size="13" paragraph_text_color="0x' . $cur_opt['main_color'] . '" paragraph_text_margin="10, 0, 0, 10" />' . "\n";
			$xml .= '<tweenIn tint="0x' . $cur_opt['secondary_color'] . '"  x="' . $cur_opt['excerpt_left'] . '" y="' . $cur_opt['excerpt_top'] . '" alpha="0.5" width="' . $cur_opt['excerpt_width'] . '" height="' . $cur_opt['excerpt_height'] . '" />' . "\n";
			$xml .= '<tweenOut time="' . $cur_opt['excerpt_time'] . '" x="' . $cur_opt['excerpt_left'] . '" />' . "\n";
			$xml .= '<tweenOver tint="0x' . $cur_opt['secondary_color'] . '" alpha="0.8" />' . "\n";
		$xml .= '</description>' . "\n";
	$xml .= '</settings>' . "\n";

$xml .= '<slides>' . "\n";

	if ( $cur_opt['post_type'] == 'cu3er' ) {
		query_posts( array( 'posts_per_page' => $cur_opt['numof_posts'], 'post_type' => 'cu3er', 'post_status' => 'publish' ) );
	} else {
		query_posts( array( 'cat' => $cur_opt['portfolio_id'], 'posts_per_page' => $cur_opt['numof_posts'], 'post_type' => 'post', 'post_status' => 'publish' ) );
	}
	
	if( have_posts() ) :
		global $post;
		while( have_posts() ) : the_post(); 
			if ( $cur_opt['post_type'] == 'cu3er' ) {
				$link  = get_post_meta( $post->ID, 'cu3er_link', true );
				$title = $post->post_title;
				$text  = $post->post_content;
			} else {
				$link  = get_permalink($post);
				$title = $post->post_title;
				$text  = $post->post_excerpt;
			}
			
			$xml .= '<slide>' . "\n";
				if ( has_post_thumbnail() ) 
				 	$xml .= '<url>' . get_the_post_thumbnail_src( $post->ID, 'cuber-thumbnail' ) . '</url>' . "\n";
				
				$xml .= '<description>' . "\n";
					$xml .= '<link target="_self">' . $link . '</link>' . "\n";
					$xml .= '<heading>' . $title . '</heading>' . "\n";
					$xml .= '<paragraph>' . $text . '</paragraph>' . "\n";
				$xml .= '</description>' . "\n";
			$xml .= '</slide>' . "\n";
		
			$xml .= '<transition num="' . rand(2, 5) . '" slicing="' . $slice_value[$rand_slice_keys[rand(0, 1)]] . '" direction="' . $direction_value[$rand_direction_keys[rand(0, 3)]] . '" shader="phong" delay="0.05" z_multiplier="4" />' . "\n";
		endwhile;
	endif;
$xml .= '</slides>' . "\n";
$xml .= '</cu3er>' . "\n";

echo apply_filters( 'config_cu3er', $xml );
exit();
?>