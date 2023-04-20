<script type="text/html" id="create-post-repeater-field">
	<div class="ts-form-group ts-repeater" ref="container">
		<label>
			{{ field.label }}
			<small>{{ field.description }}</small>
		</label>
		<div class="ts-repeater-container" ref="list">
			<div v-for="row, row_index in rows" class="ts-field-repeater" :class="{collapsed: row['meta:state'].collapsed}" :data-index="row_index" :key="row['meta:state'].id">
				<div class="ts-repeater-head">
					<label>
						<?= \Voxel\get_icon_markup( $this->get_settings_for_display('ts_list_icon') ) ?: \Voxel\svg( 'menu.svg' ) ?>
						{{ row['meta:state'].label }}
					</label>
					<div class="ts-repeater-controller">
						<a href="#" @click.prevent="deleteRow(row)" class="ts-icon-btn ts-smaller">
							<?= \Voxel\get_icon_markup( $this->get_settings_for_display('trash_icon') ) ?: \Voxel\svg( 'trash-can.svg' ) ?>
						</a>
						<a href="#" class="ts-icon-btn ts-smaller" @click.prevent="row['meta:state'].collapsed = !row['meta:state'].collapsed">
							<?= \Voxel\get_icon_markup( $this->get_settings_for_display('down_icon') ) ?: \Voxel\svg( 'chevron-down.svg' ) ?>
						</a>
					</div>
				</div>
				<div class="elementor-row medium form-field-grid">
					<template v-if="field.props.additions.enabled">
						<div class="ts-double-input flexify">
							<div class="ts-form-group">
								<label><?= _x( 'Label', 'repeater field', 'voxel' ) ?></label>
								<div class="input-container">
									<input type="text" placeholder="<?= esc_attr( _x( 'Item label', 'repeater field', 'voxel' ) ) ?>" class="ts-filter" v-model="row['meta:additions'].label">
								</div>
							</div>
							<div class="ts-form-group">
								<label><?= _x( 'Price', 'repeater field', 'voxel' ) ?></label>
								<div class="input-container">
									<input type="number" placeholder="<?= esc_attr( _x( 'Item price', 'repeater field', 'voxel' ) ) ?>" class="ts-filter" v-model="row['meta:additions'].price" min="0">
									<span class="input-suffix"><?= \Voxel\get('settings.stripe.currency') ?></span>
								</div>
							</div>
						</div>
						<div class="ts-form-group">
							<label>
								<?= _x( 'Enable quantity', 'repeater field', 'voxel' ) ?>
								<small><?= _x( 'Allows customer to purchase this item multiple times with a single order', 'repeater field', 'voxel' ) ?></small>
							</label>
							<div class="switch-slider">
								<div class="onoffswitch">
									<input type="checkbox" class="onoffswitch-checkbox" v-model="row['meta:additions'].has_quantity">
									<label class="onoffswitch-label" @click.prevent="row['meta:additions'].has_quantity = !row['meta:additions'].has_quantity"></label>
								</div>
							</div>
						</div>
						<div v-if="row['meta:additions'].has_quantity" class="ts-double-input flexify product-units">
							<div class="ts-form-group">
								<div class="input-container">
									<input
										type="number"
										v-model="row['meta:additions'].min"
										class="ts-filter"
										placeholder="<?= esc_attr( _x( 'Minimum', 'repeater field', 'voxel' ) ) ?>"
										min="0"
									>
									<span class="input-suffix"><?= _x( 'Min units', 'repeater field', 'voxel' ) ?></span>
								</div>
							</div>
							<div class="ts-form-group">
								<div class="input-container">
									<input
										type="number"
										v-model="row['meta:additions'].max"
										class="ts-filter"
										placeholder="<?= esc_attr( _x( 'Maximum', 'repeater field', 'voxel' ) ) ?>"
										min="0"
									>
									<span class="input-suffix"><?= _x( 'Max units', 'repeater field', 'voxel' ) ?></span>
								</div>
							</div>
						</div>
					</template>
					<template v-for="subfield in row">
						<template v-if="subfield.key !== 'meta:additions' && subfield.key !== 'meta:state'">
							<component
								:field="subfield"
								:is="'field-'+subfield.type"
								:ref="'row#'+row['meta:state'].id+':'+subfield.key"
								:index="row['meta:state'].id"
								:key="row['meta:state'].id"
								v-if="$root.conditionsPass(subfield)"
							></component>
						</template>
					</template>
				</div>
			</div>
		</div>

		<a href="#" class="ts-repeater-add ts-btn ts-btn-3" @click.prevent="addRow">
			<?= \Voxel\get_icon_markup( $this->get_settings_for_display('ts_add_icon') ) ?: \Voxel\svg( 'plus.svg' ) ?>
			{{ field.props.l10n.add_row }}
		</a>
	</div>
</script>
