<?php
class CPT_Faqs
{
	public function __construct()
	{
		add_action('init', [$this, 'registerFaqs']);
		add_filter('rwmb_meta_boxes', [$this, 'registerMetabox']);
	}

	public function registerFaqs()
	{
		register_post_type('faqs',
			[
				'labels' => [
					'name'                => 'FAQ',
					'singular_name'       => 'FAQ',
					'menu_name'           => 'FAQs',
					'all_items'           => 'All FAQs',
					'add_new'             => 'Add New',
					'add_new_item'        => 'Add New FAQ',
					'edit_item'           => 'Edit FAQ',
					'new_item'            => 'New FAQ',
					'view_item'           => 'View FAQ',
					'search_items'        => 'Search FAQ',
					'not_found'           => 'No FAQ Found',
					'not_found_in_trash'  => 'No FAQ found in trash',
					'parent'              => 'Parent FAQ',
				],
				'public'             => true,
				'publicly_queryable' => false,
				'show_ui'            => true,
				'show_in_menu'       => true,
				'query_var'          => true,
				'rewrite'            => ['slug' => 'faqs', 'with_front' => true],
				'capability_type'    => 'post',
				'has_archive'        => true,
				'hierarchical'       => false,
				'menu_position'      => 5,
				'menu_icon'          => 'dashicons-yes',
				'supports'           => ['title'] // 'title', 'editor', 'author', 'thumbnail', 'excerpt', 'comments'
			]
		);
	}

	public function registerMetabox($meta_boxes)
	{
		$prefix = 'mt_faq_';
		$meta_boxes[] = [
			'title'      => 'FAQ Settings',
			'post_types' => 'faqs',
			'fields'     => [
				[
					'type' => 'heading',
					'name' => 'FAQ Answer',
					'desc' => 'Set FAQ answer',
				],
				[
					'name' => 'Answer',
					'id'   => $prefix.'answer',
					'type' => 'textarea',
					'rows' => 10,
				],
			]
		];
		return $meta_boxes;
	}

	public function getFaqs()
	{
		$args = [
			'post_type'      => 'faqs',
			'posts_per_page' => -1,
			'orderby'        => 'date',
			'order'          => 'ASC',
			'post_status'    => 'publish'
		];
		$posts = new WP_Query($args);
		return $posts;
	}
}

return new CPT_Faqs();
