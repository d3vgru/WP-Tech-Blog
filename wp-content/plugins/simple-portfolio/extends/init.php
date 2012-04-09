<?php
add_action('init', 'portfolio_init');

/**
* Initialize simple-portfolio plugin
*/
function portfolio_init() {
	$custom_slug = get_option('slug') != '' ? get_option('slug') : 'portfolio';
	
	$args = array(
		'labels'			=> array(
			'name'					=> __('Portfolio', 'simple-portfolio'),
			'singular_name' 		=> __('Portfolio Project', 'simple-portfolio'),
			'add_new'				=> __('Add Project', 'simple-portfolio'),
			'add_new_item'			=> __('Add Project', 'simple-portfolio'),
			'new_item'				=> __('Add Project', 'simple-portfolio'),
			'view_item'				=> __('View Project', 'simple-portfolio'),
			'search_items' 			=> __('Search Portfolio', 'simple-portfolio'),
			'edit_item' 			=> __('Edit Project', 'simple-portfolio'),
			'all_items'				=> __('Complete Portfolio', 'simple-portfolio'),
			'not_found'				=> __('No Projects found', 'simple-portfolio'),
			'not_found_in_trash'	=> __('No Projects found in Trash', 'simple-portfolio')
		),
		'taxonomies'		=> array('portfolio-categories', 'portfolio-clients', 'portfolio-tags'),
		'public'			=> true,
		'show_ui'			=> true,
		'_builtin'			=> false,
		'_edit_link'		=> 'post.php?post=%d',
		'capability_type'	=> 'post',
		'rewrite'			=> array('slug' => __($custom_slug)),
		'hierarchical'		=> false,
		'menu_position'		=> 20,
//		'menu_icon'			=> WP_PLUGIN_URL . '/simple-portfolio/images/icon.jpg',
		'supports'			=> array('title', 'editor', 'comments', 'thumbnail')
	);
	
	/** create portfolio categories (taxonomy) */
	register_taxonomy('portfolio-categories', 'project', array(
			'hierarchical'		=> true,
			'show_ui'			=> true,
			'rewrite'			=> array('slug' => __($custom_slug . '/category')),
			'labels'			=> array(
					'name' 							=> __('Portfolio Categories', 'simple-portfolio'),
					'singular_name'					=> __('Portfolio Category', 'simple-portfolio'),
					'search_items' 					=> __('Search Portfolio Categories', 'simple-portfolio'),
					'popular_items'					=> __('Popular Portfolio Categories', 'simple-portfolio'),
					'all_items'						=> __('All Portfolio Categories', 'simple-portfolio'),
					'parent_item'					=> __('Parent Portfolio Category', 'simple-portfolio'),
					'parent_item_colon'				=> __('Parent Portfolio Category', 'simple-portfolio'),
					'edit_item'						=> __('Edit Portfolio Category', 'simple-portfolio'),
					'update_item'					=> __('Update Portfolio Category', 'simple-portfolio'),
					'add_new_item'					=> __('Add New Portfolio Category', 'simple-portfolio'),
					'new_item_name'					=> __('New Portfolio Category', 'simple-portfolio'),
					'separate_items_with_commas'	=> __('Separate Portfolio Categories with commas', 'simple-portfolio'),
					'add_or_remove_items' 			=> __('Add or remove Portfolio Categories', 'simple-portfolio'),
					'choose_from_most_used' 		=> __('Choose from the most used Portfolio Categories', 'simple-portfolio')
		)
	));
	
	/** create portfolio clients (taxonomy) */
	register_taxonomy('portfolio-clients', 'project', array(
			'hierarchical'		=> true,
			'show_ui'			=> true,
			'query_var' 		=> true,
			'rewrite'			=> array('slug' => __($custom_slug . '/client')),
			'labels'			=> array(
					'name' 							=> __('Clients', 'simple-portfolio'),
					'singular_name'					=> __('Client', 'simple-portfolio'),
					'search_items' 					=> __('Search Clients', 'simple-portfolio'),
					'popular_items'					=> __('Popular Clients', 'simple-portfolio'),
					'all_items'						=> __('All Clients', 'simple-portfolio'),
					'parent_item'					=> __('Parent Client', 'simple-portfolio'),
					'parent_item_colon'				=> __('Parent Client', 'simple-portfolio'),
					'edit_item'						=> __('Edit Client', 'simple-portfolio'),
					'update_item'					=> __('Update Client', 'simple-portfolio'),
					'add_new_item'					=> __('Add New Client', 'simple-portfolio'),
					'new_item_name'					=> __('New Client', 'simple-portfolio'),
					'separate_items_with_commas'	=> __('Separate Clients with commas', 'simple-portfolio'),
					'add_or_remove_items' 			=> __('Add or remove Clients', 'simple-portfolio'),
					'choose_from_most_used' 		=> __('Choose from the most used Clients', 'simple-portfolio')
		)
	));
	
	/** create portfolio tags (taxonomy) */
	register_taxonomy('portfolio-tags', 'project', array(
			'hierarchical'		=> false,
			'show_ui'			=> true,
			'query_var' 		=> true,
			'public'			=> true,
			'rewrite'			=> array('slug' => __($custom_slug . '/tag')),
			'labels'			=> array(
					'name' 							=> __('Tags', 'simple-portfolio'),
					'singular_name'					=> __('Tag', 'simple-portfolio'),
					'search_items' 					=> __('Search Tags', 'simple-portfolio'),
					'popular_items'					=> __('Popular Tags', 'simple-portfolio'),
					'all_items'						=> __('All Tags', 'simple-portfolio'),
					'parent_item'					=> __('Parent Tag', 'simple-portfolio'),
					'parent_item_colon'				=> __('Parent Tag', 'simple-portfolio'),
					'edit_item'						=> __('Edit Tag', 'simple-portfolio'),
					'update_item'					=> __('Update Tag', 'simple-portfolio'),
					'add_new_item'					=> __('Add New Tag', 'simple-portfolio'),
					'new_item_name'					=> __('New Tag', 'simple-portfolio'),
					'separate_items_with_commas'	=> __('Separate Tags with commas', 'simple-portfolio'),
					'add_or_remove_items' 			=> __('Add or remove Tags', 'simple-portfolio'),
					'choose_from_most_used' 		=> __('Choose from the most used Tags', 'simple-portfolio')
		)
	));
	
	/** create new custom post type */
	register_post_type('portfolio', $args);
}

