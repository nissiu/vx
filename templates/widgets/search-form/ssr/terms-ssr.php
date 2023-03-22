<?php $value = $this->_get_selected_terms() ?>
<?php $config = $this->get_frontend_config() ?>

<?php if ( ( $this->elementor_config['display_as'] ?? 'popup' ) === 'inline' ): ?>
	<div v-if="false" class="<?= $args['wrapper_class'] ?> inline-terms-wrapper ts-inline-filter">
		<?php if ( ! empty( $args['show_labels'] ) ): ?>
			<label><?= $this->get_label() ?></label>
		<?php endif ?>
		<?php if ( count( $config['props']['terms'] ) >= 15 ): ?>
			<div class="ts-input-icon flexify">
				<?= \Voxel\get_icon_markup( $this->search_widget->get_settings_for_display('ts_sf_form_btn_icon') ) ?: \Voxel\svg( 'search.svg' ) ?>
				<input type="text" placeholder="<?= esc_attr( _x( 'Search', 'terms filter', 'voxel' ) ) ?>" class="inline-input">
			</div>
		<?php endif ?>
		<div class="ts-term-dropdown ts-multilevel-dropdown inline-multilevel">
			<ul class="simplify-ul ts-term-dropdown-list">
				<?php foreach ( $config['props']['terms'] as $term ): ?>
					<?php $is_selected = is_array( $value ) && isset( $value[ $term['slug'] ] ); ?>
					<li class="<?= $is_selected ? 'ts-selected' : '' ?>">
						<a href="#" class="flexify">
							<div class="ts-checkbox-container">
								<label class="container-<?= $config['props']['multiple'] ? 'checkbox' : 'radio' ?>">
									<input type="<?= $config['props']['multiple'] ? 'checkbox' : 'radio' ?>" <?= $is_selected ? 'checked="checked"' : '' ?>>
									<span class="checkmark"></span>
								</label>
							</div>

							<p><?= esc_attr( $term['label'] ) ?></p>
							<?php if ( ! empty( $term['children'] ) ): ?>
								<div class="ts-right-icon"></div>
							<?php endif ?>
							<div class="ts-term-icon">
								<span><?= $term['icon'] ?></span>
							</div>
						</a>
					</li>
				<?php endforeach ?>
			</ul>
		</div>
	</div>
<?php else: ?>
	<div v-if="false" class="<?= $args['wrapper_class'] ?>">
		<?php if ( ! empty( $args['show_labels'] ) ): ?>
			<label><?= $this->get_label() ?></label>
		<?php endif ?>
		<div class="ts-filter ts-popup-target <?= $value ? 'ts-filled' : '' ?>">
			<span><?= \Voxel\get_icon_markup( $this->get_icon() ) ?></span>
			<div class="ts-filter-text">
				<?= $value ? array_values( $value )[0]['label'] : ( $this->props['placeholder'] ?: $this->props['label'] ) ?>
				<?php if ( $value && count( $value ) > 1 ): ?>
					<span class="term-count">+<?= number_format_i18n( count( $value ) - 1 ) ?></span>
				<?php endif ?>
			</div>
			<div class="ts-down-icon"></div>
		</div>
	</div>
<?php endif ?>
