<?php
/*
@package sunsettheme

	================================
	SUNSET ADMIN FUNCTIONS
	================================
*/

// Generate Sunset Admin Page and sections
function sunset_add_admin_page()
{
	//  Set Custom Icon URI variable
	$icon_link = get_template_directory_uri() . '/img/sunset-icon.png';

	//  Generate Sunset Admin Page
	//  add_menu_page() reference: https://codex.wordpress.org/Function_Reference/add_menu_page
	add_menu_page( 'Sunset Theme Options', 'Sunset', 'manage_options', 'alecaddd_sunset', 'sunset_theme_sidebar_page', $icon_link, 110 );

	//  Generate Sunset Admin Sub Pages
	add_submenu_page( 'alecaddd_sunset','Sunset Sidebar Options', 'Sidebar', 'manage_options', 'alecaddd_sunset', 'sunset_theme_sidebar_page');
	add_submenu_page( 'alecaddd_sunset','Sunset Theme Options', 'Theme Options', 'manage_options', 'alecaddd_sunset_theme', 'sunset_theme_support_page' );
	add_submenu_page( 'alecaddd_sunset','Sunset CSS Options', 'Custom CSS', 'manage_options', 'alecaddd_sunset_css', 'sunset_theme_settings_page' );

	//  Activate custom settings
	add_action('admin_init', 'sunset_custom_settings');
}
add_action('admin_menu', 'sunset_add_admin_page');

// Registration Section
function sunset_custom_settings(){
	// Theme Sidebar Options
	register_setting('sunset-theme-sidebar', 'profile_picture');
	register_setting('sunset-theme-sidebar', 'first_name');
	register_setting('sunset-theme-sidebar', 'last_name');
	register_setting('sunset-theme-sidebar', 'user_description');
	register_setting('sunset-theme-sidebar', 'twitter_handler', 'sunset_sanitize_twitter_callback');
	register_setting('sunset-theme-sidebar', 'facebook_handler');
	register_setting('sunset-theme-sidebar', 'gplus_handler');

	add_settings_section('sunset-theme-sidebar-options', 'Sidebar Options', 'sunset_theme_sidebar_options', 'alecaddd_sunset');

	add_settings_field('sidebar-profile-picture', 'Profile Picture', 'sunset_sidebar_profile', 'alecaddd_sunset', 'sunset-theme-sidebar-options');
	add_settings_field('sidebar-name', 'Full Name', 'sunset_sidebar_name', 'alecaddd_sunset', 'sunset-theme-sidebar-options');
	add_settings_field('sidebar-description', 'Description', 'sunset_sidebar_description', 'alecaddd_sunset', 'sunset-theme-sidebar-options');
	add_settings_field('sidebar-twitter', 'Twitter Handler', 'sunset_sidebar_twitter', 'alecaddd_sunset', 'sunset-theme-sidebar-options');
	add_settings_field('sidebar-facebook', 'Facebook Handler', 'sunset_sidebar_facebook', 'alecaddd_sunset', 'sunset-theme-sidebar-options');
	add_settings_field('sidebar-gplus', 'Google+ Handler', 'sunset_sidebar_gplus', 'alecaddd_sunset', 'sunset-theme-sidebar-options');

	// Theme Support Options
	register_setting('sunset-theme-support', 'post_formats');
	register_setting('sunset-theme-support', 'custom_header');
	register_setting('sunset-theme-support', 'custom_background');

	add_settings_section('sunset-theme-support-options', 'Theme Options', 'sunset_theme_support_options', 'alecaddd_sunset_theme');

	add_settings_field('support-post-formats', 'Post Formats', 'sunset_support_post_formats', 'alecaddd_sunset_theme', 'sunset-theme-support-options');
	add_settings_field('support-header', 'Custom Header', 'sunset_support_header', 'alecaddd_sunset_theme', 'sunset-theme-support-options');
	add_settings_field('support-background', 'Custom Background', 'sunset_support_background', 'alecaddd_sunset_theme', 'sunset-theme-support-options');
}

// *** SIDEBAR PAGE FUNCTIONS ***
// Sidebar Title Print Function
function sunset_theme_sidebar_options(){
	echo 'Customize Your Sidebar Information';
}

// Sidebar Image Print Function
function sunset_sidebar_profile(){
	$picture = esc_attr( get_option('profile_picture') );
	if(empty($picture)){
		echo '<input type="button" class="button button-secondary" value="Upload Profile Picture" id="upload-button" />';
		echo '<input type="hidden" id="profile-picture" value="" name="profile_picture" />';
	} else {
		echo '<input type="button" class="button button-secondary" value="Replace Profile Picture" id="upload-button" />';
		echo '<input type="hidden" id="profile-picture" value="'.$picture.'" name="profile_picture" />
		<input type="button" class="button button-secondary" value="Remove" id="remove-picture" />';
	}
}

// Sidebar Name Print Function
function sunset_sidebar_name(){
	$firstName = esc_attr( get_option('first_name') );
	$lastName = esc_attr( get_option('last_name') );
	echo '<input type="text" name="first_name" value="'.$firstName.'" placeholder="First Name" /> <input type="text" name="last_name" value="'.$lastName.'" placeholder="Last Name" />';
}

// Sidebar Description Print Function
function sunset_sidebar_description(){
	$description = esc_attr( get_option('user_description') );
	echo '<input type="text" name="user_description" value="'.$description.'" placeholder="Description" /><p class="description">Input your Admin section description.</p>';
}

// Sidebar Twitter Print Function
function sunset_sidebar_twitter(){
	$twitter = esc_attr( get_option('twitter_handler') );
	echo '<input type="text" name="twitter_handler" value="'.$twitter.'" placeholder="Twitter Handler" /><p class="description">Input your Twitter username without the @ symbol.</p>';
}

// Sidebar Twitter Sanitize Function
function sunset_sanitize_twitter_callback($input){
	$output = sanitize_text_field($input);
	$output = str_replace('@', '', $output);
	return $output;
}

// Sidebar Facebook Print Function
function sunset_sidebar_facebook(){
	$facebook = esc_attr( get_option('facebook_handler') );
	echo '<input type="text" name="facebook_handler" value="'.$facebook.'" placeholder="Facebook Handler" />';
}

// Sidebar Google+ Print Function
function sunset_sidebar_gplus(){
	$gplus = esc_attr( get_option('gplus_handler') );
	echo '<input type="text" name="gplus_handler" value="'.$gplus.'" placeholder="Google+ Handler" />';
}

// Sidebar Template Page
function sunset_theme_sidebar_page()
{
	/** @noinspection PhpIncludeInspection */
	require_once( get_template_directory() . '/inc/templates/sunset-theme-sidebar.php' );
}

// *** SUPPORT PAGE FUNCTIONS ***
// Support Title Print Function
function sunset_theme_support_options(){
	echo '<div>Activate Theme Support Options</div>';
}

// Support Post Formats Print Function
function sunset_support_post_formats(){
	$options = get_option('post_formats');
	$formats = array('aside', 'gallery', 'link', 'image', 'quote', 'status', 'video', 'audio', 'chat');
	$output = '';
	foreach($formats as $format){
		$checked = (@$options[$format] == 1 ? 'checked' : '');
		$output .= '<label><input type="checkbox" id="'.$format.'" name="post_formats['.$format.']" value="1" '.$checked.' /> '.$format.'</label><br>';
	}
	echo $output;
}

// Support Header Print Function
function sunset_support_header(){
	$options = get_option('custom_header');
	$checked = (@$options == 1 ? 'checked' : '');
	echo '<label><input type="checkbox" id="custom_header" name="custom_header" value="1" '.$checked.' /> Activate the Custom Header</label>';
}

// Support Background Print Function
function sunset_support_background(){
	$options = get_option('custom_background');
	$checked = (@$options == 1 ? 'checked' : '');
	echo '<label><input type="checkbox" id="custom_background" name="custom_background" value="1" '.$checked.' /> Activate the Custom Background</label>';
}

// Support Template Page
function sunset_theme_support_page(){
	/** @noinspection PhpIncludeInspection */
	require_once( get_template_directory() . '/inc/templates/sunset-theme-support.php' );
}

// *** CUSTOM CSS PAGE FUNCTIONS ***
// Custom CSS Template Page
function sunset_theme_settings_page(){
	echo '<h1>Sunset Custom CSS</h1>';
}