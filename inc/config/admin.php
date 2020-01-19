<?php
/**
 * Initialize on admin
 */
function admin_initialize() {
	remove_meta_box('dashboard_incoming_links', 'dashboard', 'normal');
	remove_meta_box('dashboard_plugins', 'dashboard', 'normal');
	remove_meta_box('dashboard_primary', 'dashboard', 'normal');
	remove_meta_box('dashboard_secondary', 'dashboard', 'normal');
	remove_meta_box('dashboard_quick_press', 'dashboard', 'side');
	remove_meta_box('dashboard_recent_drafts', 'dashboard', 'side');
	remove_meta_box('dashboard_recent_comments', 'dashboard', 'normal');
	remove_meta_box('dashboard_right_now', 'dashboard', 'normal');
	remove_meta_box('dashboard_activity', 'dashboard', 'normal');
	remove_meta_box('rg_forms_dashboard', 'dashboard', 'normal');
	remove_action('admin_notices', 'update_nag');
}
add_action('admin_init', 'admin_initialize');

/**
 * Remove footer text on admin
 */
function admin_footer() {
	return '';
}
add_filter('admin_footer_text', 'admin_footer');

/**
 * Remove admin menus
 */
function admin_remove_menus() {
	// remove_menu_page('index.php');  // Dashboard
	// remove_menu_page('jetpack');  // Jetpack
	// remove_menu_page('edit.php'); // Posts
	// remove_menu_page('upload.php'); // Media
	// remove_menu_page('edit.php?post_type=page'); // Pages
	remove_menu_page('edit-comments.php'); // Comments
	// remove_menu_page('themes.php'); // Appearance
	// remove_menu_page('plugins.php'); // Plugins
	// remove_menu_page('users.php'); // Users
	// remove_menu_page('tools.php'); // Tools
	// // remove_menu_page('options-general.php'); // Settings
	// remove_menu_page('wpcf7'); // Contact Form 7
}
add_action('admin_menu', 'admin_remove_menus');

/**
 * Admin login style
 */
function admin_login_style() {
	wp_enqueue_style( 'custom-login', get_stylesheet_directory_uri(). '/dist/admin.min.css' );
}
add_action('login_enqueue_scripts', 'admin_login_style');

/**
 * Change admin logo link on login page
 */
function admin_logo_link() {
	return get_bloginfo('url');
}
add_filter('login_headerurl', 'admin_logo_link');

/**
 * Hide updates on admin
 */
function admin_hide_updates() {
	global $wp_version;
	return (object) [
		'last_checked'=> time(),
		'version_checked'=> $wp_version
	];
}
add_filter('pre_site_transient_update_core', 'admin_hide_updates');
add_filter('pre_site_transient_update_plugins', 'admin_hide_updates');
add_filter('pre_site_transient_update_themes', 'admin_hide_updates');
