<?php

namespace Voxel\Controllers;

if ( ! defined('ABSPATH') ) {
	exit;
}

class Collections_Controller extends Base_Controller {

	protected function hooks() {
		$this->on( 'init', '@register_post_type', 0 );
	}

	protected function register_post_type() {
		register_post_type( 'collection', [
			'labels' => [
				'name' => 'Collections',
				'singular_name' => 'Collection',
			],
			'public'              => true,
			'show_ui'             => true,
			'show_in_menu'        => true,
			'show_in_nav_menus'   => true,
			'capability_type'     => 'page',
			'map_meta_cap'        => true,
			'publicly_queryable'  => true,
			'exclude_from_search' => false,
			'hierarchical'        => false,
			'query_var'           => true,
			'supports'            => [ 'title', 'publicize', 'thumbnail', 'comments' ],
			'menu_position'       => 72,
			'delete_with_user'    => true,
			'_is_created_by_voxel' => false,
			'has_archive' => 'collections',
		] );
	}
}
