<?php

get_header();

if ( \Voxel\get_page_setting( 'voxel_hide_header' ) !== 'yes' ) {
	\Voxel\print_header();
}

the_content();

if ( \Voxel\get_page_setting( 'voxel_hide_footer' ) !== 'yes' ) {
	\Voxel\print_footer();
}

get_footer();