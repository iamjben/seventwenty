<?php
/**
 * Modify the search query with posts_where
 */
function cpt_search_join($join) {
	global $wpdb;
	if (is_search()) {
		$join .=' LEFT JOIN '.$wpdb->postmeta. ' ON '. $wpdb->posts . '.ID = ' . $wpdb->postmeta . '.post_id ';
	}
	return $join;
}
add_filter('posts_join', 'cpt_search_join');

/**
 * Prevent duplicates
 */
function cpt_search_distinct($where) {
	global $wpdb;
	if (is_search()) {
		return "DISTINCT";
	}
	return $where;
}
add_filter('posts_distinct', 'cpt_search_distinct');

/**
 * FIX - Solves issue with /page/2/ of taxonomy giving a 404 error
 */
function cpt_taxonomy_request($query_string) {
	if (isset( $query_string['page'])) {
		if ('' != $query_string['page']) {
			if (isset( $query_string['name'])) {
				unset( $query_string['name']);
			}
		}
	}
	return $query_string;
}
add_filter('request', 'cpt_taxonomy_request');

/**
 * Taxonomy Pre-get posts
 */
function cpt_taxonomy_get_posts($query) {
	if (is_tax() && $query->is_main_query() && !$query->is_feed() && !is_admin()) {
		$query->set( 'paged', str_replace( '/', '', get_query_var( 'page' ) ) );
	}
}
add_action('pre_get_posts', 'cpt_taxonomy_get_posts');

/**
 * Fix Permalink - Makes permalink work
 */
function cpt_flush_rewrite() {
	flush_rewrite_rules();
}
add_action('init', 'cpt_flush_rewrite');
