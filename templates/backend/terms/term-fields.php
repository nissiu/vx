<?php
/**
 * Term custom fields.
 *
 * @since 1.0
 */
if ( ! defined('ABSPATH') ) {
	exit;
} ?>

<tr class="form-field">
	<th>Custom fields</th>
	<td>
		<div id="voxel-term-settings" class="ts-theme-options ts-container" data-config="<?= esc_attr( wp_json_encode( [
			'fields' => $fields,
		] ) ) ?>">
			<div class="ts-row wrap-row">
				<div class="ts-form-group ts-col-1-1">
					<label>Icon</label>
					<icon-picker v-model="fields.icon"></icon-picker>
				</div>

				<div class="ts-form-group ts-col-1-1">
					<label>Image</label>
					<media-select
						v-model="fields.image"
						:file-type="['image/jpeg','image/png','image/webp']"
						:multiple="false"
					></media-select>
				</div>

				<div class="ts-form-group ts-col-1-1">
					<label>Area</label>
					<input type="text" ref="addressInput" :value="fields.area.address">
					<div v-if="fields.area.swlat">
						<p>
							SW {{ fields.area.swlat }},{{ fields.area.swlng }};
							NE: {{ fields.area.nelat }},{{ fields.area.nelng }}
						</p>
					</div>
				</div>

				<div class="ts-form-group ts-col-1-1 hide">
					<!-- <pre>{{ $data }}</pre> -->
					<input type="text" name="voxel_icon" :value="fields.icon">
					<input type="text" name="voxel_image" :value="fields.image">
					<input type="text" name="voxel_gif" :value="fields.gif">
					<input type="text" name="voxel_area" :value="JSON.stringify(fields.area)">
				</div>
			</div>
		</div>
	</td>
</tr>
