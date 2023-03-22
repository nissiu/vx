<?php
$current_post = \Voxel\get_current_post();
if ( ! $current_post ) {
	return;
}

wp_enqueue_script('vx:collections.js');

?>
<div class="ts-action-wrap ts-collections" data-post-id="<?= esc_attr( $current_post->get_id() ) ?>">
	<a href="#" ref="target" class="ts-action-con" role="button" rel="nofollow" @click.prevent="open">
		<div class="ts-action-icon"><?php \Voxel\render_icon( $action['ts_acw_initial_icon'] ) ?></div>
		<?= $action['ts_acw_initial_text'] ?>
	</a>
	<teleport to="body" class="hidden">
		<transition name="form-popup">
			<popup :show-save="false" :show-clear="false" v-if="active" ref="popup" @blur="active = false" :target="$refs.target">
				<div class="ts-popup-head ts-sticky-top flexify hide-d">
					<div class="ts-popup-name flexify">
						<?php \Voxel\render_icon( $action['ts_acw_initial_icon'] ) ?>
						<p><?= _x( 'Save post', 'save post action', 'voxel' ) ?></p>
					</div>
					<ul class="flexify simplify-ul">
						<li class="flexify ts-popup-close">
							<a role="button" rel="nofollow" @click.prevent="$root.active = false" href="#" class="ts-icon-btn">
								<?= \Voxel\get_icon_markup( $this->get_settings_for_display('ts_close_ico') ) ?: \Voxel\svg( 'close.svg' ) ?>
							</a>
						</li>
					</ul>
				</div>
				<template v-if="screen === 'create'">
					<div class="ts-create-collection" :class="{'vx-pending': create.loading}">
						<div>
							<div class="">
								<input type="text" ref="input" class="border-none" v-model="create.title" placeholder="Collection name" @keyup.enter="createCollection">
							</div>
						</div>
						<div class="ts-popup-controller">
							<ul class="flexify simplify-ul">
								<li class="flexify"><a href="#" @click.prevent="screen = 'main'" class="ts-btn ts-btn-1"><?= __( 'Cancel', 'voxel' ) ?></a></li>
								<li class="flexify"><a href="#" @click.prevent="createCollection" class="ts-btn ts-btn-2"><?= __( 'Create', 'voxel' ) ?></a></li>
							</ul>
						</div>
					</div>
				</template>
				<template v-else>
					<div v-if="items.loading" class="ts-empty-user-tab">
						<div class="ts-loader"></div>
					</div>
					<div v-else class="ts-term-dropdown ts-md-group">
						<ul class="simplify-ul ts-term-dropdown-list" :class="{'vx-pending': toggling}">
							<a href="#" @click.prevent="showCreateScreen" class="ts-btn ts-btn-4 ts-btn-small">
								<div class="ts-term-icon"><?php \Voxel\svg('plus.svg') ?></div>
								<p><?= _x( 'Create collection', 'save post action', 'voxel' ) ?></p>
							</a>
							<li v-for="item in items.list" :class="{'ts-selected': item.selected}">
								<a href="#" class="flexify" @click.prevent="toggleItem( item )">
									<div class="ts-checkbox-container">
										<label class="container-checkbox">
											<input type="checkbox" value="123" :checked="item.selected" disabled hidden>
											<span class="checkmark"></span>
										</label>
									</div>
								<!-- 	<?php \Voxel\render_icon( $action['ts_acw_initial_icon'] ) ?> -->
									<p>{{ item.title }}</p>
								</a>
							</li>
							<div class="n-load-more" v-if="items.hasMore">
								<a href="#" @click.prevent="loadMore" class="ts-btn ts-btn-4" :class="{'vx-pending': items.loadingMore}">
									<?php \Voxel\svg( 'reload.svg' ) ?>
									<?= __( 'Load more', 'voxel' ) ?>
								</a>
							</div>
						</ul>
					</div>
				</template>
			</popup>
		</transition>
	</teleport>
</div>
