<?php
/**
 * Map widget template.
 *
 * @since 1.0
 */
?>

<?php if ( $source === 'current-post' ): ?>
	<div class="ts-map ts-map-autoload" data-config="<?= esc_attr( wp_json_encode( [
		'center' => [ 'lat' => $address['latitude'], 'lng' => $address['longitude'] ],
		'zoom' => $this->get_settings_for_display( 'ts_default_zoom' ),
		'minZoom' => $this->get_settings_for_display( 'ts_min_zoom' ),
		'maxZoom' => $this->get_settings_for_display( 'ts_max_zoom' ),
		'markers' => [ [
			'lat' => $address['latitude'],
			'lng' => $address['longitude'],
			'template' => \Voxel\_post_get_marker( $post ),
		] ],
	] ) ) ?>"></div>
<?php else: ?>
	<?php if ( $this->get_settings_for_display('ts_drag_search') === 'yes' ): ?>
		<div class="ts-map-drag">
			<?php if ( $this->get_settings_for_display('ts_drag_search_mode') === 'automatic' ): ?>
				<a href="#" class="ts-btn ts-btn-1 ts-drag-toggle <?= $this->get_settings_for_display( 'ts_drag_search_default' ) === 'checked' ? 'active' : '' ?>">
					<?= \Voxel\get_icon_markup( $this->get_settings_for_display('ts_search_icon') ) ?: \Voxel\svg( 'search.svg' ) ?>
					<?= _x( 'Search as I move the map', 'map', 'voxel' ) ?>
				</a>
			<?php else: ?>
				<a href="#" class="ts-btn ts-btn-1 ts-search-area hidden">
					<?= \Voxel\get_icon_markup( $this->get_settings_for_display('ts_search_icon') ) ?: \Voxel\svg( 'search.svg' ) ?>
					<?= _x( 'Search this area', 'map', 'voxel' ) ?>
				</a>
			<?php endif ?>
		</div>
	<?php endif ?>

	<div class="ts-map" data-config="<?= esc_attr( wp_json_encode( [
		'center' => [
			'lat' => $this->get_settings_for_display( 'ts_default_lat' ),
			'lng' => $this->get_settings_for_display( 'ts_default_lng' ),
		],
		'zoom' => $this->get_settings_for_display( 'ts_default_zoom' ),
		'minZoom' => $this->get_settings_for_display( 'ts_min_zoom' ),
		'maxZoom' => $this->get_settings_for_display( 'ts_max_zoom' ),
	] ) ) ?>"></div>
	<div class="ts-popup-anchor ts-marker"></div>
<?php endif ?>
