<?php

if ( is_post_type_archive() ) {
	do_action( 'voxel/post-type-archive', \Voxel\Post_Type::get( get_queried_object() ) );
} elseif ( is_category() || is_tag() ) {
	require_once locate_template( 'taxonomy.php' );
}
