<?php
class CPT_News
{
	public function __construct()
	{
		add_action('init', [$this, 'registerNews']);
		add_filter('post_type_link', [$this, 'registerPostLink'], 1, 3);
		add_filter('rwmb_meta_boxes', [$this, 'registerMetabox']);
		add_filter('manage_news_posts_columns', [$this, 'adminTableHeadings']);
		add_action('manage_news_posts_custom_column', [$this, 'adminTableColumns'], 10, 2);
	}

	public function registerNews()
	{
		register_post_type('news',
			[
				'labels' => [
					'name'                => 'News',
					'singular_name'       => 'News',
					'menu_name'           => 'News',
					'all_items'           => 'All News',
					'add_new'             => 'Add New',
					'add_new_item'        => 'Add New News',
					'edit_item'           => 'Edit News',
					'new_item'            => 'New News',
					'view_item'           => 'View News',
					'search_items'        => 'Search News',
					'not_found'           => 'No News Found',
					'not_found_in_trash'  => 'No News found in trash',
					'parent'              => 'Parent News',
				],
				'public'             => true,
				'publicly_queryable' => true,
				'show_ui'            => true,
				'show_in_menu'       => true,
				'query_var'          => true,
				'rewrite'            => ['slug' => 'news', 'with_front' => true],
				'capability_type'    => 'post',
				'has_archive'        => true,
				'hierarchical'       => false,
				'menu_position'      => 5,
				'menu_icon'          => 'dashicons-align-left',
				'supports'           => ['title', 'thumbnail'] // 'title', 'editor', 'author', 'thumbnail', 'excerpt', 'comments'
			]
		);

		register_taxonomy(
			'news-category',
			['news'],
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
				'hierarchical' => true,
				'query_var' => true,
				'rewrite' => [ 'slug' => 'news-category/', 'with_front' => false ],
			]
		);

	}

	public function registerPostLink($post_link, $id = 0)
	{
		if (strpos('%news-category%', $post_link) === 'FALSE') {
			return $post_link;
		}
		$post = get_post($id);
		if (!is_object($post) || $post->post_type != 'news') {
			return $post_link;
		}
		// this calls the term to be added to the URL
		$terms = wp_get_object_terms($post->ID, 'news-category');
		if (!$terms) {
			return str_replace('news-category/%news-category%/', '', $post_link);
		}
		return str_replace('%news-category%', $terms[0]->slug, $post_link);
	}

	public function registerMetabox($meta_boxes)
	{
		$prefix = 'mt_news_';
		$meta_boxes[] = [
			'title'      => 'News Settings',
			'post_types' => 'news',
			'fields'     => [
				[
					'type' => 'heading',
					'name' => 'Category',
					'desc' => 'Select category for this post',
				],
				[
					'name'        => 'Category',
					'id'          => $prefix.'category',
					'placeholder' => 'Select category',
					'type'        => 'taxonomy',
					'taxonomy'    => 'news-category',
					'field_type'  => 'select',
					'query_args'  => [
						'orderby' => 'count',
						'hide_empty' => 0
					],
				],
				[
					'id'      => $prefix.'details',
					'type'    => 'wysiwyg',
					'raw'     => false,
					'options' => [
						'teeny'         => true,
						'media_buttons' => true,
					],
					'rows' => 5,
				],
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
		$news = $this->get_news($post->ID);
		switch ($column)
		{
			case 'image':
				echo '<img src="'.$news['thumbnail'].'" width="150">';
			break;
	
			case 'category' :
				$category = !empty($news['category']) ? $news['category']->name : 'Category not set';
				echo $category;
			break;
		}
	}

	public function getNewsByCategory($category = '', $posts_per_page = 30)
	{
		$args = [
			'post_type'      => 'news',
			'posts_per_page' => $posts_per_page,
			'paged'          => @$paged,
			'orderby'        => 'date',
			'order'          => 'DESC',
		];

		if ($category != '') {
			$args['tax_query'] = [
				'relation' => 'AND',
				[
					'taxonomy' => 'brand-category',
					'field'    => 'slug',
					'terms'    => $category
				]
			];
		}

		$news = new WP_Query($args);
		return $news;
	}

	public function getNews($post_id)
	{
		$image = get_the_post_thumbnail_url($post_id, 'full');
		$thumbnail = empty($image) ? get_bloginfo('template_url').'/dist/img/no-image.jpg': $image;

		$data = [
			'name'        => get_the_title(),
			'thumbnail'   => $thumbnail,
			'category'    => rwmb_meta('mt_news_category'),
			'details'     => rwmb_meta('mt_news_details')
		];
		return $data;
	}
}

return new CPT_News();
