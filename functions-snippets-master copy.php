<?php

// =============================================================================
// FUNCTIONS.PHP
// -----------------------------------------------------------------------------
// Overwrite or add your own custom functions to Pro in this file.
// =============================================================================

// =============================================================================
// TABLE OF CONTENTS
// -----------------------------------------------------------------------------
//   01. Enqueue Parent Stylesheet
//   02. Additional Functions
// =============================================================================

// Enqueue Parent Stylesheet
// =============================================================================

add_filter( 'x_enqueue_parent_stylesheet', '__return_true' );



// Additional Functions
// =============================================================================

// Shortcode to create our ACF Pro repeater area in a Text area on X Theme Pro
// Profile is the name of the ACF field so change it to what the name is you have choosen
//Change the sub-field names to match your choosen subfields
//acf1 is the name for this shortcode so change it to reflect the content better
// In the div kw is my initials and again like profile is just helping it be unique to prevent clashes



// Shortcode to create our ACF Pro content

/**
 * ACF Pro repeater field shortcode
 *
 * I created this shortcode function because it didn't exist and it was being requested by others
 * I originally posted it here: https://support.advancedcustomfields.com/forums/topic/repeater-field-shortcode/
 *
 * @attr {string} field - (Required) the name of the field that contains a repeater sub group
 * @attr {string} sub_fields - (Required) a comma separated list of sub field names that are part of the field repeater group
 * @attr {string} post_id - (Optional) Specific post ID where your value was entered. Defaults to current post ID (not required). This can also be options / taxonomies / users / etc
 */
function my_acf_repeater($atts, $content='') {
  extract(shortcode_atts(array(
    "field" => null,
    "sub_fields" => null,
    "post_id" => null
  ), $atts));
  if (empty($field) || empty($sub_fields)) {
    // silently fail? is that the best option? idk
    return "";
  }
  $sub_fields = explode(",", $sub_fields);

  $_finalContent = '';
  if( have_rows($field, $post_id) ):
    while ( have_rows($field, $post_id) ) : the_row();

      $_tmp = $content;
      foreach ($sub_fields as $sub) {
        $subValue = get_sub_field(trim($sub));
        $_tmp = str_replace("%$sub%", $subValue, $_tmp);
      }
      $_finalContent .= do_shortcode( $_tmp );
    endwhile;
  else :
    $_finalContent = "$field does not have any rows";
  endif;
  return $_finalContent;
}
add_shortcode("acf_repeater", "my_acf_repeater");
add_shortcode("acf_sub_repeater", "my_acf_repeater");


// The code above works with the shortcode in X theme Pro
// [acf_repeater field="example-row" sub_fields="example-name,example-phone,example-image"]
//  User: %example-name%
//  Phone: %example-phone%
//  profile pic: %example-image%
//[/acf_repeater]
// Github - https://gist.github.com/FranciscoG/c393d9bc6e0a89cd79d1fd531eccf627








// remove version info from head and feeds
function complete_version_removal() {
	return '';
}
add_filter('the_generator', 'complete_version_removal');


// customize admin footer text
function custom_admin_footer() {
	echo '<a href="http://example.com/">Awesomeness by Kennyboy7</a>';
}
add_filter('admin_footer_text', 'custom_admin_footer');


//* ACF -- Add options page
if( function_exists('acf_add_options_page') ) {
     acf_add_options_page(array(
          'page_title' 	=> 'Admin Tutorials',
          'menu_title'	=> 'Admin Tutorials',
          'menu_slug' 	=> 'admin-tutorials',
          'capability'	=> 'edit_posts',
          'position'    => '58.998',
          'icon_url'    => 'dashicons-info',
          'redirect'	=> false
     ));
}

// add a favicon for your admin
function so_admin_favicon() {
	echo '<link rel="Shortcut Icon" type="image/x-icon" href="'.get_bloginfo('stylesheet_directory').'/images/favicon.png" />';
}
add_action('admin_head', 'so_admin_favicon');

/**
 * Sometimes it can be useful to remove the WordPress logo from the adminbar,
 * this function does that.
 */
add_action( 'wp_before_admin_bar_render', 'so_adminbar_remove_wplogo' );
function so_adminbar_remove_wplogo() {
	global $wp_admin_bar;

	$wp_admin_bar->remove_menu( 'wp-logo' );
}

// Hide WordPress Update message

	// source: http://wpdaily.co/top-10-snippets/
function so_wp_hide_update() {
    remove_action('admin_notices', 'update_nag', 3);
}
add_action('admin_menu','so_wp_hide_update');






// Long Method - you can select which widgets to remove, comment out the ones you'd like to keep
// source: http://sixrevisions.com/wordpress/how-to-customize-the-wordpress-admin-area/
function so_remove_wp_default_widgets() {
	unregister_widget( 'WP_Widget_Pages' );
	unregister_widget( 'WP_Widget_Calendar' );
	unregister_widget( 'WP_Widget_Archives' );
	if ( get_option( 'link_manager_enabled' ) )
		unregister_widget( 'WP_Widget_Links' );
	unregister_widget( 'WP_Widget_Meta' );
	unregister_widget( 'WP_Widget_Search' );
	unregister_widget( 'WP_Widget_Text' );
	unregister_widget( 'WP_Widget_Categories' );
	unregister_widget( 'WP_Widget_Recent_Posts' );
	unregister_widget( 'WP_Widget_Recent_Comments' );
	unregister_widget( 'WP_Widget_RSS' );
	unregister_widget( 'WP_Widget_Tag_Cloud' );
	unregister_widget( 'WP_Nav_Menu_Widget' );
}
add_action( 'widgets_init', 'so_remove_wp_default_widgets', 1);



/**
 * Remove the default welcome dashboard message
 * Creates a new custom message
 */
remove_action( 'welcome_panel', 'wp_welcome_panel' );

/**
 * Custom welcome panel function
 *
 * @access      public
 * @since       1.0
 * @return      void
 */
function wpex_wp_welcome_panel() { ?>

	<div class="custom-welcome-panel-content">
		<h1><?php the_field( 'dashboard_headline', 'option' ); ?></h1>
		<p class="about-description"><?php the_field( 'opening_paragraph', 'option' ); ?></p>
		<div class="welcome-panel-column-container">
			<div class="welcome-panel-column">
				<h4><?php _e( "Let's Get Started" ); ?></h4>
				<a class="button button-primary button-hero load-customize hide-if-no-customize" href="http://your-website.com"><?php _e( 'Get In Touch !' ); ?></a>
					<p class="hide-if-no-customize"><?php printf( __( 'or, <a href="%s">edit your site settings</a>' ), admin_url( 'options-general.php' ) ); ?></p>
			</div><!-- .welcome-panel-column -->
			<div class="welcome-panel-column">
				<h4><?php _e( 'Lets cause chaos' ); ?></h4>
				<ul>
				<?php if ( 'page' == get_option( 'show_on_front' ) && ! get_option( 'page_for_posts' ) ) : ?>
					<li><?php printf( '<a href="%s" class="welcome-icon welcome-edit-page">' . __( 'Edit your front page' ) . '</a>', get_edit_post_link( get_option( 'page_on_front' ) ) ); ?></li>
					<li><?php printf( '<a href="%s" class="welcome-icon welcome-add-page">' . __( 'Add additional pages' ) . '</a>', admin_url( 'post-new.php?post_type=page' ) ); ?></li>
				<?php elseif ( 'page' == get_option( 'show_on_front' ) ) : ?>
					<li><?php printf( '<a href="%s" class="welcome-icon welcome-edit-page">' . __( 'Edit your front page' ) . '</a>', get_edit_post_link( get_option( 'page_on_front' ) ) ); ?></li>
					<li><?php printf( '<a href="%s" class="welcome-icon welcome-add-page">' . __( 'Add additional pages' ) . '</a>', admin_url( 'post-new.php?post_type=page' ) ); ?></li>
					<li><?php printf( '<a href="%s" class="welcome-icon welcome-write-blog">' . __( 'Add a blog post' ) . '</a>', admin_url( 'post-new.php' ) ); ?></li>
				<?php else : ?>
					<li><?php printf( '<a href="%s" class="welcome-icon welcome-write-blog">' . __( 'Write your first blog post' ) . '</a>', admin_url( 'post-new.php' ) ); ?></li>
					<li><?php printf( '<a href="%s" class="welcome-icon welcome-add-page">' . __( 'Add an About page' ) . '</a>', admin_url( 'post-new.php?post_type=page' ) ); ?></li>
				<?php endif; ?>
					<li><?php printf( '<a href="%s" class="welcome-icon welcome-view-site">' . __( 'View your site' ) . '</a>', home_url( '/' ) ); ?></li>
				</ul>
			</div><!-- .welcome-panel-column -->
			<div class="welcome-panel-column welcome-panel-last">
				<h4><?php _e( 'More Actions' ); ?></h4>
				<ul>
					<li><?php printf( '<div class="welcome-icon welcome-widgets-menus">' . __( 'Manage <a href="%1$s">widgets</a> or <a href="%2$s">menus</a>' ) . '</div>', admin_url( 'widgets.php' ), admin_url( 'nav-menus.php' ) ); ?></li>
					<li><?php printf( '<a href="%s" class="welcome-icon welcome-comments">' . __( 'Turn comments on or off' ) . '</a>', admin_url( 'options-discussion.php' ) ); ?></li>
					<li><?php printf( '<a href="%s" class="welcome-icon welcome-learn-more">' . __( 'Learn more about getting started' ) . '</a>', __( 'http://codex.wordpress.org/First_Steps_With_WordPress' ) ); ?></li>
				</ul>
			</div><!-- .welcome-panel-column welcome-panel-last -->
		</div><!-- .welcome-panel-column-container -->
	<div><!-- .custom-welcome-panel-content -->

<?php }
add_action( 'welcome_panel', 'wpex_wp_welcome_panel' );



/* One Column Dashboard
 *
 * source: http://wordpress.stackexchange.com/a/29307/2015
 */

function kb7_layout_columns( $columns ) {
    $columns['dashboard'] = 1;
    return $columns;
}
add_filter( 'screen_layout_columns', 'kb7_layout_columns' );
function kb7_layout_dashboard() {
    return 1;
}
add_filter( 'get_user_option_screen_layout_dashboard', 'kb7_layout_dashboard' );



/**
 * Function to remove the emojis added to WP 4.2
 * function and filter taken from Classic Smilies plugin by Samuel Wood (Otto)
 *
 * @source: https://wordpress.org/plugins/classic-smilies/
 */
add_action( 'init', 'so_remove_emojis', 1 );

function so_remove_emojis() {
	// disable any and all mention of emoji's
	remove_action( 'wp_head', 'print_emoji_detection_script', 7 );
	remove_action( 'admin_print_scripts', 'print_emoji_detection_script' );
	remove_action( 'wp_print_styles', 'print_emoji_styles' );
	remove_action( 'admin_print_styles', 'print_emoji_styles' );
	remove_filter( 'the_content_feed', 'wp_staticize_emoji' );
	remove_filter( 'comment_text_rss', 'wp_staticize_emoji' );
	remove_filter( 'wp_mail', 'wp_staticize_emoji_for_email' );
	add_filter( 'tiny_mce_plugins', 'so_remove_tinymce_emoji' );
}
// filter function used to remove the tinymce emoji plugin
function so_remove_tinymce_emoji( $plugins ) {
	return array_diff( $plugins, array( 'wpemoji' ) );
}
// Remove DNS prefetch s.w.org (used for emojis)
add_filter( 'emoji_svg_url', '__return_false' );



// If a user tries to log in with the username Admin it redirects to a Rick Astley Video on YouTube

add_action( 'authenticate', 'rickroll_check_admin_login', 1, 2);
function rickroll_check_admin_login( $login, $username ) {
	if ( 'admin' == $username ) {
		wp_redirect( 'https://www.youtube.com/watch?v=dQw4w9WgXcQ' );
		exit;
	}
}


// Removes unused bits from user details area and adds FB etc
function extra_contact_info($contactmethods) {
unset($contactmethods['aim']);
unset($contactmethods['yim']);
unset($contactmethods['jabber']);
$contactmethods['facebook'] = 'Facebook';
$contactmethods['twitter'] = 'Twitter';
$contactmethods['linkedin'] = 'LinkedIn';

return $contactmethods;
}
add_filter('user_contactmethods', 'extra_contact_info');
/* END Custom User Contact Info */

// Change the default avatar to whatever you want

add_filter( 'avatar_defaults', 'kb7_new_gravatar' );
function kb7_new_gravatar ($avatar_defaults) {
$myavatar = 'http://example.com/wp-content/uploads/2017/01/wpb-default-gravatar.png';
$avatar_defaults[$myavatar] = "Default Gravatar";
return $avatar_defaults;
}


// Hide the error message if you get the username or password wrong at login, helps prevent hackers
// guessing which one the got right.

function kb7_wordpress_errors(){
  return 'Something is wrong!';
}
add_filter( 'login_errors', 'kb7_wordpress_errors' );


// Creates a shortcode to display the number of registered users of the site. [user_count]

// Function to return user count
function kb7_user_count() {
$usercount = count_users();
$result = $usercount['total_users'];
return $result;
}
// Creating a shortcode to display user count
add_shortcode('user_count', 'kb7_user_count');


// Enable shortcodes in text widgets
add_filter('widget_text','do_shortcode');

// Stop Wordpress creating a link to an images

function kb7_imagelink_setup() {
    $image_set = get_option( 'image_default_link_type' );

    if ($image_set !== 'none') {
        update_option('image_default_link_type', 'none');
    }
}
add_action('admin_init', 'kb7_imagelink_setup', 10);

// Lists all the external Javascripts as a shortcode [pluginhandles]

function kb7_display_pluginhandles() {
$wp_scripts = wp_scripts();
$handlename .= "<ul>";
    foreach( $wp_scripts->queue as $handle ) :
      $handlename .=  '<li>' . $handle .'</li>';
    endforeach;
$handlename .= "</ul>";
return $handlename;
}

add_shortcode( 'pluginhandles', 'kb7_display_pluginhandles');



// Deregister the comment-reply function to help speed up page loading

function clean_header(){
	wp_deregister_script( 'comment-reply' );
	}

add_action('init','clean_header');



// Remove jQuery Migrate Script from header
function kb7_stop_loading_wp_embed() {
	if (!is_admin()) {
		wp_deregister_script('wp-embed');

	}
}
add_action('init', 'kb7_stop_loading_wp_embed');


// Remove the REST API endpoint.
remove_action( 'rest_api_init', 'wp_oembed_register_route' );

// Turn off oEmbed auto discovery.
add_filter( 'embed_oembed_discover', '__return_false' );

// Don't filter oEmbed results.
remove_filter( 'oembed_dataparse', 'wp_filter_oembed_result', 10 );

// Remove oEmbed discovery links.
remove_action( 'wp_head', 'wp_oembed_add_discovery_links' );

// Remove oEmbed-specific JavaScript from the front-end and back-end.
remove_action( 'wp_head', 'wp_oembed_add_host_js' );

// Remove all embeds rewrite rules.
add_filter( 'rewrite_rules_array', 'disable_embeds_rewrites' );



/**
 * Add Gravity Forms capabilities to a role.
 * Runs when this theme is activated. if you change it when activate you need to deactivate and reactivate the theme
 *
 * @access public
 * @return void
 */
function grant_gforms_editor_access() {

  $role = get_role( 'editor' );
  $role->add_cap( 'gravityforms_view_entries');
    // to change the capability add another line ie
  $role->add_cap( 'gravityforms_edit_entries');
  // $role->add_cap( 'gform_full_access' ); this give total access, not advisable
  // amend the code below to remove the roles if you change above

}
// Tie into the 'after_switch_theme' hook
add_action( 'after_switch_theme', 'grant_gforms_editor_access' );

/**
 * Remove Gravity Forms capabilities from Editor role.
 * Runs when this theme is deactivated (in favor of another).
 *
 * @access public
 * @return void
 */
function revoke_gforms_editor_access() {

  $role = get_role( 'editor' );
  $role->remove_cap( 'gravityforms_view_entries');
    // to change the capability add another line ie
  $role->remove_cap( 'gravityforms_edit_entries');

  // $role->remove_cap( 'gform_full_access' );
}
// Tie into the 'switch_theme' hook
add_action( 'switch_theme', 'revoke_gforms_editor_access' );

// Below code is from https://wpabsolute.com

// A simple login form shortcode to display a login form on any page of your site.
// This is using a Gravity Form
// Usage shortcode [login_form redirect="https://wpabsolute.com" label_username="Your Username" label_password="Your Password" label_remember="Remember?" label_log_in="Log In Now" remember_checked="false"]
function kw_login_form_shortcode( $atts, $content = null ) {
$a = shortcode_atts( array(
'redirect' => ( is_ssl() ? 'https://' : 'http://' ) . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'],
'label_username' => __( 'Email' ),
'label_password' => __( 'Password' ),
'label_remember' => __( 'Remember Me' ),
'label_log_in' => __( 'Log In' ),
'remember_checked' => true,
), $atts );

$args = array(
'echo' => false,
'remember' => true,
'redirect' => esc_url( $a['redirect'] ),
'label_username' => esc_attr( $a['label_username'] ),
'label_password' => esc_attr( $a['label_password'] ),
'label_remember' => esc_attr( $a['label_remember'] ),
'label_log_in' => esc_attr( $a['label_log_in'] ),
'value_remember' => $a['remember_checked']
);
return wp_login_form( $args );
}
add_shortcode( 'login_form', 'kw_login_form_shortcode' );



// Disable admin toolbar for all users except administrators on the front-end
function kw_hide_admin_bar($content) {
// Returns true if user is administrator, otherwise false.
return ( current_user_can( 'administrator' ) ) ? $content : false;
}
add_filter( 'show_admin_bar' , 'kw_hide_admin_bar');



// Disable admin toolbar for all users except administrators and editors on the frontend
function kwkw_hide_admin_bar($content) {
// Returns true if user is administrator or editor, otherwise false.
return ( current_user_can( 'edit_pages' ) ) ? $content : false;
}
add_filter( 'show_admin_bar' , 'kwkw_hide_admin_bar');



// Block non-administrators from accessing the WordPress back-end
function kw_block_users_backend() {
	if ( is_admin() && ! current_user_can( 'administrator' ) && ! wp_doing_ajax() ) {
		wp_redirect( home_url() );
		exit;
	}
}
add_action( 'init', 'kw_block_users_backend' );

/**
 * Remove ancient Custom Fields Metabox because it's slow and most often useless anymore
 * ref: https://core.trac.wordpress.org/ticket/33885
 */
function jb_remove_post_custom_fields_now() {
	foreach ( get_post_types( '', 'names' ) as $post_type ) {
		remove_meta_box( 'postcustom' , $post_type , 'normal' );
	}
}
add_action( 'admin_menu' , 'jb_remove_post_custom_fields_now' );


/**
 * Gravity Forms Domain
 *
 * Adds a notice at the end of admin email notifications
 * specifying the domain from which the email was sent.
 *
 * @param array $notification
 * @param object $form
 * @param object $entry
 * @return array $notification
 */
function ea_gravityforms_domain( $notification, $form, $entry ) {
	if( $notification['name'] == 'Admin Notification' ) {
		$notification['message'] .= 'Sent from ' . home_url();
	}
	return $notification;
}
add_filter( 'gform_notification', 'ea_gravityforms_domain', 10, 3 );

// =======================================================================

// Set custom styles on the acf entry fields

add_action('acf/input/admin_head', 'my_acf_admin_head');

	function my_acf_admin_head() {
?>
<style type="text/css">

    .acf-flexible-content .layout .acf-fc-layout-handle {
        /*background-color: #00B8E4;*/
        background-color: #40d69a;
        color: #eee;

    }

    .acf-repeater.-row > table > tbody > tr > td,
    .acf-repeater.-block > table > tbody > tr > td {
        border-top: 2px solid #202428;
    }

    .acf-repeater .acf-row-handle {
        vertical-align: top !important;
        padding-top: 16px;
    }

    .acf-repeater .acf-row-handle span {
        font-size: 20px;
        font-weight: bold;
        color: #202428;
    }

    .imageUpload img {
        width: 75px;
    }

    .acf-repeater .acf-row-handle .acf-icon.-minus {
        top: 30px;
    }

		.color_swatch:before {
		  color: #FFF;
		  content: "";
		  width: 50px;
		  height: 50px;
		  display: inline-block;
		  position: relative;
		  top: 6px;
		  margin-right: 5px;
		  border: 1px solid #ddd;
		}

		.red:before {
		  background-color: #9e0347;
		}

		.gray:before {
		  background-color: #5a5a59;
		}

</style>
<?php
}
