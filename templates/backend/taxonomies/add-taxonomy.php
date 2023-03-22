<?php
/**
 * Add taxonomy form in WP Admin.
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
				<h1>New taxonomy<p>Create a custom taxonomy</p></h1>
			</div>
		</div>
		<div class="ts-separator"></div>
	</div>
</div>
<div class="ts-container post-types-con">
	
	<div class="ts-row wrap-row">
		<div class="ts-col-1-3">
			<form method="POST" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ) ?>">
				<div class="ts-row wrap-row">
					
					<div class="ts-form-group ts-col-1-1">
						<label><strong>Singular name</strong></label>
						<input name="taxonomy[singular_name]" type="text" required>
					</div>
					<div class="ts-form-group ts-col-1-1">
						<label>Plural name</label><br>
						<input name="taxonomy[plural_name]" type="text" required>
					</div>
					<div class="ts-form-group ts-col-1-1">
						<label>Key</label>
						<input name="taxonomy[key]" type="text" maxlength="32" required><br>
						<p>Must not exceed 32 characters and may only contain lowercase alphanumeric characters, dashes, and underscores.</p>
					</div>
					<div class="ts-form-group ts-col-1-1">
						<label>Post Type</label>
						<select name="taxonomy[post_type][]" required multiple style="padding-top: 15px; height: 200px;" class="min-scroll">
							<?php foreach ( \Voxel\Post_Type::get_all() as $post_type ): ?>
								<option value="<?= esc_attr( $post_type->get_key() ) ?>">
									<?= esc_html( $post_type->get_label() ) ?>
								</option>
							<?php endforeach ?>
						</select>
					</div>

					<div class="ts-col-1-1 ts-form-group">
						<input type="hidden" name="action" value="voxel_create_taxonomy">
						<?php wp_nonce_field( 'voxel_manage_taxonomies' ) ?>
						<button type="submit" class="ts-button ts-create-settings full-width">Create taxonomy</button>
					</div>
				</div>
			</form>
		</div>

	</div>
</div>