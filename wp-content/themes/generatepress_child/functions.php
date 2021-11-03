<?php
/**
 * GeneratePress child theme functions and definitions.
 *
 * Add your custom PHP in this file. 
 * Only edit this file if you have direct access to it on your server (to fix errors if they happen).
 */

 /*Changes text Event Calendar*/
$custom_text = [
  'Livestream' => 'Online Event',
  'Livestream %s' => 'Online Event %s',
];

function generatepress_child_enqueue_scripts() {
	if ( is_rtl() ) {
		wp_enqueue_style( 'generatepress-rtl', trailingslashit( get_template_directory_uri() ) . 'rtl.css' );
	}
}
add_action( 'wp_enqueue_scripts', 'generatepress_child_enqueue_scripts', 100 );
/* Create a Theme Location called "Header Right" */
register_nav_menu( 'header-right', 'Header Right' );

/* Insert the Header Right menu after the logo */
add_action( 'generate_after_header_content', 'add_header_right_nav' );
function add_header_right_nav(){
    ?>
    <div class="header-right">
        <?php if( function_exists( 'ubermenu' ) ): ?>
            <?php ubermenu( 'header_right', array( 'theme_location' => 'header-right' ) ); ?>
        <?php endif; ?>
    </div>
    <?php
}

/* Replace the theme's menu with UberMenu */
function generate_navigation_position(){
    if( function_exists( 'ubermenu' ) ){
        ubermenu( 'main' , array( 'theme_location' => 'primary' ) );
    }
}
 
/* Stop the theme from filtering the menu output */
add_action( 'wp_head' , 'stop_generatepress_menu_filter' );
function stop_generatepress_menu_filter(){
    remove_filter( 'walker_nav_menu_start_el', 'generate_nav_dropdown', 10, 4 );
}
if (is_user_logged_in()) {
    show_admin_bar(true);
}
/** Stops Yoast notifications. */
add_filter( 'wpseo_update_notice_content', '__return_null' );
/**add image size**/
add_image_size( 'eventcal-image', 200, 9999 ); //300 pixels wide (and unlimited height)

add_action('save_post', 'company_profile_update_progressmap_field', 100, 3);
function company_profile_update_progressmap_field( $post_id, $post, $update) {
    
    if(get_post_type($post_id) != 'company_profile') {
        return;
    }

    $cspm_lat_lng = '';

    if(isset($_POST['acff']) && isset($_POST['acff']['post']) && isset($_POST['acff']['post']['field_6177379af8b9b'])) {
        // submit company profile
        $address = '';
        $lat = '';
        $lng = '';
        $index = 0;
        foreach($_POST['acff']['post']['field_6177379af8b9b'] as $key => $map) {
            if(isset($map['field_617737bdf8b9c'])) {
                $mapData = $map['field_617737bdf8b9c'];
                $mapData = stripslashes($mapData);
                $mapData = json_decode($mapData, true);
                $cspm_lat_lng = $cspm_lat_lng.'['.$mapData['lat'].', '.$mapData['lng'].']';
                if($index == 0) {
                    $address = $mapData['address'];
                    $lat = $mapData['lat'];
                    $lng = $mapData['lng'];
                }

                $index ++;
            }
        }
        add_post_meta($post_id, 'codespacing_progress_map_address', $address);
        add_post_meta($post_id, 'codespacing_progress_map_secondary_lat_lng', $cspm_lat_lng);
        add_post_meta($post_id, '_cspm_enable_media_marker_click', 'yes');
        add_post_meta($post_id, '_cspm_location', array(
            'codespacing_progress_map_address' => $address,
            'acf_fields-2489-center_lat' => $lat,
            'profile_google_map_in_repeater_field' => $lng,
            'codespacing_progress_map_secondary_lat_lng' => $cspm_lat_lng,
        ));
        add_post_meta($post_id, '_cspm_marker_icon_height', '32');
        add_post_meta($post_id, '_cspm_marker_icon_width', '30');
        add_post_meta($post_id, '_cspm_marker_label_options', array(array('hide_label' => 'no')));
        add_post_meta($post_id, '_cspm_post_format', 'standard');

    }

    if ($update) {
        // update company profile
        $cspm_lat_lng = '';
        $address = '';
        $lat = '';
        $lng = '';
        $index = 0;

        if( have_rows('profile_google_map_repeater_field', $post_id) ): while ( have_rows('profile_google_map_repeater_field', $post_id) ) : the_row();
            $location = get_sub_field('profile_google_map_in_repeater_field');
            
            $cspm_lat_lng = $cspm_lat_lng.'['.$location['lat'].', '.$location['lng'].']';
            if($index == 0) {
                $address = $location['address'];
                $lat = $location['lat'];
                $lng = $location['lng'];
            }

            $index++;
        endwhile; endif;

        delete_post_meta($post_id, 'codespacing_progress_map_address');
        delete_post_meta($post_id, 'codespacing_progress_map_secondary_lat_lng');
        delete_post_meta($post_id, '_cspm_enable_media_marker_click');
        delete_post_meta($post_id, '_cspm_location');
        delete_post_meta($post_id, '_cspm_marker_icon_height');
        delete_post_meta($post_id, '_cspm_marker_icon_width');
        delete_post_meta($post_id, '_cspm_marker_label_options');
        delete_post_meta($post_id, '_cspm_post_format');
        
        update_post_meta($post_id, 'codespacing_progress_map_address', $address);
        update_post_meta($post_id, 'codespacing_progress_map_secondary_lat_lng', $cspm_lat_lng);
        update_post_meta($post_id, '_cspm_enable_media_marker_click', 'yes');
        update_post_meta($post_id, '_cspm_location', array(
            'codespacing_progress_map_address' => $address,
            'acf_fields-2489-center_lat' => $lat,
            'profile_google_map_in_repeater_field' => $lng,
            'codespacing_progress_map_secondary_lat_lng' => $cspm_lat_lng,
        ));
        update_post_meta($post_id, '_cspm_marker_icon_height', '32');
        update_post_meta($post_id, '_cspm_marker_icon_width', '30');
        update_post_meta($post_id, '_cspm_marker_label_options', array(array('hide_label' => 'no')));
        update_post_meta($post_id, '_cspm_post_format', 'standard');
        
    }
    
}
