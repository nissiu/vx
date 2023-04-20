<script type="text/html" id="orders-single">
	<div class="ts-social-feed ts-single-order" :class="{'vx-pending': pending}">
		<template v-if="!order">
			<div v-if="loading" class="ts-no-posts">
				<span class="ts-loader"></span>
			</div>
			<div v-else class="ts-no-posts">
				<?= \Voxel\get_icon_markup( $this->get_settings_for_display('ts_all_requests') ) ?: \Voxel\svg( 'switch.svg' ) ?>
				<p><?= _x( 'Order not found', 'single order', 'voxel' ) ?></p>
			</div>
		</template>
		<transition name="fade">
			<div v-if="order">
				<div class="ts-status-list">
					<div class="ts-order-head">
						<display-actions :order="this" :order-details="order"></display-actions>
					</div>
					<div class="ts-single-status">
						<a :href="order.customer.link" v-html="order.customer.avatar"></a>
						<div class="ts-status">
							<div class="ts-status-head flexify ts-single-order-head">
								<a :href="order.customer.link" class="ts_status-author">{{ order.customer.name }}</a>
								<div>
									<span><?= _x( 'Sent a request on', 'single order', 'voxel' ) ?></span>
									<a :href="order.post.link">{{ order.post.title }}</a>
									<span v-if="order.product_type.label">&middot; {{ order.product_type.label }}</span>
									<span class="ts-status-time">&middot; {{ order.time }}</span>
								</div>
							</div>
						</div>
					</div>
					<div class="ts-single-status">
						<div class="ts-status">
							<div class="ts-inner-status" :class="order.status.slug">
								<?= \Voxel\get_icon_markup( $this->get_settings_for_display('ts_status') ) ?: \Voxel\svg( 'bookmark.svg' ) ?>
								<p>{{ order.status.label }}</p>
							</div>
							<div class="order-cards">
								<booking-details v-if="booking" :order="this" :booking="booking"></booking-details>
								<div v-if="!order.is_free" class="ts-order-card">
									<ul class="flexify simplify-ul">
										<li class="ts-card-icon">
											<?= \Voxel\get_icon_markup( $this->get_settings_for_display('ts_price_ico') ) ?: \Voxel\svg( 'coin.svg' ) ?>
										</li>
										<li>
											<small><?= _x( 'Price', 'single order', 'voxel' ) ?></small>
											<p>{{ order.price.amount }} <span v-if="order.price.period">/ {{ order.price.period }}</span></p>
										</li>
									</ul>
								</div>
								<div class="ts-order-card">
									<ul class="flexify simplify-ul">
										<li class="ts-card-icon">
											<?= \Voxel\get_icon_markup( $this->get_settings_for_display('ts_order_ico') ) ?: \Voxel\svg( 'bag.svg' ) ?>
										</li>
										<li>
											<small><?= _x( 'Order number', 'single order', 'voxel' ) ?></small>
											<p>#{{ order.id }}</p>
										</li>
									</ul>
								</div>

								<div v-for="addition in additions" class="ts-order-card">
									<ul class="flexify simplify-ul">
										<li class="ts-card-icon" v-html="addition.icon"></li>
										<li>
											<small>{{ addition.label }}</small>
											<p>{{ addition.content }}</p>
										</li>
									</ul>
								</div>

								<div v-for="addition in order.custom_additions" class="ts-order-card">
									<ul class="flexify simplify-ul">
										<li class="ts-card-icon" v-html="addition.icon"></li>
										<li>
											<small>{{ addition.label }}</small>
											<p>{{ addition.content }}</p>
										</li>
									</ul>
								</div>
							</div>

							<div v-if="fields.length" class="order-info-container">
								<div class="order-info-head">
									<?= \Voxel\get_icon_markup( $this->get_settings_for_display('info_icon') ) ?: \Voxel\svg( 'user-information.svg' ) ?>
									<p><?= _x( 'Request details', 'single order', 'voxel' ) ?></p>
									<a href="#" @click.prevent="state.showFields = !state.showFields" class="ts-icon-btn ts-smaller">
										<?= \Voxel\get_icon_markup( $this->get_settings_for_display('ts_down_ico') ) ?: \Voxel\svg( 'chevron-down.svg' ) ?>
									</a>
								</div>
								<ul v-if="state.showFields" class="simplify-ul">
									<li v-for="field in fields">
										<template v-if="field.type === 'file'">
											<small>{{ field.label }}</small>
											<div class="ts-status-attachments">
												<ul class="simplify-ul">
													<li v-for="file in field.content">
														<a :href="file.link" target="_blank">
															<?= \Voxel\get_icon_markup( $this->get_settings_for_display('ts_upload_ico') ) ?: \Voxel\svg( 'upload.svg' ) ?>
															<span>{{ file.name }}</span>
														</a>
													</li>
												</ul>
											</div>
										</template>
										<template v-else>
											<small>{{ field.label }}</small>
											<p v-html="field.content"></p>
										</template>
									</li>
								</ul>
							</div>

							<div v-if="order.role.is_customer && order.subscription.exists" class="order-info-container">
								<div class="order-info-head">
									<?= \Voxel\get_icon_markup( $this->get_settings_for_display('ts_order_ico') ) ?: \Voxel\svg( 'checkmark-circle.svg' ) ?>
									<subscription-status :order="this" :subscription="order.subscription"></subscription-status>
								</div>
							</div>

							<div v-if="order.tags.can_edit && order.status.slug === 'completed' && order.tags.list.length" class="order-info-container">
								<div class="order-info-head">
									<?= \Voxel\get_icon_markup( $this->get_settings_for_display('ts_order_ico') ) ?: \Voxel\svg( 'checkmark-circle.svg' ) ?>
									<p><?= _x( 'Tag order', 'single order', 'voxel' ) ?></p>
									<a href="#" @click.prevent="state.showTags = !state.showTags" class="ts-icon-btn ts-smaller">
										<?= \Voxel\get_icon_markup( $this->get_settings_for_display('ts_down_ico') ) ?: \Voxel\svg( 'chevron-down.svg' ) ?>
									</a>
								</div>
								<div v-if="state.showTags" class="order-product-tags">
									<ul class="simplify-ul flexify tci-labels">
										<li
											v-for="tag in order.tags.list"
											class="tci-status"
											:class="{checked: order.tags.active === tag.key}"
											:style="order.tags.active === tag.key ? {backgroundColor: tag.secondary_color, color: tag.primary_color} : {}"
											@click="applyTag(tag.key)"
											>
											{{ tag.label }}
											<!-- <a href="#" class="get-qr-code" v-if="tag.has_qr_code">
												<i class="las la-qrcode"></i>
											</a> -->
										</li>
									</ul>
								</div>
							</div>

							<div v-if="pricing.additions.length && !order.is_free" class="order-info-container">
								<div class="order-info-head">
									<?= \Voxel\get_icon_markup( $this->get_settings_for_display('ts_price_ico') ) ?: \Voxel\svg( 'coin.svg' ) ?>
									<p><?= _x( 'Price breakdown', 'single order', 'voxel' ) ?></p>
									<a href="#" @click.prevent="state.showPricing = !state.showPricing" class="ts-icon-btn ts-smaller">
										<?= \Voxel\get_icon_markup( $this->get_settings_for_display('ts_down_ico') ) ?: \Voxel\svg( 'chevron-down.svg' ) ?>
									</a>
								</div>
								<ul v-if="state.showPricing" class="simplify-ul">
									<li v-if="pricing.additions.length">
										<small><?= _x( 'Base price', 'single order', 'voxel' ) ?></small>
										<p>{{ pricing.base_price }}</p>
									</li>
									<li v-for="addition in pricing.additions">
										<small>{{ addition.label }}</small>
										<p>{{ addition.price }}</p>
									</li>
									<li class="ts-total">
										<small><?= _x( 'Total', 'single order', 'voxel' ) ?></small>
										<p>{{ pricing.total }} <span v-if="pricing.period">/ {{ pricing.period }}</span></p>
									</li>
								</ul>
							</div>

							<div v-if="order.vendor_rules" class="order-info-container">
								<div class="order-info-head">
									<?= \Voxel\get_icon_markup( $this->get_settings_for_display('ts_user_ico') ) ?: \Voxel\svg( 'user.svg' ) ?>
									<p><?= _x( 'Vendor notes', 'single order', 'voxel' ) ?></p>
									<a href="#" @click.prevent="state.showRules = !state.showRules" class="ts-icon-btn ts-smaller">
										<?= \Voxel\get_icon_markup( $this->get_settings_for_display('ts_down_ico') ) ?: \Voxel\svg( 'chevron-down.svg' ) ?>
									</a>
								</div>
								<ul v-if="state.showRules" class="simplify-ul">
									<li>
										<p>{{ order.vendor_rules }}</p>
									</li>
								</ul>
							</div>

							<div v-if="order.downloads.length" class="order-info-container">
								<div class="order-info-head">
									<?= \Voxel\get_icon_markup( $this->get_settings_for_display('ts_upload_ico') ) ?: \Voxel\svg( 'upload.svg' ) ?>
									<p><?= _x( 'Downloads', 'single order', 'voxel' ) ?></p>
									<a href="#" @click.prevent="state.showDeliverables = !state.showDeliverables" class="ts-icon-btn ts-smaller">
										<?= \Voxel\get_icon_markup( $this->get_settings_for_display('ts_down_ico') ) ?: \Voxel\svg( 'chevron-down.svg' ) ?>
									</a>
								</div>
								<ul v-if="state.showDeliverables" class="simplify-ul">
									<li v-for="download in order.downloads">
										<small>{{ download.time }}</small>
										<div class="ts-status-attachments">
											<ul class="simplify-ul">
												<li v-for="file in download.files">
													<a
														:href="file.url"
														target="_blank"
														:class="{'vx-disabled': (file.limit && file.count >= file.limit) || !file.downloadable}"
														@click="order.role.is_customer && !order.role.is_admin && file.count++"
													>
														<?= \Voxel\get_icon_markup( $this->get_settings_for_display('ts_upload_ico') ) ?: \Voxel\svg( 'upload.svg' ) ?>
														<span>
															{{ file.name }}
															<span v-if="file.limit" class="ts-download-limit">
																{{ file.count }}/{{ file.limit }} <?= _x( 'downloads', 'single order', 'voxel' ) ?>
															</span>
														</span>
													</a>
												</li>
											</ul>
										</div>
									</li>
								</ul>
							</div>
							<div v-if="order.deliverables.enabled && order.role.is_author" class="">
								<deliver-files :order="this"></deliver-files>
							</div>
							<?php do_action( 'voxel/view-order/after-infoboxes' ) ?>
						</div>
					</div>
					<template v-for="note in notes">
						<div class="ts-divider">
							<div class=""></div>
						</div>
						<div class="ts-single-status ts-order-comment">
							<display-note :note="note" :order="this"></display-note>
						</div>
					</template>
					<div class="ts-divider">
						<div class=""></div>
					</div>
					<div class="ts-form ts-add-status ts-status">
						<create-note :order="this"></create-note>
					</div>
				</div>
			</div>
		</transition>
	</div>
</script>
