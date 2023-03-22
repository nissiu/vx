<?php
/**
 * Product form widget template.
 *
 * @since 1.0
 */
if ( ! defined('ABSPATH') ) {
	exit;
}

$deferred_templates = [];
$deferred_templates[] = locate_template( 'templates/widgets/product-form/date-picker.php' );
$deferred_templates[] = locate_template( 'templates/widgets/product-form/date-range-picker.php' );
$deferred_templates[] = locate_template( 'templates/widgets/product-form/information-fields.php' );
$deferred_templates[] = locate_template( 'templates/widgets/create-post/_media-popup.php' );
?>

<script type="text/json" class="vxconfig"><?= wp_specialchars_decode( wp_json_encode( $config ) ) ?></script>
<div
	class="ts-form ts-booking-form min-scroll"
	data-post-id="<?= absint( $post->get_id() ) ?>"
	data-field-key="<?= esc_attr( $field->get_key() ) ?>"
	v-cloak
>
	<div v-show="step === 'main'" class="ts-booking-main">
		<div class="booking-head">
			<?= \Voxel\get_icon_markup( $this->get_settings_for_display('stepone_ico') ) ?: \Voxel\svg( 'bag.svg' ) ?>
			<p><?= $this->get_settings_for_display('prform_stepone_text') ?></p>
		</div>

		<?php if ( $product_type->get_product_mode() === 'booking' ): ?>
			<?php if ( $product_type->config( 'calendar.type' ) === 'booking' ): ?>
				<?php if ( $product_type->config( 'calendar.format' ) === 'days' && $product_type->config( 'calendar.allow_range' ) ): ?>
					<form-group popup-key="datePicker" ref="datePicker" @save="onSaveCalendar" @blur="saveCalendar" @clear="resetPicker" wrapper-class="ts-booking-range-wrapper">
						<template #trigger>
							<label><?= _x( 'Pick day', 'product form', 'voxel' ) ?></label>
							<div class="ts-double-input flexify">
								<div class="ts-filter ts-popup-target" :class="{'ts-filled':booking.checkIn}" @mousedown="$root.activePopup = 'datePicker'">
									<?= \Voxel\get_icon_markup( $this->get_settings_for_display('ts_calendar_icon') ) ?: \Voxel\svg( 'calendar.svg' ) ?>
									<div class="ts-filter-text">{{ checkInLabel }}</div>
									<div class="ts-down-icon"></div>
								</div>

								<div class="ts-filter ts-popup-target" :class="{'ts-filled':booking.checkOut}" @mousedown="$root.activePopup = 'datePicker'">
									<?= \Voxel\get_icon_markup( $this->get_settings_for_display('ts_calendar_icon') ) ?: \Voxel\svg( 'calendar.svg' ) ?>
									<div class="ts-filter-text">{{ checkOutLabel }}</div>
									<div class="ts-down-icon"></div>
								</div>
							</div>
						</template>
						<template #popup>
							<date-range-picker ref="picker" :parent="this"></date-range-picker>
						</template>
					</form-group>
				<?php else: ?>
					<form-group popup-key="datePicker" ref="datePicker" @save="onSaveCalendar" @blur="saveCalendar" @clear="resetPicker" wrapper-class="ts-booking-date-wrapper">
						<template #trigger>
							<label><?= _x( 'Pick day', 'product form', 'voxel' ) ?></label>
							<div class="ts-filter ts-popup-target" :class="{'ts-filled':booking.checkIn}" @mousedown="$root.activePopup = 'datePicker'">
								<?= \Voxel\get_icon_markup( $this->get_settings_for_display('ts_calendar_icon') ) ?: \Voxel\svg( 'calendar.svg' ) ?>
								<div class="ts-filter-text">{{ pickDateLabel }}</div>
							</div>
						</template>
						<template #popup>
							<date-picker ref="picker" :parent="this"></date-picker>
						</template>
					</form-group>
				<?php endif ?>

				<?php if ( $product_type->config( 'calendar.format' ) === 'slots' ): ?>
					<div v-if="timeslots" class="ts-form-group">
						<label><?= _x( 'Pick slot', 'product form', 'voxel' ) ?></label>
						<ul class="ts-pick-slot simplify-ul">
							<li v-for="slot in timeslots" :class="{'slot-picked': slot === booking.timeslot, 'vx-disabled': config.calendar.excluded_slots[getSlotKey( slot )]}">
								<a href="#" @click.prevent="booking.timeslot = slot">
									<!-- <?= \Voxel\get_icon_markup( $this->get_settings_for_display('ts_select_icon') ) ?: \Voxel\svg( 'checkmark-circle.svg' ) ?> -->
									<span>{{ getSlotLabel(slot) }}</span>
								</a>
							</li>
						</ul>
					</div>
				<?php endif ?>
			<?php elseif ( $product_type->config( 'calendar.type' ) === 'recurring-date' ): ?>
				<div class="ts-form-group">
					<label><?= _x( 'Upcoming dates', 'product form', 'voxel' ) ?></label>
					<ul v-if="config.recurring_date.bookable.length" class="ts-pick-slot simplify-ul">
						<li
							v-for="date in config.recurring_date.bookable"
							:class="{'slot-picked': booking.checkIn === date.start && booking.checkOut === date.end}"
							@click.prevent="booking.checkIn = date.start; booking.checkOut = date.end"
						>
							<a href="#">
								<?= \Voxel\get_icon_markup( $this->get_settings_for_display('ts_select_icon') ) ?: \Voxel\svg( 'circle-plus.svg' ) ?>
								<span>{{ date.formatted }}</span>
							</a>
						</li>
					</ul>
					<small v-else><?= _x( 'No bookable dates', 'product form', 'voxel' ) ?></small>
				</div>
			<?php endif ?>
		<?php endif ?>

		<template v-for="addition in additions">
			<div v-if="addition.type === 'numeric'" class="ts-form-group">
				<label>{{ addition.label }}</label>
				<div class="ts-stepper-input flexify">
					<button class="ts-stepper-left ts-icon-btn" @click.prevent="decrement(addition)">
						<?= \Voxel\get_icon_markup( $this->get_settings_for_display('ts_minus_icon') ) ?: \Voxel\svg( 'minus.svg' ) ?>
					</button>
					<input
						v-model="addition.value"
						type="number"
						class="ts-input-box"
						@change="validateValueInBounds(addition)"
					>
					<button class="ts-stepper-right ts-icon-btn" @click.prevent="increment(addition)">
						<?= \Voxel\get_icon_markup( $this->get_settings_for_display('ts_plus_icon') ) ?: \Voxel\svg( 'plus.svg' ) ?>
					</button>
				</div>
			</div>
			<div v-if="addition.type === 'checkbox'" class="ts-form-group">
				<label>{{ addition.label }}</label>
				<div class="switch-slider">
					<div class="onoffswitch">
						<input v-model="addition.value" type="checkbox" class="onoffswitch-checkbox">
						<label class="onoffswitch-label" @click.prevent="addition.value = !addition.value"></label>
					</div>
				</div>
			</div>
			<template v-if="addition.type === 'select'">
				<form-group
					v-if="Object.keys(addition.choices).length"
					:popup-key="addition.key"
					:ref="'select-'+addition.key"
					@save="$refs['select-'+addition.key].blur()"
					@clear="addition.value = null"
					:show-clear="!addition.required"
				>
					<template #trigger>
						<label>{{ addition.label }}</label>
						<div class="ts-filter ts-popup-target" :class="{'ts-filled': addition.value !== null}" @mousedown="$root.activePopup = addition.key">
							<span><?= \Voxel\get_icon_markup( $this->get_settings_for_display('ts_addition_ico') ) ?: \Voxel\svg( 'menu.svg' ) ?></span>
							<div class="ts-filter-text">
								<span>{{ addition.choices[addition.value] ? addition.choices[addition.value].label : addition.placeholder }}</span>
							</div>
							<div class="ts-down-icon"></div>
						</div>
					</template>
					<template #popup>
						<div class="ts-term-dropdown ts-md-group ts-multilevel-dropdown">
							<ul class="simplify-ul ts-term-dropdown-list min-scroll">
								<template v-for="choice, choice_value in addition.choices">
									<li>
										<a href="#" class="flexify" @click.prevent="addition.value = choice_value; $refs['select-'+addition.key].blur();">

											<div class="ts-radio-container">
												<label class="container-radio">
													<input
														type="radio"
														:value="choice_value"
														:checked="addition.value === choice_value"
														disabled
														hidden
													>
													<span class="checkmark"></span>
												</label>
											</div>
											<p>{{ choice.label }}</p>
											<div class="ts-term-icon">
												<span v-if="choice.icon" v-html="choice.icon"></span>
											</div>
										</a>
									</li>
								</template>
							</ul>
						</div>
					</template>
				</form-group>
			</template>
		</template>

		<template v-for="addition in custom_additions">
			<form-group
				:popup-key="'custom#'+addition.key"
				:ref="'select-custom#'+addition.key"
				@save="$refs['select-custom#'+addition.key].blur()"
				@clear="clearCustomAddition(addition)"
				:show-clear="!addition.required"
			>
				<template #trigger>
					<label>{{ addition.label }}</label>
					<div class="ts-filter ts-popup-target" :class="{'ts-filled': getSelectedItems(addition).length}" @mousedown="$root.activePopup = 'custom#'+addition.key">
						<?= \Voxel\get_icon_markup( $this->get_settings_for_display('ts_addition_ico') ) ?: \Voxel\svg( 'menu.svg' ) ?>
						<div class="ts-filter-text">
							<span v-if="getSelectedItems(addition).length">{{ getSelectedItemsLabel(addition) }}</span>
							<span v-else>{{ addition.label }}</span>
						</div>
						<div class="ts-down-icon"></div>
					</div>
				</template>
				<template #popup>
					<template v-if="addition.mode === 'single'">
						<template v-for="item in addition.items">
							<div class="ts-form-group">
								<label>{{ item.label }}</label>

								<div class="switch-slider">
									<div class="onoffswitch">
										<input :checked="!!item.value" type="checkbox" class="onoffswitch-checkbox">
										<label class="onoffswitch-label" @click.prevent="pickSingleItem(addition, item)"></label>
									</div>
								</div>
							</div>
							<div v-if="item.has_quantity && item.value" class="ts-form-group">
								<div class="ts-stepper-input flexify">
									<button class="ts-stepper-left ts-icon-btn" @click.prevent="decrement(item)">
										<?= \Voxel\get_icon_markup( $this->get_settings_for_display('ts_minus_icon') ) ?: \Voxel\svg( 'minus.svg' ) ?>
									</button>
									<input
										v-model="item.value"
										type="number"
										class="ts-input-box"
										@change="validateValueInBounds(item)"
									>
									<button class="ts-stepper-right ts-icon-btn" @click.prevent="increment(item)">
										<?= \Voxel\get_icon_markup( $this->get_settings_for_display('ts_plus_icon') ) ?: \Voxel\svg( 'plus.svg' ) ?>
									</button>
								</div>
							</div>
						</template>
					</template>
					<template v-else>
						<template v-for="item in addition.items">
							<div v-if="item.has_quantity" class="ts-form-group">
								<label>{{ item.label }}</label>
								<div class="ts-stepper-input flexify">
									<button class="ts-stepper-left ts-icon-btn" @click.prevent="decrement(item)">
										<?= \Voxel\get_icon_markup( $this->get_settings_for_display('ts_minus_icon') ) ?: \Voxel\svg( 'minus.svg' ) ?>
									</button>
									<input
										v-model="item.value"
										type="number"
										class="ts-input-box"
										@change="validateValueInBounds(item)"
									>
									<button class="ts-stepper-right ts-icon-btn" @click.prevent="increment(item)">
										<?= \Voxel\get_icon_markup( $this->get_settings_for_display('ts_plus_icon') ) ?: \Voxel\svg( 'plus.svg' ) ?>
									</button>
								</div>
							</div>
							<div v-else class="ts-form-group">
								<label>{{ item.label }}</label>
								<div class="switch-slider">
									<div class="onoffswitch">
										<input v-model="item.value" type="checkbox" class="onoffswitch-checkbox">
										<label class="onoffswitch-label" @click.prevent="item.value = !item.value"></label>
									</div>
								</div>
							</div>
						</template>
					</template>
				</template>
			</form-group>
		</template>

		<div class="ts-form-group">
			<a
				href="#"
				@click.prevent="prepareCheckout"
				class="ts-btn ts-btn-2 ts-btn-large ts-booking-submit"
				:class="{'vx-pending': loading}"
			>
				<?= $this->get_settings_for_display('prform_continue') ?>
				<?= \Voxel\get_icon_markup( $this->get_settings_for_display('ts_continue_icon') ) ?: \Voxel\svg( 'arrow-right-circle.svg' ) ?>
			</a>
		</div>

		<div class="ts-form-group tcc-container">
			<ul class="ts-cost-calculator simplify-ul flexify">
				<li v-if="pricing.additions.length && pricing.base_price">
					<div class="ts-item-name">
						<p>
							<?= _x( 'Base price', 'product form', 'voxel' ) ?>
							<span v-if="repeatDayCount > 1">({{ repeatDayCount }} {{ config.calendar.range_mode === 'nights'
								? <?= wp_json_encode( _x( 'nights', 'product form', 'voxel' ) ) ?>
								: <?= wp_json_encode( _x( 'days', 'product form', 'voxel' ) ) ?>
							}})</span>
						</p>
					</div>
					<div class="ts-item-price">
						<p>{{ priceFormat( pricing.base_price ) }}</p>
					</div>
				</li>
				<template v-for="addition in pricing.additions">
					<li v-if="addition.price">
						<template v-if="addition.repeat && repeatDayCount > 1">
							<div class="ts-item-name">
								<p>{{ addition.label }} ({{ repeatDayCount }} {{ config.calendar.range_mode === 'nights'
									? <?= wp_json_encode( _x( 'nights', 'product form', 'voxel' ) ) ?>
									: <?= wp_json_encode( _x( 'days', 'product form', 'voxel' ) ) ?>
								}})</p>
							</div>
							<div class="ts-item-price">
								<p>{{ priceFormat( addition.price ) }}</p>
							</div>
						</template>
						<template v-else>
							<div class="ts-item-name">
								<p>{{ addition.label }}</p>
							</div>
							<div class="ts-item-price">
								<p>{{ priceFormat( addition.price ) }}</p>
							</div>
						</template>
					</li>
				</template>
				<!-- <li v-if="pricing.total > pricing.platform_fee" class="platform-fee">
					<div class="ts-item-name">
						<p>Platform fee</p>
					</div>
					<div class="ts-item-price">
						<p>{{ priceFormat( pricing.platform_fee ) }}</p>
					</div>
				</li> -->
				<li class="ts-total">
					<div class="item-name">
						<p><?= _x( 'Total', 'product form', 'voxel' ) ?></p>
					</div>
					<div class="item-price">
						<p>{{ priceFormat( pricing.total ) }}</p>
					</div>
				</li>
			</ul>
		</div>
	</div>

	<div v-show="step === 'checkout'" class="ts-booking-fields">
		<div class="booking-head">
			<?= \Voxel\get_icon_markup( $this->get_settings_for_display('steptwo_ico') ) ?: \Voxel\svg( 'menu.svg' ) ?>
			<p><?= $this->get_settings_for_display('prform_steptwo_text') ?></p>

		</div>

		<?php foreach ( $product_type->get_fields() as $field ):
			$field_object = sprintf( '$root.config.fields[%s]', esc_attr( wp_json_encode( $field->get_key() ) ) );
			?>
			<field-<?= $field->get_type() ?>
				:field="<?= $field_object ?>"
				ref="field:<?= esc_attr( $field->get_key() ) ?>"
			></field-<?= $field->get_type() ?>>
		<?php endforeach ?>

		<div class="ts-form-group">
			<a href="#" @click.prevent="submit" class="ts-btn ts-btn-2 ts-btn-large ts-booking-submit" :class="{'vx-pending': loading}">
				<?= \Voxel\get_icon_markup( $this->get_settings_for_display('ts_checkout_ico') ) ?: \Voxel\svg( 'shopping-bag.svg' ) ?>
				<?= $this->get_settings_for_display('prform_checkout') ?>
			</a>
		</div>
		<div v-if="!lockToCheckout" class="ts-form-group">
	   		<a href="#" class="ts-btn ts-btn-4 ts-btn-large"  @click.prevent="step = 'main'" >
	   			<?= \Voxel\get_icon_markup( $this->get_settings_for_display('ts_arrow_left.svg') ) ?: \Voxel\svg( 'chevron-left.svg' ) ?>
	   			<?= __( 'Go back', 'voxel' ) ?>
	   		</a>
		</div>
	</div>

	<teleport to="body">
		<transition name="form-popup">
			<form-popup
				v-if="externalItemRef && externalItem"
				:target="externalItemRef"
				@blur="externalItemRef = null; externalItem = null;"
				@save="externalItem = null"
				@clear="externalItem.value = externalItem.has_quantity ? 0 : false;"
			>
				<div class="ts-form-group">
					<label>{{ externalItem.label }}</label>
					<div class="ts-stepper-input flexify">
						<button class="ts-stepper-left ts-icon-btn" @click.prevent="decrement(externalItem)">
							<?= \Voxel\get_icon_markup( $this->get_settings_for_display('ts_minus_icon') ) ?: \Voxel\svg( 'minus.svg' ) ?>
						</button>
						<input
							v-model="externalItem.value"
							type="number"
							class="ts-input-box"
							@change="validateValueInBounds(externalItem)"
						>
						<button class="ts-stepper-right ts-icon-btn" @click.prevent="increment(externalItem)">
							<?= \Voxel\get_icon_markup( $this->get_settings_for_display('ts_plus_icon') ) ?: \Voxel\svg( 'plus.svg' ) ?>
						</button>
					</div>
				</div>
			</form-popup>
		</transition>
	</teleport>
</div>

<?php foreach ( $deferred_templates as $template_path ): ?>
	<?php require_once $template_path ?>
<?php endforeach ?>
