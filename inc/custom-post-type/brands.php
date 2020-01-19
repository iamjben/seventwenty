<?php
class CPT_Brands
{
	public function __construct()
	{
		add_action('init', [$this, 'registerBrands']);
		add_filter('post_type_link', [$this, 'registerPostLink'], 1, 3);
		add_filter('rwmb_meta_boxes', [$this, 'registerMetabox']);
		add_filter('manage_brands_posts_columns', [$this, 'adminTableHeadings']);
		add_action('manage_brands_posts_custom_column', [$this, 'adminTableColumns'], 10, 2);
	}
	
	public function registerBrands()
	{
		register_post_type('brands',
			[
				'labels' => [
					'name'                => 'Brands',
					'singular_name'       => 'Brand',
					'menu_name'           => 'Brands',
					'all_items'           => 'All Brands',
					'add_new'             => 'Add New',
					'add_new_item'        => 'Add New Brand',
					'edit_item'           => 'Edit Brand',
					'new_item'            => 'New Brand',
					'view_item'           => 'View Brand',
					'search_items'        => 'Search Brand',
					'not_found'           => 'No Brand Found',
					'not_found_in_trash'  => 'No Brand found in trash',
					'parent'              => 'Parent Brand',
				],
				'public'             => true,
				'publicly_queryable' => true,
				'show_ui'            => true,
				'show_in_menu'       => true,
				'query_var'          => true,
				'rewrite'            => ['slug' => 'brands', 'with_front' => true],
				'capability_type'    => 'post',
				'has_archive'        => true,
				'hierarchical'       => false,
				'menu_position'      => 5,
				'menu_icon'          => 'dashicons-tag',
				'supports'           => ['title'] // 'title', 'editor', 'author', 'thumbnail', 'excerpt', 'comments'
			]
		);

		register_taxonomy(
			'brand-category',
			['brands'],
			[
				'labels' => [
					'name'              => 'Categories',
					'singular_name'     => 'Categories',
					'search_items'      => 'Search Categories',
					'all_items'         => 'All Categories',
					'parent_item'       => 'Parent Category',
					'parent_item_colon' => 'Parent Category:',
					'edit_item'         => 'Edit Category', 
					'update_item'       => 'Update Category',
					'add_new_item'      => 'Add New Category',
					'new_item_name'     => 'New Category Name',
					'menu_name'         => 'Categories',
				],
				'public' => true,
				'show_in_nav_menus' => true,
				'show_ui' => true, // Set value to true if you want to add/modify taxonomy
				'publicly_queryable' => true,
				'exclude_from_search' => false,
				'hierarchical' => false,
				'query_var' => true,
				'rewrite' => ['slug' => 'brand-category/%brand-category%', 'with_front' => false],
			]
		);

	}

	public function registerPostLink($post_link, $id = 0)
	{
		if (strpos('%brand-category%', $post_link) === 'FALSE') {
			return $post_link;
		}
		$post = get_post($id);
		if (!is_object($post) || $post->post_type != 'brands') {
			return $post_link;
		}
		// this calls the term to be added to the URL
		$terms = wp_get_object_terms($post->ID, 'brand-category');
		if (!$terms) {
			return str_replace('brand-category/%brand-category%/', '', $post_link);
		}
		return str_replace('%brand-category%', $terms[0]->slug, $post_link);
	}

	public function registerMetabox($meta_boxes)
	{
		$prefix = 'mt_brand_';
		$meta_boxes[] = [
			'title'      => 'Brand Settings',
			'post_types' => 'brands',
			'fields'     => [
				[
					'type' => 'heading',
					'name' => 'Images',
					'desc' => 'Set photos for this brand - First photo will be the thumbnail preview',
				],
				[
					'id'   => $prefix.'images',
					'type' => 'image_advanced',
					'max_file_uploads' => 8,
				],
				[
					'type' => 'heading',
					'name' => 'About the Brand',
					'desc' => 'Set information about the brand',
				],
				[
					'name' => 'Description',
					'id'   => $prefix.'description',
					'type' => 'textarea',
					'rows' => 10,
				],
				[
					'name' => 'Website Link',
					'id'   => $prefix.'link',
					'type' => 'text',
					'size' => 80,
					'placeholder' => 'Ex. http://website.com'
				],
				// [
				// 	'type' => 'heading',
				// 	'name' => 'Filtering Options',
				// 	'desc' => 'Set gender, style and origin for filtering',
				// ],
				// [
				// 	'name'        => 'Category',
				// 	'id'          => $prefix.'category',
				// 	'placeholder' => 'Select category',
				// 	'type'        => 'taxonomy',
				// 	'taxonomy'    => 'brand-category',
				// 	'field_type'  => 'checkbox_list',
				// 	'query_args'  => [
				// 		'orderby' => 'count',
				// 		'hide_empty' => 0
				// 	],
				// ],
				// [
				// 	'name' => 'Gender',
				// 	'id'   => $prefix.'gender',
				// 	'type' => 'select',
				// 	'placeholder' => 'Select Gender',
				// 	'options' => $this->getBrandFilterOptions('genders'),
				// ],
				// [
				// 	'name' => 'Style',
				// 	'id'   => $prefix.'style',
				// 	'type' => 'checkbox_list',
				// 	'placeholder' => 'Select Style',
				// 	'options' => $this->getBrandFilterOptions('styles'),
				// ],
				// [
				// 	'name' => 'Origin',
				// 	'id'   => $prefix.'origin',
				// 	'type' => 'checkbox_list',
				// 	'placeholder' => 'Select Style',
				// 	'options' => $this->getBrandFilterOptions('origins'),
				// ]
			]
		];
		return $meta_boxes;
	}

	public function adminTableHeadings($columns)
	{
		$columns = [
			'cb'       => $columns['cb'],
			'title'    => __('Title'),
			'image'    => __('Image'),
			'category' => __('Category'),
			'date'     => __('Date')
		];
		return $columns;
	}

	public function adminTableColumns($column, $post_id)
	{
		switch ($column)
		{
			case 'image':
				$images = rwmb_meta('mt_brand_images', 'size=full', $post_id);
				$image = reset($images);
				echo empty($images) ? 'Image not set' : '<img src="'.$image['url'].'" width="150">';
			break;

			case 'category' :
				$categories = rwmb_meta('mt_brand_category', '', $post_id);
				if (!empty($categories)) {
					$result = '';
					foreach ($categories as $category) {
						$result .= $category->name.', ';
					}
					echo substr($result, 0, -2);
				} else {
					echo 'Category not set';
				}
			break;
		}
	}

	public function getBrandsByCategory($category, $posts_per_page = 30)
	{
		$args = [
			'post_type'      => 'brands',
			'posts_per_page' => $posts_per_page,
			'paged'          => @$paged,
			'orderby'        => 'title',
			'order'          => 'ASC',
			'tax_query' => [
				'relation' => 'AND',
				[
					'taxonomy' => 'brand-category',
					'field'    => 'slug',
					'terms'    => $category
				]
			]
		];
		$brands = new WP_Query($args);
		return $brands;
	}
	
	public function getBrand($post_id)
	{
		$images      = rwmb_meta('mt_brand_images', 'size=full', $post_id);
		$thumbnail_1 = reset($images); // Gets first photo in the gallery
		$thumbnail   = empty($images) ? get_bloginfo('template_url').'/dist/img/no-image.jpg': $thumbnail_1['url'];

		$data = [
			'name'        => get_the_title(),
			'thumbnail'   => $thumbnail,
			'gallery'     => $images,
			'description' => rwmb_meta('mt_brand_description'),
			'styles'      => rwmb_meta('mt_brand_style'),
			'origins'     => rwmb_meta('mt_brand_origin'),
			'gender'      => rwmb_meta('mt_brand_gender'),
			'link'        => rwmb_meta('mt_brand_link'),
			'filters'     => $this->get_brand_filters($post_id)
		];
		return $data;
	}

	private function getBrandFilters($post_id)
	{
		$categories = rwmb_meta('mt_brand_category', '', $post_id);
		$styles     = rwmb_meta('mt_brand_style', '', $post_id);
		$origins    = rwmb_meta('mt_brand_origin', '', $post_id);
		$genders    = rwmb_meta('mt_brand_gender', '', $post_id);
		
		$result_categories = '';
		if (!empty($categories)) {
			foreach ($categories as $category) {
				$result_categories .= str_slug($category->name).' ';
			}
			$result_categories = substr($result_categories, 0 ,-1);
		}
		
		$result_styles = '';
		if (!empty($styles)) {
			foreach ($styles as $style) {
				$result_styles .= str_slug($style).' ';
			}
			$result_styles = substr($result_styles, 0 ,-1);
		}

		$result_origins = '';
		if (!empty($origins)) {
			foreach ($origins as $origin) {
				$result_origins .= str_slug($origin).' ';
			}
			$result_origins = substr($result_origins, 0 ,-1);
		}

		$filters = $result_categories.' '.$result_styles.' '.$result_origins.' '.$genders;
		return $filters;
	}

	public function getBrandFilterOptions($option = '')
	{
		switch ($option)
		{
			case 'origins':
				$options = [
					'house brands'         => 'House Brands',
					'exclusive brands'     => 'Exclusive Brands',
					'international brands' => 'International Brands',
					'japanese brands'      => 'Japanese Brands'
				];
			break;

			case 'styles':
				$options = [
					'artistic'    => 'Artistic',
					'elegant'     => 'Elegant',
					'classic'     => 'Classic',
					'vintage'     => 'Vintage',
					'fashionable' => 'Fashionable',
					'trendy'      => 'Trendy',
					'casual'      => 'Casual',
					'sports'      => 'Sports',
					'kids'        => 'Kids',
				];
			break;

			case 'genders':
				$options = [
					'male'   => 'Male',
					'female' => 'Female',
					'both'   => 'Both'
				];
			break;

			default: $options = [];

		}
		return $options;
	}
}

return new CPT_Brands();
