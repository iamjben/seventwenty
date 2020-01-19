<?php
/**
* Sets up theme defaults and registers support for various WordPress features.
*
* Note that this function is hooked into the after_setup_theme hook, which
* runs before the init hook. The init hook is too late for some features, such
* as indicating support for post thumbnails.
*/
function theme_setup() {
  add_theme_support('automatic-feed-links');
  add_theme_support('title-tag');
  add_theme_support('post-thumbnails');
  add_theme_support('html5', [
    'comment-form',
    'comment-list',
    'caption',
  ]);

  // Make theme available for translation.
  // Translations can be filed in the /languages/ directory.
  // If you're building a theme based on seventwenty, use a find and replace
  // to change 'seventwenty' to the name of your theme in all the template files.
  load_theme_textdomain('seventwenty', get_template_directory() . '/languages');
}
add_action('after_setup_theme', 'theme_setup');

/**
 * Enqueue scripts and styles.
 */
function theme_assets() {
  $path = get_stylesheet_directory_uri().'/dist/';

  wp_enqueue_style('vendor-theme', $path. 'vendor.min.css', array(), '', 'all');
  wp_enqueue_style('css-theme', $path. 'theme.min.css', array(), '', 'all');

  wp_enqueue_script('jquery');
  wp_enqueue_script('vendor-theme', $path. 'vendor.min.js', false, '', true);
  wp_enqueue_script('js-theme', $path. 'theme.min.js', false, '', true);

  wp_localize_script('js-theme', 'wpajax', array(
    'ajax_url'   => admin_url('admin-ajax.php'),
    'ajax_nonce' => wp_create_nonce('wp_nonce')
  ));

  if (is_singular() && comments_open() && get_option('thread_comments')) {
    wp_enqueue_script('comment-reply');
  }
}
add_action('wp_enqueue_scripts', 'theme_assets');

/**
 * Set the content width in pixels, based on the theme's design and stylesheet.
 * Priority 0 to make it available to lower priority callbacks.
 */
function theme_content_width() {
  $GLOBALS['content_width'] = apply_filters('theme_content_width', 640);
}
add_action('after_setup_theme', 'theme_content_width', 0);

/**
 * Register widget area.
 */
function theme_widgets() {
  register_sidebar(array(
    'name'          => esc_html__( 'Sidebar', 'seventwenty' ),
    'id'            => 'sidebar-1',
    'description'   => esc_html__( 'Add widgets here.', 'seventwenty' ),
    'before_widget' => '<section id="%1$s" class="widget %2$s">',
    'after_widget'  => '</section>',
    'before_title'  => '<h2 class="widget-title">',
    'after_title'   => '</h2>',
  ));
}
add_action('widgets_init', 'theme_widgets');

/**
 * Remove on head - cleanup
 */
function theme_cleanup() {
  // Remove header links
  remove_action('wp_head', 'feed_links_extra', 3);                    // Category Feeds
  remove_action('wp_head', 'feed_links', 2);                          // Post and Comment Feeds
  remove_action('wp_head', 'rsd_link');                               // EditURI link
  remove_action('wp_head', 'wlwmanifest_link');                       // Windows Live Writer
  remove_action('wp_head', 'index_rel_link');                         // index link
  remove_action('wp_head', 'parent_post_rel_link', 10, 0);            // previous link
  remove_action('wp_head', 'start_post_rel_link', 10, 0);             // start link
  remove_action('wp_head', 'adjacent_posts_rel_link_wp_head', 10, 0); // Links for Adjacent Posts
  remove_action('wp_head', 'wp_generator');                           // WP version
  // Remove Emoji Scripts
  remove_action('wp_head', 'print_emoji_detection_script', 7);
  remove_action('admin_print_scripts', 'print_emoji_detection_script');
  remove_action('wp_print_styles', 'print_emoji_styles');
  remove_action('admin_print_styles', 'print_emoji_styles');
}
add_action('init', 'theme_cleanup');

/**
 * Remove Style/Script version
 */
function remove_versions($src) {
  if(strpos($src, 'ver='.get_bloginfo('version')))
    $src = remove_query_arg('ver', $src);
  return $src;
}
add_filter('style_loader_src', 'remove_versions', 9999);
add_filter('script_loader_src', 'remove_versions', 9999);

/**
 * Remove image dimensions
 */
function remove_image_dimensions($html) {
  $html = preg_replace( '/(width|height)=\"\d*\"\s/', "", $html );
  return $html;
}
add_filter('post_thumbnail_html', 'remove_image_dimensions', 10);
add_filter('image_send_to_editor', 'remove_image_dimensions', 10);

/**
 * Remove <p> tag on image on content
 */
function remove_p_tags_on_image($content) {
  return preg_replace('/<p>\s*(<a .*>)?\s*(<img .* \/>)\s*(<\/a>)?\s*<\/p>/iU', '\1\2\3', $content);
}
add_filter('the_content', 'remove_p_tags_on_image');
