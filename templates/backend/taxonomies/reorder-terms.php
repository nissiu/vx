<?php
/**
 * Edit term order.
 *
 * @since 1.0
 */
if ( ! defined('ABSPATH') ) {
	exit;
} ?>
<div class="wrap">
	<div id="voxel-reorder-terms" data-terms="<?= esc_attr( wp_json_encode( $terms ) ) ?>" v-cloak>
		<form method="POST" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ) ?>" @submit="onSubmit">
			<div class="edit-cpt-header">
				<div class="ts-container  cpt-header-container">
					<div class="ts-row wrap-row v-center">
						<div class="ts-col-1-2 v-center">
							<h1>Reorder <?= $taxonomy->get_label() ?><p>Set term order for <b><?= $taxonomy->get_label() ?></b> taxonomy</p></h1>
						</div>
						<div class="cpt-header-buttons ts-col-1-2 v-center">
							<input type="hidden" name="taxonomy" value="<?= esc_attr( $taxonomy->get_key() ) ?>">
							<input type="hidden" name="terms" ref="termsInput">
							<input type="hidden" name="action" value="voxel_save_term_order">
							<?php wp_nonce_field( 'voxel_save_term_order' ) ?>
							<button type="submit" class="ts-button ts-save-settings btn-shadow">
								<i class="las la-save icon-sm"></i>Save changes
							</button>
						</div>
					</div>
					<div class="ts-separator"></div>
				</div>
			</div>
			<div class="ts-theme-options ts-container ts-terms-order">
				<div class="ts-tab-content ts-container" v-cloak>
					<div v-if="terms.length" class="inner-tab ts-row row-space-between wrap-row all-center">
						<div class="ts-col-1-2">
							<div class="field-container" ref="list">
								<term-list
									:terms="terms"
									:level="1"
									group="toplevel"
								></term-list>
							</div>
						</div>
					</div>
					<div v-else>
						No terms found for this taxonomy.
					</div>
				</div>
			</div>
		</form>
	</div>
</div>

<script type="text/html" id="voxel-reorder-term-list-component">
	<draggable
		v-model="items"
		:group="group"
		handle=".field-head"
		item-key="id"
		@start="onDragStart"
		@end="onDragEnd"
	>
		<template #item="{element: term}">
			<div class="single-field wide" :class="'field-level-'+level" ref="field">
				<div class="field-head" :title="'term id: '+term.id" @click="toggleCollapse">
					<i class="las la-bars field-handle"></i>
					<p class="field-name">{{ term.label }}</p>
					<span class="field-type">{{ term.slug }}</span>
					<div class="field-actions" v-if="term.children.length">
						<span class="field-action all-center">
							<a @click.prevent href="#">
								<i class="las la-angle-double-down"></i>
							</a>
						</span>
					</div>
				</div>
				<div v-if="term.children.length" class="field-container nested" style="padding-bottom: 0;" ref="list">
					<term-list
						:terms="term.children"
						:group="'term_'+term.id"
						:level="level+1"
						:parent="term"
					></term-list>
				</div>
			</div>
		</template>
	</draggable>
</script>
