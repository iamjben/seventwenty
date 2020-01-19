<?php
/**
 * Configurations
 * 
 * All core configurations
 */
include_once 'inc/config/admin.php';
include_once 'inc/config/theme.php';

/**
 * Custom Post Type/Taxonomy
 * 
 * All custom post types/taxonomies configuration
 */
include_once 'inc/custom-post-type/base.php';
include_once 'inc/custom-post-type/brands.php';
include_once 'inc/custom-post-type/carousel.php';
include_once 'inc/custom-post-type/faqs.php';
include_once 'inc/custom-post-type/news.php';
include_once 'inc/custom-post-type/services.php';

/**
 * Utility functions
 * 
 * all utility functions
 */
include_once 'inc/utilities/wp-bootstrap-nav-walker.php';

/**
 * Custom functions
 * 
 * Include your custom functions
 */
include_once 'inc/custom-functions.php';
