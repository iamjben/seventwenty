<?php

function cpt_search_join($join) {
	//  Modify the search query with posts_where
	global $wpdb;
	if (is_search()) {
		$join .=' LEFT JOIN '.$wpdb->postmeta. ' ON '. $wpdb->posts . '.ID = ' . $wpdb->postmeta . '.post_id ';
	}
	return $join;
}
add_filter('posts_join', 'cpt_search_join');

function cpt_search_distinct($where) {
	// Prevent duplicates
	global $wpdb;
	if (is_search()) {
		return "DISTINCT";
	}
	return $where;
}
add_filter('posts_distinct', 'cpt_search_distinct');

function cpt_taxonomy_request($query_string) {
	// FIX - Solves issue with /page/2/ of taxonomy giving a 404 error
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

function cpt_taxonomy_get_posts($query) {
	// Taxonomy Pre-get posts
	if (is_tax() && $query->is_main_query() && !$query->is_feed() && !is_admin()) {
		$query->set( 'paged', str_replace( '/', '', get_query_var( 'page' ) ) );
	}
}
add_action('pre_get_posts', 'cpt_taxonomy_get_posts');

function cpt_flush_rewrite() {
	// Fix Permalink - Makes permalink work
	flush_rewrite_rules();
}
add_action('init', 'cpt_flush_rewrite');
