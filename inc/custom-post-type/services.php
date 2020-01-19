<?php
class CPT_Services
{
	public function __construct()
	{
		add_action('init', [$this, 'registerServices']);
		add_filter('rwmb_meta_boxes', [$this, 'registerMetabox']);
	}

	public function registerServices()
	{
		register_post_type('services',
			[
				'labels' => [
					'name'                => 'Services',
					'singular_name'       => 'Service',
					'menu_name'           => 'Services',
					'all_items'           => 'All Services',
					'add_new'             => 'Add New',
					'add_new_item'        => 'Add New Service',
					'edit_item'           => 'Edit Service',
					'new_item'            => 'New Service',
					'view_item'           => 'View New Service',
					'search_items'        => 'Search New Service',
					'not_found'           => 'No Service Found',
					'not_found_in_trash'  => 'No Service found in trash',
					'parent'              => 'Parent Service',
				],
				'public'             => true,
				'publicly_queryable' => false,
				'show_ui'            => true,
				'show_in_menu'       => true,
				'query_var'          => true,
				'rewrite'            => ['slug' => 'services', 'with_front' => true],
				'capability_type'    => 'post',
				'has_archive'        => true,
				'hierarchical'       => false,
				'menu_position'      => 5,
				'menu_icon'          => 'dashicons-clipboard',
				'supports'           => ['title', 'thumbnail'] // 'title', 'editor', 'author', 'thumbnail', 'excerpt', 'comments'
			]
		);
	}

	public function registerMetabox($meta_boxes)
	{
		$prefix = 'mt_service_';
		$meta_boxes[] = [
			'title'      => 'Service Settings',
			'post_types' => 'services',
			'fields'     => [
				[
					'type' => 'heading',
					'name' => 'Description',
					'desc' => 'Describe about the service'
				],
				[
					'id'      => $prefix.'description',
					'type'    => 'wysiwyg',
					'raw'     => false,
					'options' => [
							'teeny'         => false,
							'media_buttons' => false,
					]
				],
				[
					'type' => 'heading',
					'name' => 'Benefits',
					'desc' => 'Describe about the benefits of this service'
				],
				[
					'id'      => $prefix.'benefits',
					'type'    => 'wysiwyg',
					'raw'     => false,
					'options' => [
						'teeny'         => false,
						'media_buttons' => false,
					]
				],
				// [
				// 	'type' => 'heading',
				// 	'name' => 'Shops that Offers the Services',
				// 	'desc' => 'Select which shop offers the services',
				// ],
				// [
				// 	'id'          => $prefix.'available_in',
				// 	'name'        => 'Also available in',
				// 	'type'        => 'post',
				// 	'post_type'   => 'branch',
				// 	'field_type'  => 'checkbox_list',
				// 	'query_args'  => [
				// 		'post_status'    => 'publish',
				// 		'posts_per_page' => - 1,
				// 		'orderby'        => 'title',
				// 		'order'          => 'ASC'
				// 	],
				// ],
			]
		];
		return $meta_boxes;
	}
}

return new CPT_Services();
