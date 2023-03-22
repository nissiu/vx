<?php if ( $this->get_settings('ts_source') === 'search-form' ): ?>
	<?php if ( $pagination === 'prev_next' ): ?>
		<div class="feed-pagination flexify <?= ! ( $results['has_prev'] || $results['has_next'] ) ? 'hidden' : '' ?>">
			<a href="#" class="ts-btn ts-btn-1 ts-btn-large ts-load-prev <?= ! $results['has_prev'] ? 'disabled' : '' ?>">
				<?= \Voxel\get_icon_markup( $this->get_settings_for_display('ts_arrow_left') ) ?: \Voxel\svg( 'arrow-left.svg' ) ?>
				<?= _x( 'Previous', 'post feed', 'voxel' ) ?>
			</a>
			<a href="#" class="ts-btn ts-btn-1 ts-btn-large btn-icon-right ts-load-next <?= ! $results['has_next'] ? 'disabled' : '' ?>">
				<?= _x( 'Next', 'post feed', 'voxel' ) ?>
				<?= \Voxel\get_icon_markup( $this->get_settings_for_display('ts_arrow_right') ) ?: \Voxel\svg( 'arrow-right.svg' ) ?>
			</a>
		</div>
	<?php elseif ( $pagination === 'load_more' ): ?>
		<div class="feed-pagination flexify">
			<a href="#" class="ts-btn ts-btn-1 ts-btn-large ts-load-more <?= ! $results['has_next'] ? 'hidden' : '' ?>">
				<?= \Voxel\get_icon_markup( $this->get_settings_for_display('ts_sf_form_btn_reset_icon') ) ?: \Voxel\svg( 'reload.svg' ) ?>
				<?= _x( 'Show more results', 'post feed', 'voxel' ) ?>
			</a>
		</div>
	<?php endif ?>
<?php endif ?>
