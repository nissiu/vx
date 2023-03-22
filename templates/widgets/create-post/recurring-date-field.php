<script type="text/html" id="create-post-recurring-date-field">
	<div class="ts-form-group">
		<label>
			{{ field.label }}
			<small>{{ field.description }}</small>
		</label>

		<template v-for="date, index in field.value">
			<div class="ts-repeater-container">
				<div v-if="field.props.allow_recurrence" class="ts-field-repeater">
					<div class="ts-repeater-head">
						<label>
							<?= \Voxel\get_icon_markup( $this->get_settings_for_display('ts_calalt_icon') ) ?: \Voxel\svg( 'cal-alt.svg' ) ?>
							<?= _x( 'Date', 'recurring date field', 'voxel' ) ?>
						</label>
						<div class="ts-repeater-controller">
							<a href="#" @click.prevent="remove(date)" class="ts-icon-btn ts-smaller">
								<?= \Voxel\get_icon_markup( $this->get_settings_for_display('trash_icon') ) ?: \Voxel\svg( 'trash-can.svg' ) ?>
							</a>
							<a href="#" class="ts-icon-btn ts-smaller" @click.prevent="$root.toggleRow($event)">
								<?= \Voxel\get_icon_markup( $this->get_settings_for_display('down_icon') ) ?: \Voxel\svg( 'chevron-down.svg' ) ?>
							</a>
						</div>
					</div>

					<div class="elementor-row medium form-field-grid">
						<div class="ts-form-group">
							<label><?= _x( 'Multi-day?', 'recurring date field', 'voxel' ) ?></label>
							<div class="switch-slider">
								<div class="onoffswitch">
								    <input type="checkbox" class="onoffswitch-checkbox" v-model="date.multiday">
								    <label class="onoffswitch-label" @click.prevent="date.multiday = !date.multiday"></label>
								</div>
							</div>
						</div>
						<form-group
							v-if="date.multiday"
							:popup-key="id(index,'from')"
							:ref="id(index,'from')"
							class="elementor-column elementor-col-100"
							wrapper-class="ts-availability-wrapper prmr-popup"
							@mousedown="$root.activePopup = id(index,'from')"
							@save="$refs[id(index,'from')].blur()"
							@clear="clearDate(date)"
						>
							<template #trigger>
								<div class="ts-double-input flexify">
									<div class="ts-filter ts-popup-target" :class="{'ts-filled': date.startDate !== null}">
										<?= \Voxel\get_icon_markup( $this->get_settings_for_display('ts_calendar_icon') ) ?: \Voxel\svg( 'calendar.svg' ) ?>
										<div class="ts-filter-text">
										{{ ! getStartDate(date)
											? 'From'
											: ( field.props.enable_timepicker
												? format( getStartDate( date ) )
												: formatDate( getStartDate( date ) )
											) }}
										</div>
									</div>

									<div class="ts-filter" :class="{'ts-filled': date.endDate !== null}">
										<?= \Voxel\get_icon_markup( $this->get_settings_for_display('ts_calendar_icon') ) ?: \Voxel\svg( 'calendar.svg' ) ?>
										<div class="ts-filter-text">
										{{ ! getEndDate(date)
											? 'To'
											: ( field.props.enable_timepicker
												? format( getEndDate( date ) )
												: formatDate( getEndDate( date ) )
											) }}
										</div>
									</div>
								</div>
							</template>
							<template #popup>
								<date-range-picker ref="rangePicker" :date="date" @save="$refs[id(index,'from')].blur()"></date-range-picker>
							</template>
						</form-group>
						<form-group
							v-else
							:popup-key="id(index,'from')"
							:ref="id(index,'from')"
							class="elementor-column elementor-col-100"
							wrapper-class="prmr-popup"
							@mousedown="$root.activePopup = id(index,'from')"
							@save="$refs[id(index,'from')].blur()"
							@clear="clearDate(date)"
						>
							<template #trigger>
								<div class="ts-filter ts-popup-target" :class="{'ts-filled': date.startDate !== null}">
									<?= \Voxel\get_icon_markup( $this->get_settings_for_display('ts_calendar_icon') ) ?: \Voxel\svg( 'calendar.svg' ) ?>
									<div class="ts-filter-text">
									{{ ! getStartDate(date)
										? 'From'
										: ( field.props.enable_timepicker
											? format( getStartDate( date ) )
											: formatDate( getStartDate( date ) )
										) }}
									</div>
								</div>
							</template>
							<template #popup>
								<date-picker v-model="date.startDate" @update:model-value="$refs[id(index,'from')].blur()"></date-picker>
							</template>
						</form-group>

						<div v-if="field.props.enable_timepicker" class="ts-double-input flexify force-equal">
							<div class="ts-form-group">
								<label><?= _x( 'Start time', 'recurring date field', 'voxel' ) ?></label>
								<input type="time" v-model="date.startTime" class="ts-filter">
							</div>

							<div class="ts-form-group">
								<label><?= _x( 'End time', 'recurring date field', 'voxel' ) ?></label>
								<input type="time" v-model="date.endTime" class="ts-filter">
							</div>
						</div>
						<div class="ts-form-group inner-form-group">
							<label><?= _x( 'Enable recurrence?', 'recurring date field', 'voxel' ) ?></label>
							<div class="switch-slider">
								<div class="onoffswitch">
									<input type="checkbox" v-model="date.repeat" class="onoffswitch-checkbox">
									<label class="onoffswitch-label" @click.prevent="date.repeat=!date.repeat"></label>
								</div>
							</div>
						</div>

						<template v-if="date.repeat">
							<div class="ts-form-group elementor-column elementor-col-100">
								<label><?= _x( 'Repeat every', 'recurring date field', 'voxel' ) ?></label>
								<div class="ts-double-input flexify force-equal">
									<input v-model="date.frequency" type="number" class="ts-filter">
									<form-group
										:popup-key="id(index,'unit')"
										:ref="id(index,'unit')"
										class="ts-filter ts-filled"
										:default-class="false"
										@mousedown="$root.activePopup = id(index,'unit')"
										:show-clear="false"
										save-label="<?= esc_attr( _x( 'Close', 'recurring date field', 'voxel' ) ) ?>"
									>
										<template #trigger>

											<div class="ts-filter-text">{{ field.props.units[ date.unit ] }}</div>
										</template>
										<template #popup>
											<div class="ts-term-dropdown ts-md-group">
												<ul class="simplify-ul ts-term-dropdown-list min-scroll">
													<li v-for="unit_label, unit in field.props.units">
														<a href="#" class="flexify" @click.prevent="date.unit = unit; $refs[id(index,'unit')].blur()">
															<div class="ts-checkbox-container">
																<label class="container-radio">
																	<input type="radio" :value="unit" :checked="date.unit === unit" disabled hidden>
																	<span class="checkmark"></span>
																</label>
															</div>
															<p>{{ unit_label }}</p>
														</a>
													</li>
												</ul>
											</div>
										</template>
									</form-group>
								</div>
							</div>
							<form-group
								:popup-key="id(index,'until')"
								:ref="id(index,'until')"
								class="elementor-column elementor-col-100"
								@clear="date.until = null"
								@save="$refs[id(index,'until')].blur()"
								wrapper-class="prmr-popup"
							>
								<template #trigger>
									<label><?= _x( 'Until', 'recurring date field', 'voxel' ) ?></label>
									<div class="ts-filter ts-popup-target" :class="{'ts-filled': date.until !== null}" @mousedown="$root.activePopup = id(index,'until')">
										<?= \Voxel\get_icon_markup( $this->get_settings_for_display('ts_calalt_icon') ) ?: \Voxel\svg( 'cal-alt.svg' ) ?>
										<div class="ts-filter-text">
											<template v-if="getUntilDate(date)">
												{{ formatDate( getUntilDate(date) ) }}
											</template>
											<template v-else>
												<?= _x( 'Choose date', 'recurring date field', 'voxel' ) ?>
											</template>
										</div>
									</div>
								</template>
								<template #popup>
									<date-picker v-model="date.until"></date-picker>
								</template>
							</form-group>
						</template>
					</div>
				</div>
			</div>
		</template>
		<a
			href="#"
			v-if="field.value.length < field.props.max_date_count"
			@click.prevent="add"
			class="ts-repeater-add ts-btn ts-btn-3"
		>
			<?= \Voxel\get_icon_markup( $this->get_settings_for_display('ts_add_icon') ) ?: \Voxel\svg( 'plus.svg' ) ?>
			<?= _x( 'Add date', 'recurring date field', 'voxel' ) ?>
		</a>
	</div>
</script>

<script type="text/html" id="recurring-date-picker">
	<div class="ts-form-group" ref="calendar">
		<input type="hidden" ref="input">
	</div>
</script>

<script type="text/html" id="recurring-date-range-picker">
	<div class="ts-popup-head flexify">
		<div class="ts-popup-name flexify">
			<?= \Voxel\get_icon_markup( $this->get_settings_for_display('ts_calendar_icon') ) ?: \Voxel\svg( 'calendar.svg' ) ?>
			<p>
				<a href="#" :class="{chosen: activePicker === 'start'}" @click.prevent="activePicker = 'start'">
					{{ startLabel }}
				</a>
				<span v-if="value.start"> &mdash; </span>
				<a href="#" v-if="value.start" :class="{chosen: activePicker === 'end'}" @click.prevent="activePicker = 'end'">
					{{ endLabel }}
				</a>
			</p>
		</div>
	</div>
	<div class="ts-booking-date ts-booking-date-range ts-form-group" ref="calendar">
		<input type="hidden" ref="input">
	</div>
</script>
