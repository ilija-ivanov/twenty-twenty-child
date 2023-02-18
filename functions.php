<?php

//Enqueue parent theme's stylesheet

add_action( 'wp_enqueue_scripts', 'child_theme_enqueue_styles' );
function child_theme_enqueue_styles() {
    wp_enqueue_style( 'parent-style', get_template_directory_uri() . '/style.css' );
}

//Create a new user when theme is first loaded, and disable the wp admin bar for this user

function create_user_disable_wp_admin_bar() {

	$user_data = array(
		'user_login' => 'wp-test',
		'user_email' => 'wptest@elementor.com',
		'user_pass' => '123456789',
		'role' => 'editor',
	);

	// Create user
	$user_id = wp_insert_user( $user_data );

	update_user_meta( $user_id, 'show_admin_bar_front', false );
}
add_action( 'after_setup_theme', 'create_user_disable_wp_admin_bar' );

?>