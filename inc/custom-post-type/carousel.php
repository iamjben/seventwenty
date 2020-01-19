<?php
class CPT_Carousel
{
	public function __construct() {
		add_action('init', [$this, 'registerCarousel']);
		add_filter('rwmb_meta_boxes', [$this, 'registerMetabox']);
		add_filter('manage_carousel_posts_columns', [$this, 'adminTableHeadings']);
		add_action('manage_carousel_posts_custom_column', [$this, 'adminTableColumns'], 10, 2);
	}

	public function registerCarousel()
	{
		register_post_type('carousel',
			[
				'labels' => [
					'name'                => 'Carousel',
					'singular_name'       => 'Carousel',
					'menu_name'           => 'Home Carousel',
					'all_items'           => 'All Carousel Items',
					'add_new'             => 'Add New',
					'add_new_item'        => 'Add New Carousel',
					'edit_item'           => 'Edit Carousel',
					'new_item'            => 'New Carousel',
					'view_item'           => 'View Carousel',
					'search_items'        => 'Search Carousels',
					'not_found'           => 'No Carousel Found',
					'not_found_in_trash'  => 'No Carousel found in trash',
					'parent'              => 'Parent Carousel',
				],
				'public'             => true,
				'publicly_queryable' => false,
				'show_ui'            => true,
				'show_in_menu'       => true,
				'query_var'          => false,
				'rewrite'            => ['slug' => 'slider', 'with_front' => true],
				'capability_type'    => 'post',
				'has_archive'        => false,
				'hierarchical'       => false,
				'menu_position'      => 5,
				'menu_icon'          => 'dashicons-format-gallery',
				'supports'           => ['title', 'page-attributes'] // 'title', 'editor', 'author', 'thumbnail', 'excerpt', 'comments', 'page-attributes'
			]
		);
	}

	public function registerMetabox($meta_boxes)
	{
		$prefix = 'mb_carousel_';
		$meta_boxes[] = [
			'title'      => 'Carousel Settings',
			'post_types' => 'carousel',
			'fields'     => [
				[
					'type' => 'heading',
					'name' => 'Carousel Link',
					'desc' => 'Set link to your slider item',
				],
				[
					'id'   => $prefix.'link',
					'name' => 'Link',
					'type' => 'text',
					'placeholder' => 'ex. https://site-url.com'
				],
				[
					'type' => 'heading',
					'name' => 'Image - Desktop',
					'desc' => 'This image will load for desktop',
				],
				[
					'id'   => $prefix.'image_a',
					'type' => 'image_advanced',
					'max_file_uploads' => 1
				],
				[
					'type' => 'heading',
					'name' => 'Image - Mobile/Tablet',
					'desc' => 'This image will load for mobile and tablet devices',
				],
				[
					'id'   => $prefix.'image_b',
					'type' => 'image_advanced',
					'max_file_uploads' => 1
				]
			],
		];
		return $meta_boxes;
	}

	public function adminTableHeadings($columns)
	{
		$columns = [
			'cb'       => $columns['cb'],
			'title'    => __('Title'),
			'order'    => __('Order Position '),
			'image'    => __('Image'),
			'date'     => __('Date')
		];
		return $columns;
	}

	public function adminTableColumns($column, $post_id)
	{
		$post = get_post($post_id);
		switch ($column)
		{
			case 'image':
				$image = rwmb_meta('mt_slider_image', 'size=full', $post_id);
				if (!empty($image)) {
					foreach ($image as $i) {
						$photo = $i['url'];
						echo '<img src="'.$photo.'" width="150">';
					}
				} else {
					echo 'No image assigned yet';
				}
			break;
			case 'order':
				echo $this->ordinal($post->menu_order);
			break;
		}
	}
			
	public function getSliders($post_per_page = -1)
	{
		$args = [
			'post_type'      => 'slider',
			'posts_per_page' => $posts_per_page,
			'orderby'        => 'date',
			'order'          => 'DESC',
			'post_status'    => 'publish'
		];
		$sliders = new WP_Query($args);
		return $sliders;
	}

	private function ordinal($number)
	{
		$ends = array('th','st','nd','rd','th','th','th','th','th','th');
		if ((($number % 100) >= 11) && (($number%100) <= 13))
			return $number. 'th';
		else
			return $number. $ends[$number % 10];
	}
}

return new CPT_Carousel();
