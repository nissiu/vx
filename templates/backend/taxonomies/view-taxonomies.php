<?php
/**
 * Template for managing active taxonomies in wp-admin.
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
				<h1>Taxonomies<p>Taxonomies associated with post types on your site.</p></h1>
			</div>
			<div class="cpt-header-buttons ts-col-1-3">
				<a href="<?= esc_url( $add_taxonomy_url ) ?>" class="ts-button ts-save-settings btn-shadow"><i class="las la-plus icon-sm"></i>Create taxonomy</a>
			</div>			
		</div>
		<div class="ts-separator"></div>
	</div>
</div>
<div class="ts-container post-types-con">
	
	<div class="ts-row wrap-row">

		<?php foreach ( $taxonomies as $taxonomy ): ?>
			<div class="ts-col-1-4 voxel-created-<?= $taxonomy->is_managed_by_voxel() ? 'yes' : 'no' ?>">
				<div class="post-type-card">
					<i class="las la-list-ul"></i>
					<h3><?= $taxonomy->get_label() ?></h3>
					<ul>
						<li>Post type: <?= join( ', ', $taxonomy->get_post_types() ) ?></li>
						<li>Key: <?= $taxonomy->get_key() ?></li>
						<li>Built-in: <?= $taxonomy->is_built_in() ? 'yes' : 'no' ?></li>
						<!-- <li>Description: <?= $taxonomy->get_description() ?: '<em>(empty)</em>' ?></li> -->
					</ul>
					
					<div class="two-btn">
						<?php if ( $taxonomy->is_managed_by_voxel() ): ?>
							<a href="<?= esc_url( $taxonomy->get_edit_link() ) ?>" class="ts-button ts-faded"><i class="las la-pen icon-sm"></i>Edit</a>
						<?php endif ?>

						<a href="<?= esc_url( admin_url( sprintf(
							'admin.php?page=voxel-taxonomies&action=reorder-terms&taxonomy=%s',
							$taxonomy->get_key()
						) ) ) ?>" class="ts-button ts-faded"><i class="las la-list-ol icon-sm"></i>Reorder</a>
					</div>
				</div>
			</div>
		<?php endforeach ?>

		

	</div>
</div>

