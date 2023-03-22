<div class="ts-no-posts <?= ! empty( $results['ids'] ) ? 'hidden' : '' ?>">
	<?= \Voxel\get_icon_markup( $this->get_settings_for_display('ts_noresults_icon') ) ?: \Voxel\svg( 'search.svg' ) ?>
	<p><?= _x( 'There are no listings matching your search.', 'post feed', 'voxel' ) ?></p>
	<a href="#" class="ts-btn ts-btn-1 ts-btn-large ts-feed-reset">
		<?= \Voxel\get_icon_markup( $this->get_settings_for_display('ts_sf_form_btn_reset_icon') ) ?: \Voxel\svg( 'reload.svg' ) ?>
		<?= _x( 'Reset', 'post feed', 'voxel' ) ?>
	</a>
</div>
