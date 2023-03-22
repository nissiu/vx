<?php
/**
 * Orders widget template.
 *
 * @since 1.0
 */
if ( ! defined('ABSPATH') ) {
	exit;
}

require_once locate_template( 'templates/widgets/orders/single-order.php' );
require_once locate_template( 'templates/widgets/create-post/_media-popup.php' );
require_once locate_template( 'templates/widgets/orders/booking-details.php' );
require_once locate_template( 'templates/widgets/orders/create-note.php' );
require_once locate_template( 'templates/widgets/orders/deliver-files.php' );
require_once locate_template( 'templates/widgets/orders/display-actions.php' );
require_once locate_template( 'templates/widgets/orders/display-note.php' );
require_once locate_template( 'templates/widgets/orders/subscription-status.php' );
?>

<div v-cloak class="ts-orders" data-config="<?= esc_attr( wp_json_encode( $config ) ) ?>">
	<template v-if="activeOrder">
		<single-order :order-id="activeOrder"></single-order>
	</template>
	<template v-else>
		<div class="ts-form ts-order-filters min-scroll min-scroll-h">
			<form-group popup-key="type" ref="type" @clear="setType('all'); $refs.type.blur();" @save="$refs.type.blur();">
				<template #trigger>
					<div class="ts-filter ts-popup-target ts-filled" :class="{'ts-filled': type !== 'all'}" @mousedown="$root.activePopup = 'type'">
						<div class="ts-filter-text">
							<template v-if="type === 'incoming'">
								<?= _x( 'Incoming', 'orders', 'voxel' ) ?>
							</template>
							<template v-else-if="type === 'outgoing'">
								<?= _x( 'Outgoing', 'orders', 'voxel' ) ?>
							</template>
							<template v-else>
								<?= _x( 'All requests', 'orders', 'voxel' ) ?>
							</template>
						</div>
						<div class="ts-down-icon"></div>
					</div>
				</template>
				<template #popup>
					<div class="ts-term-dropdown ts-md-group">
						<ul class="simplify-ul ts-term-dropdown-list min-scroll">
							<li>
								<a href="#" class="flexify" @click.prevent="setType('all')">
									<div class="ts-checkbox-container">
										<label class="container-radio">
											<input type="radio" :checked="type === 'all'" disabled hidden>
											<span class="checkmark"></span>
										</label>
									</div>
									<p><?= _x( 'All requests', 'orders', 'voxel' ) ?></p>
								</a>
							</li>
							<li>
								<a href="#" class="flexify" @click.prevent="setType('incoming')">
									<div class="ts-checkbox-container">
										<label class="container-radio">
											<input type="radio" :checked="type === 'incoming'" disabled hidden>
											<span class="checkmark"></span>
										</label>
									</div>
									<p><?= _x( 'Incoming', 'orders', 'voxel' ) ?></p>
								</a>
							</li>
							<li>
								<a href="#" class="flexify" @click.prevent="setType('outgoing')">
									<div class="ts-checkbox-container">
										<label class="container-radio">
											<input type="radio" :checked="type === 'outgoing'" disabled hidden>
											<span class="checkmark"></span>
										</label>
									</div>
									<p><?= _x( 'Outgoing', 'orders', 'voxel' ) ?></p>
								</a>
							</li>
						</ul>
					</div>
				</template>
			</form-group>

			<form-group popup-key="status" ref="status" @clear="setStatus('all'); $refs.status.blur();" @save="$refs.status.blur()">
				<template #trigger>
					<div class="ts-filter ts-popup-target" :class="{'ts-filled': status !== 'all'}" @mousedown="$root.activePopup = 'status'">
						<div v-if="status === 'all'" class="ts-filter-text"><?= _x( 'Any status', 'orders', 'voxel' ) ?></div>
						<div v-else class="ts-filter-text">{{ config.statuses[ status ] }}</div>
						<div class="ts-down-icon"></div>
					</div>
				</template>
				<template #popup>
					<div class="ts-term-dropdown ts-md-group">
						<ul class="simplify-ul ts-term-dropdown-list min-scroll">
							<template v-for="status_details, status_key in config.statuses">
								<li v-if="status_details.type === 'subheading'" class="ts-parent-item">
									<a href="#" @click.prevent class="flexify"><p>{{ status_details.label }}</p></a>
								</li>
								<li v-else>
									<a href="#" class="flexify" @click.prevent="setStatus( status_key )">
										<div class="ts-checkbox-container">
											<label class="container-radio">
												<input type="radio" :value="status_key" :checked="status === status_key" disabled hidden>
												<span class="checkmark"></span>
											</label>
										</div>
										<p>{{ status_details }}</p>
									</a>
								</li>
							</template>
						</ul>
					</div>
				</template>
			</form-group>

			<form-group popup-key="search" ref="search" @clear="search = ''; searchOrders(); $refs.search.blur();" @save="searchOrders(); $refs.search.blur();">
				<template #trigger>
					<div class="ts-filter ts-popup-target" :class="{'ts-filled': search.trim().length}" @mousedown="$root.activePopup = 'search'">
						<div class="ts-filter-text">{{ search.trim().length ? search.trim() : <?= wp_json_encode( _x( 'Search', 'orders', 'voxel' ) ) ?> }}</div>
						<div class="ts-down-icon"></div>
					</div>
				</template>
				<template #popup>
					<div>
						<form @submit.prevent="searchOrders(); $refs.search.blur();">
							<div class="ts-input-icon flexify">
								<?= \Voxel\get_icon_markup( $this->get_settings_for_display('ts_order_keyword') ) ?: \Voxel\svg( 'search.svg' ) ?>
								<input class="border-none autofocus" v-model="search" type="text" placeholder="<?= esc_attr( _x( 'Search orders', 'orders', 'voxel' ) ) ?>">
							</div>
						</form>
					</div>
				</template>
			</form-group>
		</div>

		<div v-if="orders.length">
			<div class="orders-flex" :class="{'vx-disabled': loading}">
				<div class="ts-order-item" v-for="order in orders" @click.prevent="viewOrder( order.id )">
					<div class="data-con">
						<span v-html="order.customer.avatar"></span>
					</div>
					<div class="data-con">
						<template v-if="order.is_free">
							<span>
								<?= \Voxel\replace_vars( _x( '@customer sent a request', 'orders', 'voxel' ), [
									'@customer' => '<p>{{ order.customer.name }}</p>',
								] ) ?>
							</span>
							<span>{{ order.time }}</span>
						</template>
						<template v-else>
							<span>
								<?= \Voxel\replace_vars( _x( '@customer sent a @price request', 'orders', 'voxel' ), [
									'@customer' => '<p>{{ order.customer.name }}</p>',
									'@price' => '<p>{{ order.price }}</p>',
								] ) ?>
							</span>
							<span>{{ order.time }}</span>
						</template>
						<span v-if="order.product_type.label">&middot; {{ order.product_type.label }}</span>
					</div>
					<div class="ts-order-status"  :class="order.status.slug">
						<?= \Voxel\get_icon_markup( $this->get_settings_for_display('ts_status') ) ?: \Voxel\svg( 'bookmark.svg' ) ?>
						<p>{{ order.status.label }}</p>
					</div>
				</div>
			</div>

			<div class="orders-pagination flexify" v-if="page > 1 || hasMore">
				<a href="#" class="ts-btn ts-btn-1" :class="{'vx-disabled': page <= 1}" @click.prevent="page -= 1; getOrders();">
					<?= \Voxel\get_icon_markup( $this->get_settings_for_display('ts_arrow_left') ) ?: \Voxel\svg( 'chevron-left.svg' ) ?>
					<?= _x( 'Previous', 'orders', 'voxel' ) ?>
				</a>
				<a href="#" class="ts-btn ts-btn-1 ts-btn-large" :class="{'vx-disabled': !hasMore}" @click.prevent="page += 1; getOrders();">
					<?= _x( 'Next', 'orders', 'voxel' ) ?>
					<?= \Voxel\get_icon_markup( $this->get_settings_for_display('ts_arrow_right') ) ?: \Voxel\svg( 'chevron-right.svg' ) ?>
				</a>
			</div>
		</div>
		<div v-else>
			<div v-if="loading" class="ts-no-posts">
				<span class="ts-loader"></span>
			</div>
			<div v-else class="ts-no-posts">
				<?= \Voxel\get_icon_markup( $this->get_settings_for_display('ts_all_requests') ) ?: \Voxel\svg( 'switch.svg' ) ?>
				<p><?= _x( 'No requests found', 'orders', 'voxel' ) ?></p>
			</div>
		</div>
	</template>
</div>
