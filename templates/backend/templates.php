<?php
/**
 * Template for managing site and post type templates in wp-admin.
 *
 * @since 1.0
 */

if ( ! defined('ABSPATH') ) {
	exit;
}

$all_templates_link = admin_url( 'edit.php?elementor_library_category=voxel-template&post_type=elementor_library' );
?>
<div id="vx-template-manager" data-config="<?= esc_attr( wp_json_encode( $config ) ) ?>">
	<div class="edit-cpt-header">
		<div class="ts-container cpt-header-container">
			<div class="ts-row wrap-row v-center">
				<div class="ts-col-1-2 v-center ">
					<h1>Templates<p>Voxel template directory</p></h1>
				</div>
				<div class="cpt-header-buttons ts-col-1-2 v-center">
					<a href="<?= esc_url( $all_templates_link ) ?>" class="ts-button ts-faded">
						View all Voxel templates
					</a>
				</div>
			</div>
			<span class="ts-separator"></span>
		</div>
	</div>

	<div class="ts-container ts-theme-options">
		<div class="ts-row wrap-row">
			<div class="ts-col-1-1">
				<ul class="inner-tabs inner-tabs template-nav">
					<li :class="{'current-item': tab === 'general'}">
						<a href="#" @click.prevent="setTab('general')">General</a>
					</li>
					<li :class="{'current-item': tab === 'membership'}">
						<a href="#" @click.prevent="setTab('membership')">Membership</a>
					</li>
					<li :class="{'current-item': tab === 'orders'}">
						<a href="#" @click.prevent="setTab('orders')">Orders</a>
					</li>
					<li :class="{'current-item': tab === 'style_kits'}">
						<a href="#" @click.prevent="setTab('style_kits')">Style kits</a>
					</li>
					<?php foreach ( \Voxel\Post_Type::get_voxel_types() as $post_type ): ?>
						<li :class="{'current-item': tab === 'post_type:<?= esc_attr( $post_type->get_key() ) ?>'}">
							<a href="#" @click.prevent="setTab('post_type:<?= esc_attr( $post_type->get_key() ) ?>')">
								<?= $post_type->get_label() ?>
							</a>
						</li>
					<?php endforeach ?>
				</ul>
			</div>
		</div>
		<div class="ts-row wrap-row" v-cloak>
			<div class="ts-col-1-1">
				<div class="ts-template-list">
					<div class="ts-container post-types-con ts-template-list">
						<div class="ts-row wrap-row">
							<template v-for="template in config.templates">
								<div v-if="tab === template.category" class="ts-col-1-4">
									<div class="single-field tall">
										<a :href="editLink(template.id)" target="_blank" class="field-head">
											<img :src="template.image" alt="" class="icon-sm">
											<p class="field-name">{{ template.label }}</p>
											<span class="field-type mr0">Edit with Elementor</span>
										</a>
										<ul class="basic-ul">
											<li>
												<a :href="previewLink(template.id)" target="_blank" class="ts-button ts-faded icon-only">
													<i class="las la-eye icon-sm"></i>
												</a>
											</li>
											<li>
												<a href="#" @click.prevent="template.editSettings = true" class="ts-button ts-faded icon-only">
													<i class="las la-ellipsis-h icon-sm"></i>
												</a>
											</li>
										</ul>
									</div>
									<template-settings v-if="template.editSettings" :template="template"></template-settings>
								</div>
							</template>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>

<script type="text/html" id="template-manager-popup">
	<teleport to="body">
		<div class="ts-field-modal ts-theme-options">
			<div class="modal-backdrop" @click="template.editSettings = false"></div>
			<div class="modal-content min-scroll">
				<div class="modal-content-holder">
					<div class="field-modal-head">
						<h2>Template options</h2>
						<a href="#" @click.prevent="template.editSettings = false" class="ts-button btn-shadow">
							<i class="las la-check icon-sm"></i>Done
						</a>
					</div>
					<div class="ts-field-props">
						<div class="field-modal-body min-scroll">
							<div class="ts-row wrap-row">
								<div v-if="modifyId" class="ts-form-group ts-col-1-1" :class="{'vx-disabled': updating}">
									<label>{{ template.type === 'page' ? 'Enter new page template id' : 'Enter new template id' }}</label>
									<input type="number" v-model="newId">
									<p class="text-right">
										<a href="#" @click.prevent="modifyId = false" class="ts-button ts-transparent ts-btn-small">Cancel</a>
										<a href="#" @click.prevent="saveId" class="ts-button ts-faded ts-btn-small">Submit</a>
									</p>
								</div>
								<div v-else class="ts-form-group ts-col-1-1">
									<label>Template ID</label>
									<input type="number" disabled v-model="template.id">
									<p class="text-right">
										<a href="#" @click.prevent="modifyId = true" class="ts-button ts-transparent ts-btn-small">Switch template</a>
									</p>
								</div>
							</div>
						</div>
					</div>
				</div>
				
			</div>
		</div>
	</teleport>
</script>
