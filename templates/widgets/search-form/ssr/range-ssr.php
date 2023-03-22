<?php
$display_value = function( $value ) {
	if ( $this->props['format_numeric'] ) {
		$value = number_format_i18n( $value );
	}

	return $this->props['format_prefix'].$value.$this->props['format_suffix'];
};

$value = $this->parse_value( $this->get_value() );
$config = $this->get_frontend_config();
$label = $value ? join( ' &mdash; ', array_map( $display_value, $value ) ) : null;
$scale = $config['props']['range_end'] - $config['props']['range_start'];

?>

<?php if ( ( $this->elementor_config['display_as'] ?? 'popup' ) === 'inline' ): ?>
	<div v-if="false" class="<?= $args['wrapper_class'] ?> ts-inline-filter range-ssr">
		<?php if ( ! empty( $args['show_labels'] ) ): ?>
			<label><?= $this->get_label() ?></label>
		<?php endif ?>

		<?php if ( $this->props['handles'] === 'single' ): ?>
			<?php $default = $this->props['compare'] === 'outside_range' ? $config['props']['range_start'] : $config['props']['range_end'] ?>
			<?php $percent = $value
				? \Voxel\clamp( ( ( $value[0] - $config['props']['range_start'] ) / $scale ) * 100, 0, 100 )
				: ( $this->props['compare'] === 'outside_range' ? 0 : 100 ) ?>
			<?php $translate = $this->props['compare'] === 'outside_range' ? $percent : 0 ?>
			<?php $css_scale = $this->props['compare'] === 'outside_range' ? 1 - ( $percent / 100 ) : $percent / 100 ?>
			<div class="range-slider-wrapper">
				<div class="range-value"><?= $value ? $label : $display_value( $default ) ?></div>
				<div class="range-slider noUi-target noUi-ltr noUi-horizontal noUi-txt-dir-ltr">
					<div class="noUi-base">
						<div class="noUi-connects">
							<div class="noUi-connect" style="transform: translate(<?= $translate ?>%, 0px) scale(<?= $css_scale ?>, 1);"></div>
						</div>
						<div class="noUi-origin" style="transform: translate(-<?= 1000 - ( $percent * 10 ) ?>%, 0px); z-index: 4;">
							<div class="noUi-handle noUi-handle-lower">
								<div class="noUi-touch-area"></div>
							</div>
						</div>
					</div>
				</div>
			</div>
		<?php else: ?>
			<?php
			$default = $this->props['compare'] === 'outside_range'
				? [ $config['props']['range_start'], $config['props']['range_start'] ]
				: [ $config['props']['range_start'], $config['props']['range_end'] ];

			$percent_start = 0;
			$percent_end = $this->props['compare'] === 'outside_range' ? 0 : 100;
			if ( $value ) {
				$percent_start = \Voxel\clamp( ( ( $value[0] - $config['props']['range_start'] ) / $scale ) * 100, 0, 100 );
				$percent_end = \Voxel\clamp( ( ( $value[1] - $config['props']['range_start'] ) / $scale ) * 100, 0, 100 );
			} ?>
			<div class="range-slider-wrapper">
				<div class="range-value"><?= $value ? $label : join( ' &mdash; ', array_map( $display_value, $default ) ) ?></div>
				<div class="range-slider noUi-target noUi-ltr noUi-horizontal noUi-txt-dir-ltr">
					<div class="noUi-base">
						<div class="noUi-origin" style="transform: translate(-<?= 1000 - ( $percent_start * 10 ) ?>%, 0px); z-index: 4;">
							<div class="noUi-handle noUi-handle-lower">
								<div class="noUi-touch-area"></div>
							</div>
						</div>
						<div class="noUi-origin" style="transform: translate(-<?= 1000 - ( $percent_end * 10 ) ?>%, 0px); z-index: 4;">
							<div class="noUi-handle noUi-handle-lower">
								<div class="noUi-touch-area"></div>
							</div>
						</div>

						<?php if ( $this->props['compare'] === 'outside_range' ): ?>
							<div class="noUi-connects">
								<div class="noUi-connect" style="transform: translate(0%, 0px) scale(<?= $percent_start / 100 ?>, 1);"></div>
								<div class="noUi-connect" style="transform: translate(<?= $percent_end ?>%, 0px) scale(<?= 1 - ( $percent_end / 100 ) ?>, 1);"></div>
							</div>
						<?php else: ?>
							<div class="noUi-connects">
								<div class="noUi-connect" style="transform: translate(<?= $percent_start ?>%, 0px) scale(<?= ( $percent_end - $percent_start ) / 100 ?>, 1);"></div>
							</div>
						<?php endif ?>
					</div>
				</div>
			</div>
		<?php endif ?>
	</div>
<?php else: ?>
	<div v-if="false" class="<?= $args['wrapper_class'] ?>">
		<?php if ( ! empty( $args['show_labels'] ) ): ?>
			<label><?= $this->get_label() ?></label>
		<?php endif ?>
		<div class="ts-filter ts-popup-target <?= $value ? 'ts-filled' : '' ?>">
			<span><?= \Voxel\get_icon_markup( $this->get_icon() ) ?></span>
			<div class="ts-filter-text"><?= $value ? $label : ( $this->props['placeholder'] ?: $this->props['label'] ) ?></div>
			<div class="ts-down-icon"></div>
		</div>
	</div>
<?php endif ?>