<li
	class="ts-notifications-wrapper elementor-repeater-item-<?= $component['_id'] ?>"
	data-config="<?= esc_attr( wp_json_encode( [
		'l10n' => [
			'confirmClear' => _x( 'Clear all notifications?', 'notifications', 'voxel' ),
		],
	] ) ) ?>"
>
	<a ref="target" @click.prevent="open" href="#">
		<div class="ts-comp-icon flexify">
			<?php \Voxel\render_icon( $component['choose_component_icon'] ) ?>
			<?php if ( is_user_logged_in() && \Voxel\current_user()->get_notification_count()['unread'] > 0 ): ?>
				<span ref="indicator" class="unread-indicator"></span>
			<?php endif ?>
		</div>
		<p class="ts_comp_label" ><?= $component['notifications_title'] ?></p>
	</a>
	<teleport to="body" class="hidden">
		<transition name="form-popup">
			<form-popup
				ref="popup"
				v-if="$root.active"
				:target="$refs.target"
				:show-save="false"
				:show-clear="false"
				@blur="active = false"
			>
				<div class="ts-popup-head flexify ts-sticky-top">
					<div class="ts-popup-name flexify">
						<?php \Voxel\render_icon( $component['choose_component_icon'] ) ?>
						<p><?= $component['notifications_title'] ?></p>
					</div>

					<ul class="flexify simplify-ul">
						<li class="flexify">
							<a href="#" @click.prevent="clearAll" class="ts-icon-btn" role="button" rel="nofollow">
								<?= \Voxel\get_icon_markup( $this->get_settings_for_display('ts_trash_ico') ) ?: \Voxel\svg( 'trash-can.svg' ) ?>
							</a>
						</li>
						<li class="flexify ts-popup-close">
							<a @click.prevent="$root.active = false" href="#" class="ts-icon-btn" role="button" rel="nofollow">
								<?= \Voxel\get_icon_markup( $this->get_settings_for_display('ts_close_ico') ) ?: \Voxel\svg( 'close.svg' ) ?>
							</a>
						</li>
					</ul>
				</div>
				<div v-if="loading" class="ts-empty-user-tab">
					<span class="ts-loader"></span>
				</div>
				<div v-else-if="!list.length" class="ts-empty-user-tab">
					<?php \Voxel\render_icon( $component['choose_component_icon'] ) ?>
					<p><?= _x( 'No notifications received', 'notifications', 'voxel' ) ?></p>
				</div>
				<ul class="ts-notification-list simplify-ul">
					<li v-for="item in list" :class="{'ts-new-notification': item.is_new, 'ts-unread-notification': !item.seen, 'vx-loading': item._loading}">
						<a href="#" @click.prevent="openItem(item)">
							<div class="notification-image">
								<template v-if="item.image_url">
									<img :src="item.image_url">
								</template>
								<template v-else>
									<?php \Voxel\render_icon( $component['choose_component_icon'] ) ?>
								</template>
							</div>
							<div class="notification-details">
								<p>{{ item.subject }}</p>
								<span>{{ item.time }}</span>
							</div>
						</a>
					</li>
				</ul>
				<div class="ts-form-group">
						<div class="n-load-more" v-if="hasMore">
							<a href="#" @click.prevent="loadMore" class="ts-btn ts-btn-4" :class="{'vx-pending': loadingMore}">
								<?= \Voxel\get_icon_markup( $this->get_settings_for_display('ts_load_ico') ) ?: \Voxel\svg( 'reload.svg' ) ?>
								<?= __( 'Load more', 'voxel' ) ?>
							</a>
						</div>
					</div>
			</form-popup>
		</transition>
	</teleport>
</li>
