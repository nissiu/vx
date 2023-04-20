<?php $value = $this->parse_value( $this->get_value() ) ?>
<?php $config = $this->get_frontend_config() ?>

<?php if ( ( $this->elementor_config['display_as'] ?? 'popup' ) === 'inline' ): ?>
	<div v-if="false" class="<?= $args['wrapper_class'] ?> ts-inline-filter">
		<?php if ( ! empty( $args['show_labels'] ) ): ?>
			<label><?= $this->get_label() ?></label>
		<?php endif ?>
		<div class="ts-input-icon flexify">
			<span><?= \Voxel\get_icon_markup( $this->get_icon() ) ?></span>
			<a href="#" class="inline-user-location">
				<?= \Voxel\get_icon_markup( $this->search_widget->get_settings_for_display('ts_mylocation_icon') ) ?: \Voxel\svg( 'my-location.svg' ) ?>
			</a>
			<input type="text" class="inline-input" placeholder="<?= esc_attr( $this->props['placeholder'] ?: $this->props['label'] ) ?>" value="<?= esc_attr( $value ? $value['address'] : '' ) ?>">
		</div>
	</div>
<?php else: ?>
	<div v-if="false" class="<?= $args['wrapper_class'] ?>">
		<?php if ( ! empty( $args['show_labels'] ) ): ?>
			<label><?= $this->get_label() ?></label>
		<?php endif ?>
		<div class="<?= ! empty( $value['address'] ) ? 'ts-double-input flexify' : ''?>">
			<div class="ts-filter ts-popup-target <?= $value ? 'ts-filled' : '' ?>">
				<span><?= \Voxel\get_icon_markup( $this->get_icon() ) ?></span>
				<div class="ts-filter-text"><?= $value ? $value['address'] : ( $this->props['placeholder'] ?: $this->props['label'] ) ?></div>
				<div class="ts-down-icon"></div>
			</div>
		</div>
	</div>
<?php endif ?>

<?php if ( ! empty( $value['address'] ) && $value['address'] !== $config['props']['l10n']['visibleArea'] ): ?>
	<?php if ( ( $this->elementor_config['display_proximity_as'] ?? 'popup' ) === 'inline' ): ?>
		<div v-if="false" class="<?= $args['wrapper_class'] ?> ts-inline-filter">
			<label><?= _x( 'Enable proximity search', 'location filter', 'voxel' ) ?></label>
			<div class="switch-slider">
				<div class="onoffswitch">
					<input type="checkbox" class="onoffswitch-checkbox" <?= ( $value['method'] ?? null ) === 'radius' ? 'checked="checked"' : '' ?>>
					<label class="onoffswitch-label"></label>
				</div>
			</div>
			<?php if ( ( $value['method'] ?? null ) === 'radius' ): ?>
				<?php $scale = $config['props']['radius']['max'] - $config['props']['radius']['min'] ?>
				<?php $percent = \Voxel\clamp( ( $config['props']['value']['radius'] / $scale ) * 100, 0, 100 ) ?>
				<div>
					<div class="range-slider-wrapper">
						<div class="range-value"><?= ( $value['method'] ?? null ) === 'radius'
							? ( $value['radius'].' '.( $this->props['radius_units'] === 'mi' ? _x( 'mi', 'location filter', 'voxel' ) : _x( 'km', 'location filter', 'voxel' ) ) )
							: _x( 'Distance', 'location filter', 'voxel' ) ?></div>
						<div class="range-slider noUi-target noUi-ltr noUi-horizontal noUi-txt-dir-ltr">
							<div class="noUi-base">
								<div class="noUi-connects">
									<div class="noUi-connect" style="transform: translate(0%, 0px) scale(<?= $percent / 100 ?>, 1);"></div>
								</div>
								<div class="noUi-origin" style="transform: translate(-<?= 1000 - ( $percent * 10 ) ?>%, 0px); z-index: 4;">
									<div class="noUi-handle noUi-handle-lower">
										<div class="noUi-touch-area"></div>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			<?php endif ?>
		</div>
	<?php elseif ( ( $this->elementor_config['display_proximity_as'] ?? 'popup' ) === 'none' ): ?>
	<?php else: ?>
		<div v-if="false" class="<?= $args['wrapper_class'] ?>">
			<?php if ( ! empty( $args['show_labels'] ) ): ?>
				<label><?= _x( 'Distance', 'location filter', 'voxel' ) ?></label>
			<?php endif ?>
			<div class="ts-filter ts-popup-target <?= ( $value['method'] ?? null ) === 'radius' ? 'ts-filled' : '' ?>">
				<span><?php \Voxel\svg( 'my-location.svg' ) ?></span>
				<div class="ts-filter-text"><?= ( $value['method'] ?? null ) === 'radius'
					? ( $value['radius'].( $this->props['radius_units'] === 'mi' ? _x( 'mi', 'location filter', 'voxel' ) : _x( 'km', 'location filter', 'voxel' ) ) )
					: _x( 'Distance', 'location filter', 'voxel' ) ?></div>
					<div class="ts-down-icon"></div>
			</div>
		</div>
	<?php endif ?>
<?php endif ?>