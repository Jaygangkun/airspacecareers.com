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
