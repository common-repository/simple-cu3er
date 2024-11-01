<?php
class Simple_Cu3er_Admin {
	function Simple_Cu3er_Admin() {
		add_action('admin_menu', array(&$this, 'addMenu') );
		add_action('admin_init', array(&$this, 'adminScriptsStyles') ); 
	}
	
	function adminScriptsStyles() {
		if ( isset($_GET['page']) && $_GET['page'] == 'simple-cu3er' ) {
			wp_enqueue_script( 'color_picker', SIMPLE_CU3ER_URL . 'js/colorpicker.js', array('jquery'), SIMPLE_CU3ER_VER );
			wp_enqueue_style ( 'color_picker', SIMPLE_CU3ER_URL . 'css/colorpicker.css', array(), SIMPLE_CU3ER_VER );
			
			wp_enqueue_style ( 'simple-cu3er', SIMPLE_CU3ER_URL . 'css/admin.css', array(), SIMPLE_CU3ER_VER );
			wp_enqueue_script( 'simple-cu3er', SIMPLE_CU3ER_URL . 'js/admin.js', array('color_picker'), SIMPLE_CU3ER_VER );
		}
		
		$cur_opt = get_option('simple_cu3er');
		if ( $cur_opt['post_type'] == 'cu3er' ) {
			// Add metabox
			add_action( 'add_meta_boxes', array(&$this, 'initMetabox'), 10, 1 );
			
			// Save datas from metabox
			add_action( 'save_post', array(&$this, 'saveMetabox'), 10, 1 );
		}
	}

	
	function initMetabox( $post_type = '' ) {
		if ( !in_array( $post_type, array('cu3er') ) )
			return false;
			
		add_meta_box( 'cu3er-meta', __('Cu3er Info', 'simple-cu3er'), array(&$this, 'formMetabox'), $post_type, 'side', 'default' );
		return true;
	}
	
	function saveMetabox( $post_ID = 0 ) {
		if ( isset($_POST['cu3er_link']) ) {
			if ( empty($_POST['cu3er_link']) ) {
				delete_post_meta( $post_ID, 'cu3er_link' );
			} else {
				update_post_meta( $post_ID, 'cu3er_link', esc_url($_POST['cu3er_link']) );
			}
		}
		
		return true;
	}

	function formMetabox( $object, $post_type ) {
		$current_link = get_post_meta( $object->ID, 'cu3er_link', true );
		?>
		<p>
			<label for="cu3er_link"><?php _e('Destination link', 'simple-cu3er'); ?></label>
			<br />
			<input class="widefat" name="cu3er_link" id="cu3er_link" value="<?php echo esc_url($current_link); ?>" />
			<div class="clear"></div>
		</p>
		<?php
	}

	function addMenu() {
		add_options_page( __('Simple Cu3er', 'simple-cu3er'), __('Simple Cu3er', 'simple-cu3er'), 'manage_options', 'simple-cu3er', array(&$this, 'pageManage') );
	}
	
	function pageManage() { 
		if ( isset($_POST['save']) ) {
			
			$all_options = Simple_Cu3er_Base::getDefaultOptions();
			
			$new_options = array();
			foreach( $all_options as $key => $default_val ) {
				$new_options[$key] = stripslashes($_POST[$key]);
			}
			
			update_option( 'simple_cu3er', $new_options );
		}
		
		if (isset($_POST['save'])) {
			echo '<div class="message updated"><p>'.__('Simple Cu3er options updated!', 'simple-cu3er').'</p></div>';
		} elseif (isset($_POST['reset'])) {
			Simple_Cu3er_Base::resetDefaultOptions();
			echo '<div class="message updated"><p>'.__('Simple Cu3er options has been reset!', 'simple-cu3er').'</p></div>';
		}
		
		$cur_opt = get_option('simple_cu3er');
		?>
		<div class="wrap">
			<h2><?php _e('Simple Cu3er Options', 'simple-cu3er'); ?></h2>
			
			<form action="" method="post" name="massive_form" id="massive_form">
				<table class="form-table">
					<tr valign="top">
						<th colspan="3" scope="row">
							<h3><?php _e('Basic Settings', 'simple-cu3er'); ?></h3>
						</th>
					</tr>
					
					<tr valign="top">
						<th scope="row"><?php _e('Content type', 'simple-cu3er'); ?></th>
						<td class="alt">
							<select name="post_type" id="post_type">
								<option <?php selected($cur_opt['post_type'], 'post'); ?> value="post"><?php _e('Posts', 'simple-cu3er'); ?></option>
								<option <?php selected($cur_opt['post_type'], 'cu3er'); ?> value="cu3er"><?php _e('Cu3er Items', 'simple-cu3er'); ?></option>
							</select>
						</td>
						<td class="alt"><?php _e('Choose the source of content for Cu3er : Posts or Cu3er Items. (custom post type)', 'simple-cu3er'); ?></td>
					</tr>
					
					<tr valign="top">
						<th scope="row"><?php _e('Category IDs', 'simple-cu3er'); ?></th>
						<td class="alt">
							<input name="portfolio_id" id="portfolio_id" value="<?php echo $cur_opt['portfolio_id']; ?>" size="5" />
						</td>
						<td class="alt"><?php _e('numeric, comma separated', 'simple-cu3er'); ?></td>
					</tr>
					
					<tr valign="top">
						<th scope="row"><?php _e('Number of Posts', 'simple-cu3er'); ?></th>
						<td><input name="numof_posts" id="numof_posts" value="<?php echo $cur_opt['numof_posts']; ?>" size="5" /></td>
						<td><?php _e('numeric', 'simple-cu3er'); ?></td>
					</tr>
					
					<tr valign="top">
						<th scope="row"><?php _e('Cu3er width', 'simple-cu3er'); ?></th>
						<td class="alt"><input name="width" id="width" value="<?php echo $cur_opt['width']; ?>" size="5" /></td>
						<td class="alt"><?php _e('px', 'simple-cu3er'); ?></td>
					</tr>
					
					<tr valign="top">
						<th scope="row"><?php _e('Cu3er height', 'simple-cu3er'); ?></th>
						<td><input name="height" id="height" value="<?php echo $cur_opt['height']; ?>" size="5" /></td>
						<td><?php _e('px', 'simple-cu3er'); ?></td>
					</tr>
					
					<tr valign="top">
						<th scope="row"><?php _e('Cu3er Main Color', 'simple-cu3er'); ?></th>
						<td class="alt"><input name="main_color" id="main_color" value="<?php echo $cur_opt['main_color']; ?>" size="5" /></td>
						<td class="alt"><?php _e('HEX, text, arrows and timer color', 'simple-cu3er'); ?></td>
					</tr>
					
					<tr valign="top">
						<th scope="row"><?php _e('Cu3er Secondary Color', 'simple-cu3er'); ?></th>
						<td><input name="secondary_color" id="secondary_color" value="<?php echo $cur_opt['secondary_color']; ?>" size="5" /></td>
						<td><?php _e('HEX, Background colors', 'simple-cu3er'); ?></td>
					</tr>
					
					<tr valign="top">
						<th scope="row"><?php _e('Cu3er Arrow Type', 'simple-cu3er'); ?></th>
						<td class="alt"><input name="arrow_type" id="arrow_type" value="<?php echo $cur_opt['arrow_type']; ?>" size="5" /></td>
						<td class="alt">
							<?php _e('numeric (<span id="showArrows">View Available</span>)', 'simple-cu3er'); ?>
							<div id="arrowType"><img src="<?php echo SIMPLE_CU3ER_URL .'images/arrows.gif'; ?>" alt="Arrows" /></div>
						</td>
					</tr>
					
					<tr valign="top">
						<th colspan="3" scope="row">
							<h3><?php _e('Timer Settings', 'simple-cu3er'); ?></h3>
						</th>
					</tr>
					
					<tr valign="top">
						<th scope="row"><?php _e('Slide Queue Time', 'simple-cu3er'); ?></th>
						<td class="alt"><input name="autoplay_time" id="autoplay_time" value="<?php echo $cur_opt['autoplay_time']; ?>" size="5" /></td>
						<td class="alt"><?php _e('seconds', 'simple-cu3er'); ?></td>
					</tr>
					
					<tr valign="top">
						<th scope="row"><?php _e('Timer Left Padding', 'simple-cu3er'); ?></th>
						<td><input name="autoplay_left" id="autoplay_left" value="<?php echo $cur_opt['autoplay_left']; ?>" size="5" /></td>
						<td><?php _e('px', 'simple-cu3er'); ?></td>
					</tr>
					
					<tr valign="top">
						<th scope="row"><?php _e('Timer Top Padding', 'simple-cu3er'); ?></th>
						<td class="alt"><input name="autoplay_top" id="autoplay_top" value="<?php echo $cur_opt['autoplay_top']; ?>" size="5" /></td>
						<td class="alt"><?php _e('px', 'simple-cu3er'); ?></td>
					</tr>
					
					<tr valign="top">
						<th colspan="3" scope="row">
							<h3><?php _e('Title and Excerpt Settings', 'simple-cu3er'); ?></h3>
						</th>
					</tr>
					
					<tr valign="top">
						<th scope="row"><?php _e('Excerpt Area Queue Time', 'simple-cu3er'); ?></th>
						<td class="alt"><input name="excerpt_time" id="excerpt_time" value="<?php echo $cur_opt['excerpt_time']; ?>" size="5" /></td>
						<td class="alt"><?php _e('seconds', 'simple-cu3er'); ?></td>
					</tr>
					
					<tr valign="top">
						<th scope="row"><?php _e('Excerpt Area Width', 'simple-cu3er'); ?></th>
						<td><input name="excerpt_width" id="excerpt_width" value="<?php echo $cur_opt['excerpt_width']; ?>" size="5" /></td>
						<td><?php _e('px', 'simple-cu3er'); ?></td>
					</tr>
					
					<tr valign="top">
						<th scope="row"><?php _e('Excerpt Area Height', 'simple-cu3er'); ?></th>
						<td class="alt"><input name="excerpt_height" id="excerpt_height" value="<?php echo $cur_opt['excerpt_height']; ?>" size="5" /></td>
						<td class="alt"><?php _e('px', 'simple-cu3er'); ?></td>
					</tr>
					
					<tr valign="top">
						<th scope="row"><?php _e('Excerpt Area Left Padding', 'simple-cu3er'); ?></th>
						<td><input name="excerpt_left" id="excerpt_left" value="<?php echo $cur_opt['excerpt_left']; ?>" size="5" /></td>
						<td><?php _e('px', 'simple-cu3er'); ?></td>
					</tr>
					
					<tr valign="top">
						<th scope="row"><?php _e('Excerpt Area Top Padding', 'simple-cu3er'); ?></th>
						<td class="alt"><input name="excerpt_top" id="excerpt_top" value="<?php echo $cur_opt['excerpt_top']; ?>" size="5" /></td>
						<td class="alt"><?php _e('px', 'simple-cu3er'); ?></td>
					</tr>
				</table>
				
				<p class="submit">
					<input class="button-primary" type="submit" name="save" value="<?php _e('Save Options', 'simple-cu3er'); ?>" />
					<input class="button" type="submit" name="reset" value="<?php _e('Reset Options', 'simple-cu3er'); ?>" />
				</p>
			</form>
			<br />
			<p>
				<?php _e('Plugin by <a href="http://www.beapi.fr/simple-cu3er">Be API</a>', 'simple-cu3er'); ?> |
				<?php _e('Cu3er by Stefan Kovac @ <a href="http://www.progressivered.com/cu3er/docs/">ProgressiveRed</a>', 'simple-cu3er'); ?>
			</p>
		</div>
		<?php
	}
}
?>