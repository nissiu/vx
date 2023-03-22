<?php
/**
 * Template for managing active post types in wp-admin.
 *
 * @since 1.0
 */

if ( ! defined('ABSPATH') ) {
	exit;
} ?>

<div class="edit-cpt-header">
	<div class="ts-container cpt-header-container">
		<div class="ts-row wrap-row v-center">
			<div class="ts-col-2-3">
				<h1>Custom Post Types <p>Post types created or managed with Voxel.</p></h1>
			</div>

			<div class="cpt-header-buttons ts-col-1-3">
				<a href="<?php echo esc_url( $add_type_url ) ?>" class="ts-button ts-save-settings btn-shadow"><i class="las la-plus icon-sm"></i>Create post type</a>
			</div>



		</div>
		<div class="ts-separator"></div>
	</div>
</div>
<div class="ts-container post-types-con">

	<div class="ts-row wrap-row">

		<?php foreach ( $voxel_types as $post_type ): ?>
			<div class="ts-col-1-4">
				<div class="post-type-card">
					<?php echo $post_type->get_icon() ? \Voxel\render_icon( $post_type->get_icon() ) : '<i class="las la-cube"></i>'; ?>
					<h3><?php echo $post_type->get_label() ?></h3>
					<ul>
						<li>Key: <?php echo $post_type->get_key() ?></li>
						<li>Built-in: <?php echo $post_type->is_built_in() ? 'yes' : 'no' ?></li>
						<!-- <li>Description: <?php echo $post_type->get_description() ?: '<em>(empty)</em>' ?></li> -->
					</ul>
					<a href="<?php echo esc_url( $post_type->get_edit_link() ) ?>" class="ts-button edit-voxel ts-outline">
						Edit with Voxel
						<img src="<?php echo esc_url( \Voxel\get_image('post-types/logo.svg') ) ?>">

					</a>
				</div>
			</div>
		<?php endforeach ?>
		<?php if ( empty( $voxel_types ) ): ?>
			<div class="ts-col-1-4">
				<p class="no-post-types">No custom post types created yet.</p>
			</div>
		<?php endif ?>

	</div>


</div>
<div class="edit-cpt-header">
	<div class="ts-container cpt-header-container">
		<div class="ts-row">
			<div class="ts-col-1-1">
				<h1>Other Post Types<p>Post types added by WordPress and/or plugins.</p></h1>
			</div>
		</div>
		<div class="ts-separator"></div>
	</div>
</div>
<div class="ts-container post-types-con other-post-types">
	<div class="ts-row wrap-row">
		<?php foreach ( $other_types as $post_type ): ?>
			<div class="ts-col-1-4">
				<div class="post-type-card">

					<h3><?php echo $post_type->get_label() ?></h3>
					<ul>
						<li>Key: <?php echo $post_type->get_key() ?></li>
						<li>Built-in: <?php echo $post_type->is_built_in() ? 'yes' : 'no' ?></li>
						<!-- <li>Description: <?php echo $post_type->get_description() ?: '<em>(empty)</em>' ?></li> -->
					</ul>
					<a href="<?php echo esc_url( $post_type->get_edit_link() ) ?>" class="ts-button ts-outline edit-voxel">
						Manage with Voxel
						<img src="<?php echo esc_url( \Voxel\get_image('post-types/logo.svg') ) ?>">

					</a>
				</div>
			</div>
		<?php endforeach ?>
	</div>
</div>
