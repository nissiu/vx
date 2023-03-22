<script type="text/html" id="create-post-work-hours-field">
	<div class="ts-work-hours-field ts-form-group">
		<label>
			{{ field.label }}
			<small>{{ field.description }}</small>
		</label>
		<template v-for="group, index in field.value">
			<form-group
				:ref="id(index)"
				class="work-hours-field ts-repeater-container"
				:popup-key="id(index)"
				:default-class="false"
				@clear="group.days = []"
				@save="$refs[id(index)].blur()"
				wrapper-class="prmr-popup"
			>
				<template #trigger>

					<div class="ts-field-repeater">
						<div class="ts-repeater-head">
							<label>
								<?= \Voxel\get_icon_markup( $this->get_settings_for_display('ts_calalt_icon') ) ?: \Voxel\svg( 'cal-alt.svg' ) ?>
								<?= _x( 'Work hour set', 'work hours field', 'voxel' ) ?>
							</label>
							<div class="ts-repeater-controller">
								<a href="#" @click.prevent="removeGroup(group)" class="ts-icon-btn ts-smaller">
									<?= \Voxel\get_icon_markup( $this->get_settings_for_display('trash_icon') ) ?: \Voxel\svg( 'minus.svg' ) ?>
								</a>
								<a href="#" class="ts-icon-btn ts-smaller" @click.prevent="$root.toggleRow($event)">
									<?= \Voxel\get_icon_markup( $this->get_settings_for_display('down_icon') ) ?: \Voxel\svg( 'chevron-down.svg' ) ?>
								</a>
							</div>
						</div>
						<div class="elementor-row medium form-field-grid">
							<div class="ts-form-group">
								<label>
									<?= _x( 'Select days', 'work hours field', 'voxel' ) ?>
									<small><?= _x( 'Select the days that this work hour set applies to', 'work hours field', 'voxel' ) ?></small>
								</label>
								<div class="ts-filter ts-popup-target ts-datepicker-input" :class="{'ts-filled': group.days.length}" @mousedown="$root.activePopup = id(index)">
									<?= \Voxel\get_icon_markup( $this->get_settings_for_display('ts_calalt_icon') ) ?: \Voxel\svg( 'cal-alt.svg' ) ?>
									<div v-if="group.days.length" class="ts-filter-text">{{ displayDays( group.days ) }}</div>
									<div v-else class="ts-filter-text"><?= _x( 'Choose day(s)', 'work hours field', 'voxel' ) ?></div>
								</div>
							</div>
							<template v-if="group.days.length">
								<div class="form-group">
									<label>
										<?= _x( 'Availability', 'work hours field', 'voxel' ) ?>
										<small><?= _x( 'Select availability for this set', 'work hours field', 'voxel' ) ?></small>
									</label>
									<div class="ts-term-dropdown ts-md-group ts-multilevel-dropdown inline-multilevel ts-inline-filter">
										<ul class="simplify-ul ts-term-dropdown-list min-scroll">
											<li v-for="label, status in field.props.statuses">
												<a href="#" @click.prevent="group.status = status" class="flexify">
													<div class="ts-radio-container">
														<label class="container-radio">
															<input :checked="group.status === status" type="radio" disabled hidden>
															<span class="checkmark"></span>
														</label>
													</div>
													<p>{{ label }}</p>
													<div class="ts-term-icon">
														<?= \Voxel\get_icon_markup( $this->get_settings_for_display('ts_clock_icon') ) ?: \Voxel\svg( 'clock.svg' ) ?>
													</div>
												</a>
											</li>
										</ul>
									</div>
								</div>
								<template v-if="group.status === 'hours'">
									<div class="ts-form-group">
										<label style="padding-bottom: 0;">
											<?= _x( 'Enter hours', 'work hours field', 'voxel' ) ?>
											<small><?= _x( 'Enter work hours for this set', 'work hours field', 'voxel' ) ?></small>
										</label>
									</div>
									<template v-if="group.hours.length">
										<div class="ts-form-group ">
											<div class="form-field-grid medium">
												<div v-for="hours in group.hours" class="ts-double-input has-controller flexify">
													<input type="time" class="ts-filter" v-model="hours.from">
													<input type="time" class="ts-filter" v-model="hours.to">
													<div class="">
														<a href="#" @click.prevent="removeHours(hours, group)" class="ts-icon-btn ts-smaller">
															<?= \Voxel\get_icon_markup( $this->get_settings_for_display('trash_icon') ) ?: \Voxel\svg( 'minus.svg' ) ?>
														</a>
													</div>
												</div>
											</div>
										</div>
									</template>
									<div class="ts-form-group">
										<a href="#" @click.prevent="addHours(group)" class="ts-repeater-add add-hours ts-btn ts-btn-3">
											<?= \Voxel\get_icon_markup( $this->get_settings_for_display('ts_add_icon') ) ?: \Voxel\svg( 'plus.svg' ) ?>
											<template v-if="group.hours.length >= 1">
												<?= _x( 'Add additional hours', 'work hours field', 'voxel' ) ?>
											</template>
											<template v-else>
												<?= _x( 'Add hours', 'work hours field', 'voxel' ) ?>
											</template>
										</a>
									</div>
								</template>
							</template>
						</div>
					</div>
				</template>
				<template #popup>
					<div class="ts-term-dropdown ts-md-group ts-multilevel-dropdown">
						<ul class="simplify-ul ts-term-dropdown-list min-scroll">
							<li v-for="label, key in field.props.weekdays">
								<a href="#" v-if="isDayAvailable( key, group )" @click.prevent="check( key, group.days )" class="flexify">
									<div class="ts-checkbox-container">
										<label class="container-checkbox">
											<input :checked="isChecked( key, group.days )" type="checkbox" disabled hidden>
											<span class="checkmark"></span>
										</label>
									</div>

									<p>{{ label }}</p>
									<div class="ts-term-icon">
										<?= \Voxel\get_icon_markup( $this->get_settings_for_display('ts_calalt_icon') ) ?: \Voxel\svg( 'cal-alt.svg' ) ?>
									</div>
								</a>
							</li>
						</ul>
					</div>
				</template>
			</form-group>
		</template>

		<a v-if="unusedDays.length" href="#" @click.prevent="addGroup" class="ts-repeater-add ts-btn ts-btn-3">
			<?= \Voxel\get_icon_markup( $this->get_settings_for_display('ts_add_icon') ) ?: \Voxel\svg( 'plus.svg' ) ?>
			<?= _x( 'Create work hour set', 'work hours field', 'voxel' ) ?>
		</a>
	</div>
</script>
