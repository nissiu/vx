<script type="text/html" id="orders-display-actions">
	<ul class="simplify-ul flexify">
		<li>
			<a href="#" @click.prevent="order.backToAll" class="ts-icon-btn">
				<?= \Voxel\get_icon_markup( $this->get_settings_for_display('ts_arrow_left') ) ?: \Voxel\svg( 'chevron-left.svg' ) ?>
			</a>
		</li>
		<li v-if="orderDetails.role.is_author && orderDetails.status.slug === 'pending_approval'">
			<a href="#" @click.prevent="order.doAction('author.approve')" class="ts-btn ts-btn-2 ts-btn-large ts-approve-btn">
				<span v-html="$root.config.actions['author.approve'].icon"></span>
				{{ $root.config.actions['author.approve'].label }}
			</a>
		</li>
		<li v-if="order.qrTags.length">
			<form-group popup-key="qrcodes" ref="qrcodes" :show-save="false" clear-label="<?= esc_attr( _x( 'Close', 'single order', 'voxel' ) ) ?>" @clear="$event.blur()">
				<template #trigger>
					<a href="#" @mousedown="$root.activePopup = 'qrcodes'" class="ts-icon-btn ts-popup-target">
						<?= \Voxel\get_icon_markup( $this->get_settings_for_display('ts_qr_ico') ) ?: \Voxel\svg( 'qr.svg' ) ?>
					</a>
				</template>
				<template #popup>
					<div class="ts-term-dropdown ts-md-group">
						<ul class="simplify-ul ts-term-dropdown-list min-scroll">
							<li v-for="tag in order.qrTags">
								<a :href="order.getQrLink(tag)" download @click="$root.activePopup = null" class="flexify">
									<div class="ts-term-icon"><?= \Voxel\get_icon_markup( $this->get_settings_for_display('ts_qr_ico') ) ?: \Voxel\svg( 'qr.svg' ) ?></div>
									<p><?= _x( 'Get QR code for', 'single order', 'voxel' ) ?> "{{ tag.label }}"</p>
								</a>
							</li>
						</ul>
					</div>
				</template>
			</form-group>
		</li>
		<li v-if="order.actions.length">
			<form-group popup-key="actions" ref="actions" :show-save="false" clear-label="<?= esc_attr( _x( 'Close', 'single order', 'voxel' ) ) ?>" @clear="$event.blur()">
				<template #trigger>
					<a href="#" @click.prevent @mousedown="$root.activePopup = 'actions'" class="ts-icon-btn ts-popup-target">
						<?= \Voxel\get_icon_markup( $this->get_settings_for_display('ts_sr_more') ) ?: \Voxel\svg( 'menu.svg' ) ?>
					</a>
				</template>
				<template #popup>
					<div class="ts-term-dropdown ts-md-group">
						<ul class="simplify-ul ts-term-dropdown-list min-scroll">
							<li v-for="action in order.actions">
								<a v-if="$root.config.actions[ action ]" href="#" @click.prevent="order.doAction(action); $root.activePopup = null;" class="flexify">
									<div class="ts-term-icon"><span v-html="$root.config.actions[ action ].icon"></span></div>
									<p>{{ $root.config.actions[ action ].label }}</p>
								</a>
							</li>
						</ul>
					</div>
				</template>
			</form-group>
		</li>
	</ul>
</script>
