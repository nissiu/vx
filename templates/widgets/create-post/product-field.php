<script type="text/html" id="create-post-product-field">
	<div class="ts-form-group ts-product-field form-field-grid">
		<div v-if="!field.required" class="ts-form-group">
			<label>{{ field.label }}<small>{{ field.description }}</small></label>
			<div class="switch-slider">
				<div class="onoffswitch">
				    <input type="checkbox" class="onoffswitch-checkbox" v-model="field.value.enabled">
				    <label class="onoffswitch-label" @click.prevent="field.value.enabled = !field.value.enabled"></label>
				</div>
			</div>
		</div>
		<template v-if="field.required || field.value.enabled">
			<template v-if="field.props.is_using_price_id">
				<div class="ts-form-group">
					<label><?= _x( 'Price ID', 'product field', 'voxel' ) ?></label>
					<div class="input-container">
						<input type="text" class="ts-filter" v-model="field.value.price_id">
					</div>
				</div>
			</template>
			<template v-else>
				<div v-if="field.props.has_base_price" class="ts-form-group">
					<label>{{ l10n.base_price }}</label>
					<div class="input-container">
						<input
							type="number" class="ts-filter" v-model="field.value.base_price" min="0"
							placeholder="<?= esc_attr( _x( 'Enter base price', 'product field', 'voxel' ) ) ?>"
						>
						<span class="input-suffix"><?= \Voxel\get('settings.stripe.currency') ?></span>
					</div>
				</div>
				<div v-if="field.props.payment_mode === 'subscription'" class="ts-form-group">
					<label><?= _x( 'Subscription interval', 'product field', 'voxel' ) ?></label>
					<div class="ts-repeater-container">
						<div class="ts-field-repeater">
							<div class="elementor-row medium form-field-grid">
								<div class="ts-form-group">
									<label><?= _x( 'Repeat every', 'product field', 'voxel' ) ?></label>
									<input v-model="interval.count" type="number" class="ts-filter" min="1" :max="field.props.interval_limits[ interval.unit ] || 365">
								</div>
								<div class="ts-form-group">
									<div class="ts-term-dropdown ts-md-group ts-inline-filter inline-multilevel">
										<ul class="simplify-ul ts-term-dropdown-list min-scroll">
											<li v-for="unit_label, unit in field.props.intervals">
												<a href="#" class="flexify" @click.prevent="interval.unit = unit; $refs[field.key+'.interval'].blur()">
													<div class="ts-checkbox-container">
														<label class="container-radio">
															<input type="radio" :value="unit" :checked="interval.unit === unit" disabled hidden>
															<span class="checkmark"></span>
														</label>
													</div>
													<p>{{ unit_label }}</p>
													<div class="ts-term-icon">
														<span><?= \Voxel\get_icon_markup( $this->get_settings_for_display('ts_calalt_icon') ) ?: \Voxel\svg( 'cal-alt.svg' ) ?></span>
													</div>
												</a>
											</li>
										</ul>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</template>
			<template v-if="field.props.product_mode === 'booking'">
				<template v-if="field.props.calendar.type === 'booking'">
					<div class="ts-double-input flexify">
						<div class="ts-form-group">
							<label><?= _x( 'Make available next', 'product field', 'voxel' ) ?></label>
							<div class="input-container">
								<input type="number" class="ts-filter" placeholder="<?= esc_attr( _x( 'e.g. 30', 'product field', 'voxel' ) ) ?>" v-model="calendar.make_available_next">
								<span class="input-suffix"><?= _x( 'days', 'product field', 'voxel' ) ?></span>
							</div>
						</div>
						<div v-if="field.props.calendar.format === 'days'" class="ts-form-group">
							<label>{{ l10n.instances_per_day }}</label>
							<input type="number" class="ts-filter" v-model="calendar.bookable_per_instance">
						</div>
						<div v-if="field.props.calendar.format === 'slots'" class="ts-form-group">
							<label>{{ l10n.instances_per_slot }}</label>
							<input type="number" class="ts-filter" v-model="calendar.bookable_per_instance">
						</div>
					</div>
					<div v-if="field.props.calendar.format === 'slots'" class="ts-form-group ts-product-timeslots">
						<field-product-timeslots></field-product-timeslots>
					</div>
					<form-group v-if="field.props.calendar.format === 'days'" :popup-key="field.key+'.weekdays'" ref="weekdayExclusions" @save="saveWeekdayExclusions" @clear="clearWeekdayExclusions">
						<template #trigger>
							<label><?= _x( 'Exclude days of week', 'product field', 'voxel' ) ?></label>
							<div class="ts-filter ts-popup-target" :class="{'ts-filled': state.weekdays_display_value.length}" @mousedown="$root.activePopup = field.key+'.weekdays'">
								<span><?= \Voxel\get_icon_markup( $this->get_settings_for_display('ts_calminus_icon') ) ?: \Voxel\svg( 'calendar-minus.svg' ) ?></span>
								<div class="ts-filter-text">{{
									state.weekdays_display_value || <?= wp_json_encode( _x( 'Click to exclude weekdays', 'product field', 'voxel' ) ) ?>
								}}</div>
							</div>
						</template>
						<template #popup>
							<div class="ts-term-dropdown ts-md-group ts-multilevel-dropdown">
								<ul class="simplify-ul ts-term-dropdown-list min-scroll">
									<li v-for="day_label, day_key in field.props.weekdays">
										<a href="#" class="flexify" @click.prevent="toggleWeekdayExclusion( day_key )">
											<div class="ts-checkbox-container">
												<label class="container-checkbox">
													<input type="checkbox" :value="day_key" :checked="state.excluded_weekdays[day_key]" disabled hidden>
													<span class="checkmark"></span>
												</label>
											</div>
											<p>{{ day_label }}</p>
											<div class="ts-term-icon">
												<span><?= \Voxel\get_icon_markup( $this->get_settings_for_display('ts_calminus_icon') ) ?: \Voxel\svg( 'calendar-minus.svg' ) ?></span>
											</div>
										</a>
									</li>
								</ul>
							</div>
						</template>
					</form-group>

					<div class="ts-form-group">
						<label>
							<?= _x( 'Calendar', 'product field', 'voxel' ) ?>
							<small><?= _x( 'Availability visualization based on your settings. Click to exclude specific days', 'product field', 'voxel' ) ?></small>
						</label>
						<field-product-calendar ref="datePicker"></field-product-calendar>
					</div>
				</template>


				<template v-else-if="field.props.calendar.type === 'recurring-date'">
					<div class="ts-form-group">
						<label>
							<?= _x( 'Availability', 'product field', 'voxel' ) ?>
							<small><?= _x( 'How many days in advance can dates be booked?', 'product field', 'voxel' ) ?></small>
						</label>
						<div class="input-container">
							<input type="number" class="ts-filter" placeholder="<?= esc_attr( _x( 'e.g. 30', 'product field', 'voxel' ) ) ?>" v-model="calendar.make_available_next" min="0">
							<span class="input-suffix"><?= _x( 'days', 'product field', 'voxel' ) ?></span>
						</div>
					</div>

					<!-- <div class="ts-form-group">
						<label>Quantity<small>Set the maximum amount of bookings you will allow per date</small></label>
						<input type="number" class="ts-filter" v-model="calendar.bookable_per_instance">
					</div> -->

					<!-- <div class="ts-field-repeater">
						<div class="ts-repeater-head">
							<label><i class="lar la-calendar"></i>Upcoming bookable dates</label>
							<div class="ts-repeater-controller">
								<a href="#" class="ts-icon-btn">
									<i aria-hidden="true" class="las la-angle-down"></i>
								</a>
							</div>
						</div>
						<div class="elementor-row medium form-field-grid">
							<div class="ts-form-group" v-if="state.recurring_dates.length">

								<ul class="timeslot-list simplify-ul">
									<li v-for="date in state.recurring_dates">
										<i class="lar la-calendar"></i>
										<span>
											{{ formatRecurrence( date ) }}
										</span>

									</li>
								</ul>
							</div>
						</div>
					</div> -->
				</template>
			</template>

			<template v-if="!field.props.is_using_price_id">
				<div v-for="addition in field.props.additions" class="ts-form-group ts-addition">
					<div class="ts-field-repeater">
						<div class="ts-repeater-head">
							<label>
								<span v-html="addition.icon_markup"></span>
								{{ addition.label }}
							</label>
							<div class="ts-repeater-controller">
								<a href="#" class="ts-icon-btn ts-smaller" @click.prevent="$root.toggleRow($event)">
									<?= \Voxel\get_icon_markup( $this->get_settings_for_display('down_icon') ) ?: \Voxel\svg( 'chevron-down.svg' ) ?>
								</a>
							</div>
						</div>
						<div class="elementor-row form-field-grid medium">
							<!-- <div class="ts-form-group">
								<span>{{ addition.description }}</span>
							</div> -->

							<div class="switch-slider ts-form-group" v-if="!addition.required">
								<label><?= _x( 'Enable addition', 'product field', 'voxel' ) ?></label>
								<div class="onoffswitch">
									<input type="checkbox" class="onoffswitch-checkbox" v-model="addition.values.enabled">
									<label class="onoffswitch-label" @click.prevent="addition.values.enabled = !addition.values.enabled"></label>
								</div>
							</div>

							<template v-if="addition.required || addition.values.enabled">
								<template v-if="addition.type === 'checkbox'">
									<div class="ts-form-group">
										<label><?= _x( 'Price', 'product field', 'voxel' ) ?></label>
										<div class="input-container">
											<input type="number" v-model="addition.values.price" class="ts-filter" placeholder="<?= esc_attr( _x( 'e.g. 30', 'product field', 'voxel' ) ) ?>" min="0">
											<span class="input-suffix"><?= \Voxel\get('settings.stripe.currency') ?></span>
										</div>
									</div>
								</template>
								<template v-if="addition.type === 'numeric'">
									<div class="ts-form-group">
										<div class="input-container">
											<input type="number" v-model="addition.values.price" class="ts-filter" placeholder="<?= esc_attr( _x( 'Price per unit', 'product field', 'voxel' ) ) ?>" min="0">
											<span class="input-suffix"><?= \Voxel\get('settings.stripe.currency') ?> <?= _x( 'per unit', 'product field', 'voxel' ) ?></span>
										</div>
									</div>
									<div class="ts-double-input flexify product-units">
										<div class="ts-form-group">
											<div class="input-container">
												<input
													type="number"
													v-model="addition.values.min"
													class="ts-filter"
													placeholder="<?= esc_attr( _x( 'Minimum', 'product field', 'voxel' ) ) ?>"
													min="0"
												>
												<span class="input-suffix"><?= _x( 'Min units', 'product field', 'voxel' ) ?></span>
											</div>
										</div>
										<div class="ts-form-group">
											<div class="input-container">
												<input
													type="number"
													v-model="addition.values.max"
													class="ts-filter"
													placeholder="<?= esc_attr( _x( 'Maximum', 'product field', 'voxel' ) ) ?>"
													min="0"
												>
												<span class="input-suffix"><?= _x( 'Max units', 'product field', 'voxel' ) ?></span>
											</div>
										</div>
									</div>
								</template>
								<template v-if="addition.type === 'select'">
									<template v-for="choice in addition.choices">
										<div class="ts-form-group">
											<label>{{ choice.label }}</label>
											<div class="switch-slider">
												<div class="onoffswitch">
													<input type="checkbox" class="onoffswitch-checkbox" v-model="addition.values.choices[choice.value].enabled">
													<label class="onoffswitch-label" @click.prevent="addition.values.choices[choice.value].enabled = !addition.values.choices[choice.value].enabled"></label>
												</div>
											</div>
										</div>
										<div v-if="addition.values.choices[choice.value].enabled" class="ts-form-group">
											<label><?= _x( 'Price', 'product field', 'voxel' ) ?></label>
											<div class="input-container">
												<input type="number" v-model="addition.values.choices[ choice.value ].price" class="ts-filter" min="0">
												<span class="input-suffix"><?= \Voxel\get('settings.stripe.currency') ?></span>
											</div>
										</div>
									</template>
								</template>
							</template>
						</div>
					</div>
				</div>
			</template>
			<template v-if="field.props.deliverables.enabled">
				<div class="ts-form-group">
					<field-file
						:field="deliverables"
						:sortable="true"
						:show-library="false"
						:preview-images="false"
						ref="deliverables"
					></field-file>
				</div>
			</template>
			<template v-if="(field.props.product_mode === 'booking' && field.props.calendar.type === 'recurring-date' )">
				<div class="ts-form-group vx-disabled">
					<label>
						<?= _x( 'Manage stock', 'product field', 'voxel' ) ?>
						<small><?= _x( 'Not available on this build', 'product field', 'voxel' ) ?></small>
					</label>
					<div class="switch-slider">
						<div class="onoffswitch">
							<input type="checkbox" class="onoffswitch-checkbox">
							<label class="onoffswitch-label"></label>
						</div>
					</div>
				</div>
			</template>
			<template v-if="field.props.notes.enabled">
				<div class="ts-form-group">
					<label>
						<?= _x( 'Enable notes', 'product field', 'voxel' ) ?>
						<small><?= _x( 'Notes are shared with customer when an order is placed', 'product field', 'voxel' ) ?></small>
					</label>
					<div class="switch-slider">
						<div class="onoffswitch">
							<input type="checkbox" class="onoffswitch-checkbox" v-model="field.value.notes_enabled">
							<label class="onoffswitch-label" @click.prevent="field.value.notes_enabled = !field.value.notes_enabled"></label>
						</div>
					</div>
				</div>
				<div v-if="field.value.notes_enabled" class="ts-form-group ts-product-notes">
					<label>
						{{ l10n.notes.label }}
						<small>{{ l10n.notes.description }}</small>
					</label>
					<textarea v-model="field.value.notes" class="ts-filter min-scroll" :placeholder="l10n.notes.placeholder" rows="5"></textarea>
				</div>
			</template>
		</template>
	</div>
</script>

<script type="text/html" id="create-post-product-timeslots">
	<label><?= _x( 'Timeslots', 'product field', 'voxel' ) ?></label>
	<div class="ts-repeater-container">
		<div v-for="slotGroup, groupIndex in timeslots" class="ts-field-repeater" :class="{collapsed: slotGroup._collapsed}">
			<div class="ts-repeater-head">
				<label>
					<?= \Voxel\get_icon_markup( $this->get_settings_for_display('ts_clock_icon') ) ?: \Voxel\svg( 'clock.svg' ) ?>
					<?= _x( 'Time slot group', 'product field', 'voxel' ) ?>
				</label>
				<div class="ts-repeater-controller">
					<a href="#" @click.prevent="removeGroup(slotGroup)" class="ts-icon-btn ts-smaller">
						<?= \Voxel\get_icon_markup( $this->get_settings_for_display('trash_icon') ) ?: \Voxel\svg( 'minus.svg' ) ?>
					</a>
					<a href="#" class="ts-icon-btn ts-smaller" @click.prevent="slotGroup._collapsed = !slotGroup._collapsed">
						<?= \Voxel\get_icon_markup( $this->get_settings_for_display('down_icon') ) ?: \Voxel\svg( 'chevron-down.svg' ) ?>
					</a>
				</div>
			</div>
			<div class="elementor-row medium form-field-grid">
				<form-group
					:popup-key="groupKey(groupIndex)"
					:ref="groupKey(groupIndex)"
					class="ts-form-group"
					@save="saveDays(groupIndex)"
					@clear="clearDays(slotGroup)"
					wrapper-class="prmr-popup"
				>
					<template #trigger>
						<label><?= _x( 'Choose days', 'product field', 'voxel' ) ?></label>

						<div class="ts-filter ts-popup-target" @mousedown="$root.activePopup = groupKey(groupIndex)" :class="{'ts-filled': slotGroup.days.length}">
							<span><?= \Voxel\get_icon_markup( $this->get_settings_for_display('ts_calalt_icon') ) ?: \Voxel\svg( 'cal-alt.svg' ) ?></span>
							<div class="ts-filter-text">{{ daysLabel(
								slotGroup,
								<?= wp_json_encode( _x( 'Choose day(s)', 'product field', 'voxel' ) ) ?>
							) }}</div>
						</div>
					</template>
					<template #popup>
						<div class="ts-term-dropdown ts-md-group ts-multilevel-dropdown">
							<ul class="simplify-ul ts-term-dropdown-list min-scroll">
								<li v-for="day_label, day_key in field.props.weekdays">
									<a
										href="#"
										class="flexify"
										v-if="isDayAvailable( day_key, slotGroup )"
										@click.prevent="toggleDay( day_key, slotGroup )"
									>	<div class="ts-checkbox-container">
											<label class="container-checkbox">
												<input type="checkbox" :value="day_key" :checked="isDayUsed( day_key, slotGroup )" disabled hidden>
												<span class="checkmark"></span>
											</label>
										</div>
										<p>{{ day_label }}</p>
										<div class="ts-term-icon">
											<span><?= \Voxel\get_icon_markup( $this->get_settings_for_display('ts_calalt_icon') ) ?: \Voxel\svg( 'cal-alt.svg' ) ?></span>
										</div>
									</a>
								</li>
							</ul>
						</div>
					</template>
				</form-group>

				<div class="ts-form-group">
					<label><?= _x( 'Create time slots', 'product field', 'voxel' ) ?></label>
					<div class="ts-double-input flexify force-equal">
						<form-group
							save-label="<?= esc_attr( _x( 'Add', 'product field', 'voxel' ) ) ?>"
							:show-clear="false"
							:popup-key="groupKey(groupIndex, 'add')"
							:ref="groupKey(groupIndex, 'add')"
							:default-class="false"
							@save="addSlot(slotGroup, groupIndex)"
							@clear="closeSlotPopup(groupIndex)"
						>
							<template #trigger>
								<a
									href="#"
									class="ts-btn ts-btn-3 ts-popup-target"
									@click.prevent
									@mousedown="$root.activePopup = groupKey(groupIndex, 'add')"
								>
									<?= \Voxel\get_icon_markup( $this->get_settings_for_display('ts_add_icon') ) ?: \Voxel\svg( 'plus.svg' ) ?>
									<?= _x( 'Add', 'product field', 'voxel' ) ?>
								</a>
							</template>
							<template #popup>
								<div class="ts-form-group">
									<label>
										<?= _x( 'Create slot', 'product field', 'voxel' ) ?>
										<small><?= _x( 'Enter the start and end range', 'product field', 'voxel' ) ?></small>
									</label>
								</div>
								<div>
									<input class="border-top" type="time" v-model="create.from">
								</div>
								<div>
									<input class="border-top" type="time" v-model="create.to">
								</div>
							</template>
						</form-group>

						<form-group
							save-label="<?= esc_attr( _x( 'Generate', 'product field', 'voxel' ) ) ?>"
							:show-clear="false"
							:popup-key="groupKey(groupIndex, 'generate')"
							:ref="groupKey(groupIndex, 'generate')"
							:default-class="false"
							@save="generateSlots(slotGroup, groupIndex)"
							@clear="closeGeneratePopup(groupIndex)"
						>
							<template #trigger>
								<a
									href="#"
									class="ts-btn ts-btn-3 ts-popup-target"
									@click.prevent
									@mousedown="$root.activePopup = groupKey(groupIndex, 'generate')"
								>
									<?= \Voxel\get_icon_markup( $this->get_settings_for_display('ts_list_icon') ) ?: \Voxel\svg( 'list.svg' ) ?>
									<?= _x( 'Generate', 'product field', 'voxel' ) ?>
								</a>
							</template>
							<template #popup>
								<div class="ts-form-group">
									<label>
										<?= _x( 'Time range', 'product field', 'voxel' ) ?>
										<small><?= _x( 'Enter the start and end range', 'product field', 'voxel' ) ?></small>
									</label>
								</div>
								<div>
									<input class="border-top" type="time" v-model="generate.from">
								</div>
								<div>
									<input class="border-both" type="time" v-model="generate.to">
								</div>
								<div class="ts-form-group">
									<label>
										<?= _x( 'Slot length', 'product field', 'voxel' ) ?>
										<small><?= _x( 'In minutes', 'product field', 'voxel' ) ?></small>
									</label>
								</div>
								<div>
									<input class="border-top" type="number" v-model="generate.length" min="5">
								</div>
							</template>
						</form-group>
					</div>
				</div>
				<div v-if="slotGroup.slots.length" class="ts-form-group">
					<label>
						<?= _x( 'Available time slots', 'product field', 'voxel' ) ?>
						<small><?= _x( 'Time slots you created', 'product field', 'voxel' ) ?></small>
					</label>
					<ul class="timeslot-list simplify-ul">
						<li v-for="slot, slotIndex in slotGroup.slots">
							<a href="#" @click.prevent="removeSlot(slot, slotGroup)" class="delete-timeslot">
								<?= \Voxel\get_icon_markup( $this->get_settings_for_display('ts_rtimeslot_icon') ) ?: \Voxel\svg( 'circle-minus.svg' ) ?>
							</a>
							<span>{{ displaySlot(slot) }}</span>
						</li>
					</ul>
				</div>
			</div>
		</div>
	</div>
	<a v-if="unusedDays.length" href="#" @click.prevent="addSlotGroup" class="ts-repeater-add ts-btn ts-btn-3">
		<?= \Voxel\get_icon_markup( $this->get_settings_for_display('ts_add_icon') ) ?: \Voxel\svg( 'plus.svg' ) ?>
		<?= _x( 'Add timeslot group', 'product field', 'voxel' ) ?>
	</a>
</script>
