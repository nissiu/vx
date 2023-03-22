<?php
/**
 * Template for managing product types in wp-admin.
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
				<h1>Product Types<p>Product types you created</p></h1>
			</div>
			<div class="cpt-header-buttons ts-col-1-3">
				<a href="<?= esc_url( $add_type_url ) ?>" class="ts-button ts-save-settings btn-shadow">
					<i class="las la-plus icon-sm"></i>
					Create product type
				</a>
			</div>
		</div>
		<div class="ts-separator"></div>
	</div>
</div>
<div class="ts-container post-types-con">
	<div class="ts-row wrap-row">
		<?php foreach ( $product_types as $product_type ): ?>
			<div class="ts-col-1-4">
				<div class="post-type-card">
					<i class="las la-shopping-bag"></i>
					<h3><?= $product_type->get_label() ?></h3>
					<ul>
						<li>Key: <?= $product_type->get_key() ?></li>
					</ul>
					<a href="<?= esc_url( $product_type->get_edit_link() ) ?>" class="ts-button ts-faded">
						<i class="las la-pen icon-sm"></i>Edit product type
					</a>
				</div>
			</div>
		<?php endforeach ?>
		<?php if ( empty( $product_types ) ): ?>
			<div class="ts-col-1-4">
				<p class="no-post-types">No product types created yet.</p>
			</div>
		<?php endif ?>
	</div>
</div>
