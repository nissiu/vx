<?php
/**
 * Edit product type fields in WP Admin.
 *
 * @since 1.0
 */
if ( ! defined('ABSPATH') ) {
	exit;
}

require_once locate_template('templates/backend/product-types/components/additions.php');
require_once locate_template('templates/backend/product-types/components/information-fields.php');
require_once locate_template('templates/backend/product-types/components/order-tags.php');
require_once locate_template('templates/backend/post-types/components/select-field-choices.php');
?>

<div class="wrap">
	<div id="voxel-edit-product-type" v-cloak>
		<form method="POST" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ) ?>" @submit="prepareSubmission">
			<div class="edit-cpt-header">
				<div class="ts-container cpt-header-container">
					<div class="ts-row wrap-row v-center">
						<div class="ts-col-1-2 v-center ">
							<h1><?= $product_type->get_label() ?>
								<p>You are editing <?= $product_type->get_label() ?> product type</p>
							</h1>
						</div>
						<div class="cpt-header-buttons ts-col-1-2 v-center">
							<input type="hidden" name="product_type_config" :value="submit_config">
							<input type="hidden" name="action" value="voxel_save_product_type_settings">
							<?php wp_nonce_field( 'voxel_save_product_type_settings' ) ?>
							<button type="submit" name="remove_product_type" value="yes" class="ts-button ts-transparent"
								onclick="return confirm('Are you sure?')">
								Delete
							</button>

							<button type="submit" class="ts-button ts-save-settings btn-shadow">
								<i class="las la-save icon-sm"></i>
								Save changes
							</button>
						</div>
					</div>
					<span class="ts-separator"></span>
				</div>
			</div>

			<div class="ts-theme-options ts-container">

				<div class="ts-tab-content ts-container">
					<div class="ts-row ts-theme-options-nav">
						<div class="ts-nav ts-col-1-1">
							<div class="ts-nav-item" :class="{'current-item': tab === 'general'}">
								<a href="#" @click.prevent="setTab('general', 'base')">
									<span class="item-icon all-center">
										<i class="las la-home"></i>
									</span>
									<span class="item-name">
										General
									</span>
								</a>
							</div>
							<div class="ts-nav-item" :class="{'current-item': tab === 'pricing'}">
								<a href="#" @click.prevent="setTab('pricing', 'base')">
									<span class="item-icon all-center">
										<i class="las la-dollar-sign"></i>
									</span>
									<span class="item-name">
										Pricing
									</span>
								</a>
							</div>
							<div class="ts-nav-item" :class="{'current-item': tab === 'additions', 'vx-disabled': config.settings.payments.pricing === 'price_id'}">
								<a href="#" @click.prevent="setTab('additions')">
									<span class="item-icon all-center">
										<i class="las la-plus"></i>
									</span>
									<span class="item-name">
										Additions
									</span>
								</a>
							</div>
							<div class="ts-nav-item" :class="{'current-item': tab === 'fields'}">
								<a href="#" @click.prevent="setTab('fields')">
									<span class="item-icon all-center">
										<i class="las la-user-circle"></i>
									</span>
									<span class="item-name">
										Information fields
									</span>
								</a>
							</div>
							<div class="ts-nav-item" :class="{'current-item': tab === 'checkout'}">
								<a href="#" @click.prevent="setTab('checkout', 'tax')">
									<span class="item-icon all-center">
										<i class="las la-shopping-bag"></i>
									</span>
									<span class="item-name">
										Checkout
									</span>
								</a>
							</div>
							</div>
					</div>

					<div v-if="tab === 'general'" class="inner-tab ts-row wrap-row all-center">
						<div class="ts-col-1-2">
							<div class="ts-row wrap-row">
								<div class="ts-tab-heading ts-col-1-1">
									<h1>General</h1>
									<p>General product type settings</p>
								</div>

								<div class="ts-col-1-1">
									<ul class="inner-tabs">
										<li :class="{'current-item': $root.subtab === 'base'}">
											<a href="#" @click.prevent="$root.setTab('general', 'base')">General</a>
										</li>
										<li :class="{'current-item': $root.subtab === 'tags'}">
											<a href="#" @click.prevent="$root.setTab('general', 'tags')">Order tags</a>
										</li>
										<li :class="{'current-item': $root.subtab === 'deliverables'}">
											<a href="#" @click.prevent="$root.setTab('general', 'deliverables')">Deliverables</a>
										</li>
										<li :class="{'current-item': $root.subtab === 'comments'}">
											<a href="#" @click.prevent="$root.setTab('general', 'comments')">Comments</a>
										</li>
										<li :class="{'current-item': $root.subtab === 'catalog'}">
											<a href="#" @click.prevent="$root.setTab('general', 'catalog')">Catalog mode</a>
										</li>
										<li :class="{'current-item': $root.subtab === 'labels'}">
											<a href="#" @click.prevent="$root.setTab('general', 'labels')">Labels</a>
										</li>
										<li :class="{'current-item': $root.subtab === 'other'}">
											<a href="#" @click.prevent="$root.setTab('general', 'other')">Other</a>
										</li>
									</ul>
								</div>

								<template v-if="subtab === 'base'">
									<div class="ts-form-group ts-col-1-1">
										<div class="ts-form-group">
											<label>Label</label>
											<input type="text" v-model="config.settings.label">
										</div>
										<div class="ts-form-group">
											<label>Key</label>
											<input type="text" v-model="config.settings.key" maxlength="20" required disabled>
										</div>
									</div>

									<div class="ts-tab-heading ts-col-1-1" style="padding-bottom: 0;">
										<h2>Product mode</h2>
										<p>Determine how products of this type will be sold</p>
									</div>

									<div class="ts-form-group ts-col-1-1">
										<div class="ts-radio-container two-column">
											<label class="container-radio">
												<h2 class="mb5">Booking calendar</h2>
												<p class="description">Create a product type which includes bookable days or timeslots</p>
												<input type="radio" v-model="config.settings.mode" value="booking">
												<span class="checkmark"></span>
											</label>
										</div>

										<div v-if="config.settings.mode === 'booking'" class="ts-row wrap-row" style="padding-left: 35px; margin-top: 15px;">
											<?php \Voxel\Form_Models\Select_Model::render( [
												'v-model' => 'config.calendar.type',
												'width' => '1/1',
												'label' => 'Get bookable instances from:',
												'choices' => [
													'booking' => 'Booking calendar',
													'recurring-date' => 'Recurring date field',
												],
											] ) ?>

											<template v-if="config.calendar.type === 'booking'">
												<?php \Voxel\Form_Models\Select_Model::render( [
													'v-model' => 'config.calendar.format',
													'width' => '1/1',
													'label' => 'Vendor can create bookable',
													'choices' => [
														'days' => 'Days',
														'slots' => 'Time slots',
													],
												] ) ?>

												<?php \Voxel\Form_Models\Switcher_Model::render( [
													'v-model' => 'config.calendar.allow_range',
													'v-if' => 'config.calendar.format === "days"',
													'width' => '1/1',
													'label' => 'Vendor can create bookable day ranges',
												] ) ?>

												<?php \Voxel\Form_Models\Select_Model::render( [
													'v-model' => 'config.calendar.range_mode',
													'v-if' => 'config.calendar.format === "days" && config.calendar.allow_range',
													'label' => 'Count range length using',
													'choices' => [
														'days' => 'Days: Count the number of days in the selected range',
														'nights' => 'Nights: Count the number of nights in the selected range',
													],
												] ) ?>
											</template>
										</div>
									</div>
									<div class="ts-form-group ts-col-1-1">
										<div class="ts-radio-container two-column">
											<label class="container-radio">
												<h2 class="mb5">Regular product</h2>
												<p class="description">Create a regular product</p>
												<input type="radio" v-model="config.settings.mode" value="regular">
												<span class="checkmark"></span>
											</label>
										</div>
									</div>
									<div class="ts-form-group ts-col-1-1">
										<div class="ts-radio-container two-column">
											<label class="container-radio">
												<h2 class="mb5">Claim post</h2>
												<p class="description">Create a product type that can transfer ownership of a post after order completion</p>
												<input type="radio" v-model="config.settings.mode" value="claim">
												<span class="checkmark"></span>
											</label>
										</div>
									</div>
								</template>
								<template v-else-if="subtab === 'catalog'">
									<?php \Voxel\Form_Models\Switcher_Model::render( [
										'v-model' => 'config.settings.catalog_mode.active',
										'label' => 'Enable catalog mode',
									] ) ?>

									<template v-if="config.settings.catalog_mode.active">
										<?php \Voxel\Form_Models\Switcher_Model::render( [
											'v-model' => 'config.settings.catalog_mode.requires_approval',
											'label' => 'Catalog mode: Orders require vendor approval',
										] ) ?>

										<?php \Voxel\Form_Models\Switcher_Model::render( [
											'v-model' => 'config.settings.catalog_mode.refunds_allowed',
											'label' => 'Catalog mode: Allow refund requests',
										] ) ?>
									</template>
								</template>
								<template v-else-if="subtab === 'tags'">
									<div class="ts-col-1-1">
										<order-tags></order-tags>
									</div>
								</template>
								<template v-else-if="subtab === 'deliverables'">
									<div class="ts-col-1-1">
										<p>
											Deliverables allow product vendors to securely share files with their customers.
											This can be done automatically when the order is completed, or manually by the vendor at any time.
											Deliverables are stored securely, without direct link access.
										</p>
									</div>

									<?php \Voxel\Form_Models\Switcher_Model::render( [
										'v-model' => 'config.settings.deliverables.enabled',
										'label' => 'Enable deliverables',
									] ) ?>

									<template v-if="config.settings.deliverables.enabled">
										<?php \Voxel\Form_Models\Checkboxes_Model::render( [
											'v-model' => 'config.settings.deliverables.delivery_methods',
											'label' => 'Delivery methods',
											'choices' => [
												'automatic' => 'Automatic: Share files automatically when the order is completed',
												'manual' => 'Manual: Files are manually delivered by the vendor after the order is completed',
											],
										] ) ?>

										<?php \Voxel\Form_Models\Number_Model::render( [
											'v-model' => 'config.settings.deliverables.download_limit',
											'label' => 'Download limit (per file). Leave empty for unlimited downloads.',
										] ) ?>

										<div class="ts-tab-subheading ts-col-1-1">
											<h3>Uploads</h3>
										</div>

										<?php \Voxel\Form_Models\Number_Model::render( [
											'v-model' => 'config.settings.deliverables.uploads.max_size',
											'label' => 'Max file size (kB)',
											'width' => '1/2',
										] ) ?>

										<?php \Voxel\Form_Models\Number_Model::render( [
											'v-model' => 'config.settings.deliverables.uploads.max_count',
											'label' => 'Max file count',
											'width' => '1/2',
										] ) ?>

										<?php \Voxel\Form_Models\Checkboxes_Model::render( [
											'v-model' => 'config.settings.deliverables.uploads.allowed_file_types',
											'label' => 'Allowed file types',
											'choices' => array_combine( get_allowed_mime_types(), get_allowed_mime_types() ),
										] ) ?>
									</template>
								</template>
								<template v-else-if="subtab === 'comments'">
									<div class="ts-tab-subheading ts-col-1-1">
										<h3>Comment attachments</h3>
									</div>

									<?php \Voxel\Form_Models\Number_Model::render( [
										'v-model' => 'config.settings.comments.uploads.max_size',
										'label' => 'Max file size (kB)',
										'width' => '1/2',
									] ) ?>

									<?php \Voxel\Form_Models\Number_Model::render( [
										'v-model' => 'config.settings.comments.uploads.max_count',
										'label' => 'Max file count',
										'width' => '1/2',
									] ) ?>

									<?php \Voxel\Form_Models\Checkboxes_Model::render( [
										'v-model' => 'config.settings.comments.uploads.allowed_file_types',
										'label' => 'Allowed file types',
										'choices' => array_combine( get_allowed_mime_types(), get_allowed_mime_types() ),
									] ) ?>
								</template>
								<template v-else-if="subtab === 'other'">
									<?php \Voxel\Form_Models\Switcher_Model::render( [
										'v-model' => 'config.settings.notes.enabled',
										'width' => '1/1',
										'label' => 'Enable vendor notes',
									] ) ?>

									<?php \Voxel\Form_Models\Switcher_Model::render( [
										'v-model' => 'config.settings.skip_main_step',
										'width' => '1/1',
										'label' => 'Product form: Skip first step when there are no additions/booking calendar',
									] ) ?>
								</template>
								<template v-else-if="subtab === 'labels'">
									<div class="ts-col-1-1">
										<div class="ts-form-group">
											<strong>Product field</strong>
										</div>
										<div class="ts-form-group">
											<label>Base price</label>
											<input type="text" v-model="config.settings.l10n.field.base_price">
										</div>
										<div class="ts-form-group">
											<label>Quantity per day</label>
											<input type="text" v-model="config.settings.l10n.field.instances_per_day">
										</div>
										<div class="ts-form-group">
											<label>Quantity per timeslot</label>
											<input type="text" v-model="config.settings.l10n.field.instances_per_slot">
										</div>
										<div class="ts-form-group">
											<label>Notes label</label>
											<input type="text" v-model="config.settings.l10n.field.notes.label">
										</div>
										<div class="ts-form-group">
											<label>Notes placeholder</label>
											<input type="text" v-model="config.settings.l10n.field.notes.placeholder">
										</div>
										<div class="ts-form-group">
											<label>Notes description</label>
											<textarea v-model="config.settings.l10n.field.notes.description"></textarea>
										</div>
									</div>

									<div class="ts-col-1-1">
										<div class="ts-form-group">
											<strong>Product form</strong>
										</div>
										<div class="ts-form-group">
											<label>Check-in</label>
											<input type="text" v-model="config.settings.l10n.form.check_in">
										</div>
										<div class="ts-form-group">
											<label>Check-out</label>
											<input type="text" v-model="config.settings.l10n.form.check_out">
										</div>
										<div class="ts-form-group">
											<label>Choose date</label>
											<input type="text" v-model="config.settings.l10n.form.pick_date">
										</div>
									</div>
								</template>
							</div>
						</div>
					</div>
					<div v-if="tab === 'pricing'" class="inner-tab ts-row wrap-row all-center">
						<div class="ts-col-1-2">
							<div class="ts-row wrap-row">
								<div class="ts-tab-heading ts-col-1-1">
									<h1>Pricing</h1>
									<p>Configure how product prices will be calculated and paid</p>
								</div>

								<div class="ts-col-1-1">
									<ul class="inner-tabs">
										<li :class="{'current-item': $root.subtab === 'base'}">
											<a href="#" @click.prevent="$root.setTab('pricing', 'base')">Pricing</a>
										</li>
										<li :class="{'current-item': $root.subtab === 'base_price'}">
											<a href="#" @click.prevent="$root.setTab('pricing', 'base_price')">Base price</a>
										</li>
										<li :class="{'current-item': $root.subtab === 'advanced'}">
											<a href="#" @click.prevent="$root.setTab('pricing', 'advanced')">Advanced</a>
										</li>
									</ul>
								</div>

								<template v-if="subtab === 'base'">
									<?php \Voxel\Form_Models\Select_Model::render( [
										'v-model' => 'config.settings.payments.mode',
										'label' => 'Payment mode',
										'choices' => [
											'payment' => 'Single payment: Users pay once for products of this type',
											'subscription' => 'Subscription: Users pay on a recurring interval for products of this type',
										],
									] ) ?>

									<?php \Voxel\Form_Models\Select_Model::render( [
										'v-model' => 'config.settings.payments.transfer_destination',
										'label' => 'Upon successful payment, funds are transferred to:',
										'choices' => [
											'vendor_account' => 'Vendor: Funds are transferred directly to the seller\'s account',
											'admin_account' => 'Admin: Funds are transferred to the admin account',
										],
									] ) ?>

									<?php \Voxel\Form_Models\Select_Model::render( [
										'v-if' => 'config.settings.payments.mode === \'payment\'',
										'v-model' => 'config.settings.payments.capture_method',
										'label' => 'Funds capture method',
										'choices' => [
											'automatic' => 'Automatic: Capture funds when the customer authorizes the payment.',
											'manual' => 'Manual: Capture funds when the vendor approves the customer order.',
										],
									] ) ?>

									<template v-if="config.settings.payments.transfer_destination === 'vendor_account'">
										<template v-if="config.settings.payments.mode === 'subscription'">
											<?php \Voxel\Form_Models\Number_Model::render( [
												'v-model' => 'config.checkout.application_fee.amount',
												'label' => 'Platform fee on subscription sales (in percentage)',
												'min' => 0,
												'max' => 100,
											] ) ?>
										</template>
										<template v-else>
											<?php \Voxel\Form_Models\Radio_Buttons_Model::render( [
												'v-model' => 'config.checkout.application_fee.type',
												'label' => 'Platform fee on product sales',
												'choices' => [
													'percentage' => 'Percentage of product price',
													'fixed_amount' => 'Fixed amount',
												],
											] ) ?>

											<?php \Voxel\Form_Models\Number_Model::render( [
												'v-model' => 'config.checkout.application_fee.amount',
												'v-if' => 'config.checkout.application_fee.type === "percentage"',
												'label' => 'Percentage',
												'min' => 0,
												'max' => 100,
											] ) ?>

											<?php \Voxel\Form_Models\Number_Model::render( [
												'v-model' => 'config.checkout.application_fee.amount',
												'v-if' => 'config.checkout.application_fee.type === "fixed_amount"',
												'label' => 'Amount (in cents)',
												'min' => 0,
											] ) ?>
										</template>

										<?php \Voxel\Form_Models\Switcher_Model::render( [
											'v-model' => 'config.checkout.on_behalf_of',
											'label' => 'Make the vendor account the business of record for the payment',
										] ) ?>

										<div class="ts-col-1-1" style="margin-top: -5px;">
											<details>
												<summary>Details</summary>
												<p>
												When enabled, Stripe automatically:<br>
												 - Settles charges in the country of the specified account, thereby minimizing declines and avoiding currency conversions.</br>
												 - Uses the fee structure for the connected account’s country.</br>
												 - Uses the connected account’s statement descriptor.</br>
												 - If the account is in a different country than the platform, the connected account’s address and phone number shows up on the customer’s credit card statement (as opposed to the platform’s).</br>
												 - The number of days that a pending balance is held before being paid out depends on the delay_days setting on the connected account.</br>
												 - Not compatible with automatic tax calculation.<br><br>
												Source: <a href="https://stripe.com/docs/connect/charges#on_behalf_of" target="_blank">https://stripe.com/docs/connect/charges#on_behalf_of</a>
												</p>
											</details>
										</div>
									</template>

								</template>
								<template v-else-if="subtab === 'base_price'">
									<?php \Voxel\Form_Models\Switcher_Model::render( [
										'v-model' => 'config.settings.base_price.active',
										'label' => 'Enable base price',
										'description' => 'Allows vendor to enter a base price for the product, before taking into account additions',
									] ) ?>

									<?php \Voxel\Form_Models\Number_Model::render( [
										'v-model' => 'config.settings.base_price.default_price',
										'label' => "{{ config.settings.base_price.active ? 'Default base price' : 'Predefined price' }}",
									] ) ?>
								</template>
								<template v-else-if="subtab === 'advanced'">
									<?php \Voxel\Form_Models\Radio_Buttons_Model::render( [
										'v-model' => 'config.settings.payments.pricing',
										'label' => 'Product pricing',
										'choices' => [
											'dynamic' => <<<HTML
												<span>Dynamic</span>
												<p class="mt0">Price is calculated as the sum of the base price and used additions.</p>
											HTML,
											'price_id' => <<<HTML
												<span>Price ID</span>
												<p class="mt0">
													Price references a product price ID created directly in the Stripe dashboard.
													Product additions are not available with this method.
												</p>
											HTML,
										],
									] ) ?>
								</template>
							</div>
						</div>
					</div>
					<div v-if="tab === 'additions'" class="inner-tab ts-row wrap-row all-center">
						<product-additions></product-additions>
					</div>
					<div v-if="tab === 'fields'" class="inner-tab ts-row wrap-row all-center">
						<information-fields></information-fields>
					</div>
					<div v-if="tab === 'checkout'" class="inner-tab ts-row wrap-row all-center">
						<div class="ts-col-1-2">

							<div class="ts-row wrap-row">
								<div class="ts-tab-heading ts-col-1-1">
									<h1>Checkout</h1>
									<p>Configure checkout</p>
								</div>

								<div class="ts-col-1-1">
									<ul class="inner-tabs">
										<li :class="{'current-item': $root.subtab === 'tax'}">
											<a href="#" @click.prevent="$root.setTab('checkout', 'tax')">Tax collection</a>
										</li>
										<li :class="{'current-item': $root.subtab === 'shipping'}">
											<a href="#" @click.prevent="$root.setTab('checkout', 'shipping')">Shipping</a>
										</li>
										<li :class="{'current-item': $root.subtab === 'promotions'}">
											<a href="#" @click.prevent="$root.setTab('checkout', 'promotions')">Promotion codes</a>
										</li>
									</ul>
								</div>
							</div>

							<div class="ts-row wrap-row">
								<template v-if="subtab === 'tax'">
									<?php \Voxel\Form_Models\Switcher_Model::render( [
										'v-model' => 'config.checkout.tax.auto.tax_id_collection',
										'label' => 'Enable customer tax ID collection',
										'sublabel' => sprintf( 'See list of supported countries <a href="%s" target="_blank">here</a>', 'https://stripe.com/docs/tax/checkout/tax-ids#supported-types' ),
									] ) ?>

									<?php \Voxel\Form_Models\Select_Model::render( [
										'v-model' => 'config.checkout.tax.mode',
										'label' => 'Tax collection mode',
										'choices' => [
											'auto' => 'Automatic',
											'manual' => 'Manual',
											'none' => 'None',
										],
									] ) ?>

									<template v-if="config.checkout.tax.mode === 'auto'">
										<div class="ts-form-group ts-col-1-1">
											<h3>Collect taxes automatically using <a href="https://stripe.com/tax" target="_blank">Stripe Tax</a></h3>
											<p>
												<a href="<?= esc_url( \Voxel\Stripe::dashboard_url( '/settings/tax' ) ) ?>" target="_blank">Configure Stripe Tax</a>
												<span> &middot; </span>
												<a href="https://stripe.com/docs/tax/tax-codes" target="_blank">Available Tax Codes</a>
											</p>
										</div>

										<?php \Voxel\Form_Models\Select_Model::render( [
											'v-model' => 'config.checkout.tax.auto.tax_code',
											'label' => 'Tax code',
											'choices' => [ '' => 'Select a code' ] + \Voxel\Stripe\Tax_Codes::all(),
										] ) ?>

										<?php \Voxel\Form_Models\Select_Model::render( [
											'v-model' => 'config.checkout.tax.auto.tax_behavior',
											'label' => 'Tax behavior',
											'choices' => [
												'inclusive' => 'Inclusive',
												'exclusive' => 'Exclusive',
											],
										] ) ?>
									</template>

									<template v-if="config.checkout.tax.mode === 'manual'">
										<div class="ts-form-group ts-col-1-1 ts-tab-subheading" style="margin-bottom: 0 !important;">
											<h3>
												Collect taxes manually using Tax Rates
											</h3>
											<div class="basic-ul" style="margin-top: 15px;">
												<a href="<?= esc_url( \Voxel\Stripe::dashboard_url( '/tax-rates' ) ) ?>" target="_blank" class="ts-button ts-faded">
													<i class="las la-external-link-alt icon-sm"></i>
													Setup tax rates
												</a>
											</div>
										</div>

										<div class="ts-form-group ts-col-1-2">
											<h4>Live mode</h4>
											<rate-list
												v-model="config.checkout.tax.manual.tax_rates"
												mode="live"
												source="backend.list_tax_rates"
											></rate-list>
										</div>

										<div class="ts-form-group ts-col-1-2">
											<h4>Test mode</h4>
											<rate-list
												v-model="config.checkout.tax.manual.test_tax_rates"
												mode="test"
												source="backend.list_tax_rates"
											></rate-list>
										</div>
									</template>
								</template>
								<template v-else-if="subtab === 'shipping'">
									<?php \Voxel\Form_Models\Switcher_Model::render( [
										'v-model' => 'config.checkout.shipping.enabled',
										'label' => 'Enable shipping',
									] ) ?>

									<template v-if="config.checkout.shipping.enabled">
										<div class="ts-form-group ts-col-1-1 ts-tab-subheading">
											<h3>
												Shipping Rates
											</h3>
											<p>
												<a href="<?= esc_url( \Voxel\Stripe::dashboard_url( '/shipping-rates' ) ) ?>" target="_blank">Manage Shipping Rates</a>
											</p>
										</div>

										<div class="ts-form-group ts-col-1-2">
											<h4>Live mode</h4>

											<rate-list
												v-model="config.checkout.shipping.shipping_rates"
												mode="live"
												source="backend.list_shipping_rates"
											></rate-list>
										</div>

										<div class="ts-form-group ts-col-1-2">
											<h4>Test mode</h4>

											<rate-list
												v-model="config.checkout.shipping.test_shipping_rates"
												mode="test"
												source="backend.list_shipping_rates"
											></rate-list>
										</div>

										<?php \Voxel\Form_Models\Checkboxes_Model::render( [
											'v-model' => 'config.checkout.shipping.allowed_countries',
											'label' => 'Allowed countries',
											'description' => sprintf(
												'These countries are currently not supported: %s',
												"\n - ".join( "\n - ", \Voxel\Stripe\Country_Codes::shipping_unsupported() )
											),
											'columns' => 'two',
											'choices' => \Voxel\Stripe\Country_Codes::shipping_supported(),
										] ) ?>
									</template>
								</template>
								<template v-else-if="subtab === 'promotions'">
									<?php \Voxel\Form_Models\Switcher_Model::render( [
										'v-model' => 'config.checkout.promotion_codes.enabled',
										'label' => 'Allow promotion codes in checkout',
									] ) ?>

									<div class="ts-col-1-1">
										<div class="basic-ul">
											<a href="<?= esc_url( \Voxel\Stripe::dashboard_url( '/coupons' ) ) ?>" target="_blank" class="ts-button ts-faded">
												<i class="las la-external-link-alt icon-sm"></i>
												Manage promotion codes
											</a>
										</div>
									</div>
								</template>
							</div>
						</div>
					</div>
				</div>

				<?php if ( \Voxel\is_dev_mode() ): ?>
					<!-- <pre debug>{{ config }}</pre> -->
				<?php endif ?>
			</div>
		</form>
	</div>
</div>

<?php require_once locate_template( 'templates/backend/product-types/components/rate-list-component.php' ) ?>
