<?php
/**
 * Add product type form in WP Admin.
 *
 * @since 1.0
 */
if ( ! defined('ABSPATH') ) {
	exit;
} ?>
<div class="edit-cpt-header">
	<div class="ts-container  cpt-header-container">
		<div class="ts-row">
			<div class="ts-col-2-3">
				<h1>
					New product type
					<p>Create a product type</p>
				</h1>
			</div>
		</div>
		<div class="ts-separator"></div>
	</div>
</div>
<div class="ts-container post-types-con">

	<div class="ts-row">
		<div class="ts-col-1-3">
			<form method="POST" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ) ?>">
				<div class="ts-row wrap-row">
					<div class="ts-form-group ts-col-1-1">
						<label>Label</label>
						<input name="product_type[label]" type="text" autocomplete="off" required>
					</div>
					<div class="ts-form-group ts-col-1-1">
						<label>Key</label>
						<input name="product_type[key]" type="text" autocomplete="off" maxlength="20" required><br>
					</div>
					<div class="ts-form-group ts-col-1-1">
						<input type="hidden" name="action" value="voxel_create_product_type">
						<?php wp_nonce_field( 'voxel_manage_product_types' ) ?>
						<button type="submit" class="ts-button ts-create-settings full-width">Create product type</button>
					</div>
				</div>
			</form>
		</div>
	</div>
</div>