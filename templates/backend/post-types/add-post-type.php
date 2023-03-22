<?php
/**
 * Add post type form in WP Admin.
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
				<h1>New post type<p>Create a post  type</p></h1>
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
						<label>Singular name</label>
						<input name="post_type[singular_name]" type="text" autocomplete="off" required>
					</div>
					<div class="ts-form-group ts-col-1-1">
						<label>Plural name</label>
						<input name="post_type[plural_name]" type="text" autocomplete="off" required>
					</div>
					<div class="ts-form-group ts-col-1-1">
						<label>Key</label>
						<input name="post_type[key]" type="text" autocomplete="off" maxlength="20" required><br>
						<p>Must not exceed 20 characters and may only contain lowercase alphanumeric characters, dashes, and underscores.</p>
					</div>
					<div class="ts-form-group ts-col-1-1">
						<input type="hidden" name="action" value="voxel_create_post_type">
						<?php wp_nonce_field( 'voxel_manage_post_types' ) ?>
						<button type="submit" class="ts-button ts-create-settings full-width">Create post type</button>
					</div>
				</div>
			</form>
		</div>
	</div>
</div>