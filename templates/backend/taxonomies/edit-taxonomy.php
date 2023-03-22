<?php
/**
 * Edit taxonomy form in WP Admin.
 *
 * @since 1.0
 */
if ( ! defined('ABSPATH') ) {
	exit;
} ?>
<div class="edit-cpt-header">
	<div class="ts-container cpt-header-container">
		<div class="ts-row">
			<div class="ts-col-2-3">
				<h1>Edit <?= esc_html( $taxonomy->get_label() ) ?><p>Edit custom taxonomy details</p></h1>
			</div>
		</div>
		<div class="ts-separator"></div>
	</div>
</div>
<div class="ts-container post-types-con ts-theme-options">
	<form method="POST" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ) ?>">
		<div class="ts-row wrap-row">
			<div class="ts-col-1-3">
				<div class="ts-row wrap-row">
					<div class="ts-form-group ts-col-1-1">
						<div class="ts-tab-heading">
							<h1>General</h1>
							<p>General taxonomy settings</p>
						</div>
					</div>

					<div class="ts-form-group ts-col-1-1">
						<label>Key</label>
						<input type="text" disabled value="<?= esc_attr( $taxonomy->get_key() ) ?>"><br>
					</div>

					<div class="ts-form-group ts-col-1-1">
						<label>Singular name</label>
						<input name="taxonomy[singular_name]" type="text" required value="<?= esc_attr( $taxonomy->get_singular_name() ) ?>">
					</div>

					<div class="ts-form-group ts-col-1-1">
						<label>Plural name</label>
						<input name="taxonomy[plural_name]" type="text" required value="<?= esc_attr( $taxonomy->get_plural_name() ) ?>">
					</div>

					<div class="ts-form-group ts-col-1-1">
						<label>Post Type</label>
						<select name="taxonomy[post_type][]" required multiple style="padding-top: 20px;height: 200px;" class="min-scroll">
							<?php foreach ( \Voxel\Post_Type::get_all() as $post_type ): ?>
								<option
									value="<?= esc_attr( $post_type->get_key() ) ?>"
									<?= in_array( $post_type->get_key(), $taxonomy->get_post_types(), true ) ? 'selected' : '' ?>
								>
									<?= esc_html( $post_type->get_label() ) ?>
								</option>
							<?php endforeach ?>
						</select>
					</div>

					<div class="ts-form-group ts-col-1-1">
						<input name="taxonomy[key]" type="hidden" value="<?= esc_attr( $taxonomy->get_key() ) ?>">
						<input type="hidden" name="action" value="voxel_save_taxonomy_settings">
						<?php wp_nonce_field( 'voxel_save_taxonomy_settings' ) ?>
						<button type="submit" class="ts-button ts-create-settings full-width">Update taxonomy</button>

						<?php if ( $taxonomy->is_created_by_voxel() ): ?>
							&nbsp;&nbsp;
							<button type="submit" name="remove_taxonomy" value="yes" class="ts-button ts-faded full-width"
								onclick="return confirm('Are you sure?')">
								Delete this taxonomy
							</button>
						<?php endif ?>
					</div>
				</div>
			</div>
			<div class="ts-col-2-3">
				<div class="ts-form-group ts-col-1-1">
					<div class="ts-tab-heading">
						<h1>Templates</h1>
						<p>Design the templates for this taxonomy</p>
					</div>
				</div>

				<div class="inner-tab ts-row wrap-row ts-col-1-1">
					<div class="ts-col-1-2">
						<div class="single-field tall">
							<a href="<?= esc_url( admin_url( sprintf( 'post.php?post=%d&action=elementor', $templates['single'] ) ) ) ?>" target="_blank" class="field-head">
								<img src="<?php echo esc_url( \Voxel\get_image('post-types/single.png') ) ?>" alt="" class="icon-sm">
								<p class="field-name">Single term</p>
								<span class="field-type">Edit with Elementor</span>
							</a>
						</div>
					</div>
					<div class="ts-col-1-2">
						<div class="single-field tall">
							<a href="<?= esc_url( admin_url( sprintf( 'post.php?post=%d&action=elementor', $templates['card'] ) ) ) ?>" target="_blank" class="field-head">
								<img src="<?php echo esc_url( \Voxel\get_image('post-types/pcard.png') ) ?>" alt="" class="icon-sm">
								<p class="field-name">Preview card</p>
								<span class="field-type">Edit with Elementor</span>
							</a>
						</div>
					</div>
				</div>
			</div>
		</div>
	</form>
</div>
