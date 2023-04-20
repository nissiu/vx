<?php
/**
 * Admin general settings.
 *
 * @since 1.0
 */

if ( ! defined('ABSPATH') ) {
	exit;
}

wp_enqueue_script('vx:general-settings.js');
?>
<div class="wrap">
	<div id="vx-general-settings" data-config="<?= esc_attr( wp_json_encode( $config ) ) ?>" v-cloak>
		<form method="POST" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ) ?>" @submit="state.submit_config = JSON.stringify( config )">
			<div class="edit-cpt-header">
				<div class="ts-container cpt-header-container">
					<div class="ts-row wrap-row">
						<div class="ts-col-1-2 v-center ">
							<h1>General Settings</h1>
						</div>
						<div class="cpt-header-buttons ts-col-1-2 v-center">
							<input type="hidden" name="config" :value="state.submit_config">

							<input type="hidden" name="action" value="voxel_save_general_settings">
							<?php wp_nonce_field( 'voxel_save_general_settings' ) ?>

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
				<div class="ts-row wrap-row">
					<div class="ts-col-1-3">
						<ul class="inner-tabs vertical-tabs">
							<li :class="{'current-item': tab === 'membership'}">
								<a href="#" @click.prevent="tab = 'membership'">Membership</a>
							</li>
							<li :class="{'current-item': tab === 'stripe'}">
								<a href="#" @click.prevent="tab = 'stripe'">Stripe</a>
							</li>
							<li :class="{'current-item': tab === 'stripe.portal'}">
								<a href="#" @click.prevent="tab = 'stripe.portal'">Stripe Customer Portal</a>
							</li>
							<li :class="{'current-item': tab === 'maps'}">
								<a href="#" @click.prevent="tab = 'maps'">Maps</a>
							</li>
							<li :class="{'current-item': tab === 'auth.google'}">
								<a href="#" @click.prevent="tab = 'auth.google'">Login with Google</a>
							</li>
							<li :class="{'current-item': tab === 'recaptcha'}">
								<a href="#" @click.prevent="tab = 'recaptcha'">Recaptcha</a>
							</li>
							<li :class="{'current-item': tab === 'timeline'}">
								<a href="#" @click.prevent="tab = 'timeline'">Timeline</a>
							</li>
							<li :class="{'current-item': tab === 'notifications'}">
								<a href="#" @click.prevent="tab = 'notifications'">Notifications</a>
							</li>
							<li :class="{'current-item': tab === 'dms'}">
								<a href="#" @click.prevent="tab = 'dms'">Direct Messages</a>
							</li>
							<li :class="{'current-item': tab === 'emails'}">
								<a href="#" @click.prevent="tab = 'emails'">Emails</a>
							</li>
							<li :class="{'current-item': tab === 'nav_menus'}">
								<a href="#" @click.prevent="tab = 'nav_menus'">Nav menus</a>
							</li>
							<li :class="{'current-item': tab === 'db'}">
								<a href="#" @click.prevent="tab = 'db'">Database</a>
							</li>
							<li :class="{'current-item': tab === 'other'}">
								<a href="#" @click.prevent="tab = 'other'">Other</a>
							</li>
						</ul>
					</div>

					<div v-if="tab === 'recaptcha'" class="ts-col-1-2">
						<div class="ts-tab-heading no-top-space">
							<h1>Google recaptcha v3</h1>
							<p>Configure Google reCAPTCHA in the <a href="https://www.google.com/recaptcha/admin" target="_blank">v3 Admin Console</a></p>
						</div>
						<div class="ts-row wrap-row">
							<?php \Voxel\Form_Models\Switcher_Model::render( [
								'v-model' => 'config.recaptcha.enabled',
								'label' => 'Enable reCAPTCHA',
							] ) ?>

							<?php \Voxel\Form_Models\Text_Model::render( [
								'v-model' => 'config.recaptcha.key',
								'label' => 'Site key',
							] ) ?>

							<?php \Voxel\Form_Models\Text_Model::render( [
								'v-model' => 'config.recaptcha.secret',
								'label' => 'Secret key',
							] ) ?>
						</div>
					</div>

					<div v-else-if="tab === 'stripe'" class="ts-col-2-3">
						<div class="ts-tab-heading no-top-space">
							<h1>Stripe</h1>
							<p>Add your Stripe account details</p>
						</div>
						<div class="ts-row wrap-row">
							<?php \Voxel\Form_Models\Select_Model::render( [
								'v-model' => 'config.stripe.currency',
								'label' => 'Currency',
								'choices' => \Voxel\Stripe\Currencies::all(),
							] ) ?>

							<div class="ts-tab-subheading ts-col-1-1">
								<h2>API keys</h2>
								<p>
									Enter your Stripe account API keys. You can get your keys in
									<a href="https://dashboard.stripe.com/apikeys" target="_blank">dashboard.stripe.com/apikeys</a>
								</p>
							</div>

							<?php \Voxel\Form_Models\Text_Model::render( [
								'v-model' => 'config.stripe.key',
								'label' => 'Public key',
							] ) ?>

							<?php \Voxel\Form_Models\Password_Model::render( [
								'v-model' => 'config.stripe.secret',
								'label' => 'Secret key',
							] ) ?>

							<div class="ts-tab-subheading ts-col-1-1">
								<h2>Test mode</h2>
							</div>

							<?php \Voxel\Form_Models\Switcher_Model::render( [
								'v-model' => 'config.stripe.test_mode',
								'label' => 'Enable Stripe test mode',
							] ) ?>

							<template v-if="config.stripe.test_mode">
								<?php \Voxel\Form_Models\Text_Model::render( [
									'v-model' => 'config.stripe.test_key',
									'label' => 'Test public key',
								] ) ?>

								<?php \Voxel\Form_Models\Password_Model::render( [
									'v-model' => 'config.stripe.test_secret',
									'label' => 'Test secret key',
								] ) ?>
							</template>

							<div class="ts-tab-subheading ts-col-1-1">
								<h2>Webhook endpoints</h2>
								<ul class="inner-tabs">
									<li :class="{'current-item': webhooks.tab === 'live'}">
										<a href="#" @click.prevent="webhooks.tab = 'live'">Live mode</a>
									</li>
									<li :class="{'current-item': webhooks.tab === 'test'}">
										<a href="#" @click.prevent="webhooks.tab = 'test'">Test mode</a>
									</li>
									<li :class="{'current-item': webhooks.tab === 'local'}">
										<a href="#" @click.prevent="webhooks.tab = 'local'">Local</a>
									</li>
								</ul>
							</div>

							<template v-if="webhooks.tab === 'live'">
								<?php if ( $config['stripe']['secret'] ): ?>
									<div class="ts-col-1-1 mt0">
										<p><i class="las la-check"></i> Live mode webhook endpoints are active.</p>
										<a href="#" @click.prevent="checkEndpointStatus('live')" ref="liveEndpointStatus" class="ts-button ts-faded ts-btn-small">Check status</a>&nbsp;
										<a href="#" @click.prevent="checkEndpointStatus('live', true)" class="ts-button ts-faded ts-btn-small">Stripe Connect status</a>&nbsp;
										<a href="#" @click.prevent="webhooks.liveDetails = ! webhooks.liveDetails" class="ts-button ts-faded ts-btn-small">Details</a>&nbsp;
										<a
											href="https://dashboard.stripe.com/webhooks/<?= esc_attr( $config['stripe']['webhooks']['live']['id'] ) ?>"
											target="_blank"
											class="ts-button ts-faded ts-btn-small"
										>Open in Stripe Dashboard</a>
									</div>
									<template v-if="webhooks.liveDetails">
										<div class="ts-col-1-1" :class="{'vx-disabled': !webhooks.editLiveDetails}">
											<div class="ts-form-group">
												<label>Endpoint ID</label>
												<input type="text" v-model="config.stripe.webhooks.live.id">
											</div>
											<div class="ts-form-group">
												<label>Endpoint secret</label>
												<input type="text" v-model="config.stripe.webhooks.live.secret">
											</div>
											<div class="ts-form-group">
												<label>Connect endpoint ID</label>
												<input type="text" v-model="config.stripe.webhooks.live_connect.id">
											</div>
											<div class="ts-form-group">
												<label>Connect endpoint secret</label>
												<input type="text" v-model="config.stripe.webhooks.live_connect.secret">
											</div>
										</div>
										<div class="ts-col-1-1 text-right">
											<a
												href="#"
												class="ts-button ts-btn-small ts-icon-btn ts-transparent"
												@click.prevent="webhooks.editLiveDetails = !webhooks.editLiveDetails"
											>Modify</a>
										</div>
									</template>
								<?php else: ?>
									<div class="ts-form-group ts-col-1-1">
										Stripe API keys are required to setup webhook endpoints.
									</div>
								<?php endif ?>
							</template>

							<template v-else-if="webhooks.tab === 'test'">
								<?php if ( $config['stripe']['test_secret'] ): ?>
									<div class="ts-col-1-1 mt0">
										<p><i class="las la-check"></i> Test mode webhook endpoints are active.</p>
										<a href="#" @click.prevent="checkEndpointStatus('test')" class="ts-button ts-faded ts-btn-small">Check status</a>&nbsp;
										<a href="#" @click.prevent="checkEndpointStatus('test', true)" class="ts-button ts-faded ts-btn-small">Stripe Connect status</a>&nbsp;
										<a href="#" @click.prevent="webhooks.testDetails = ! webhooks.testDetails" class="ts-button ts-faded ts-btn-small">Details</a>&nbsp;
										<a
											href="https://dashboard.stripe.com/test/webhooks/<?= esc_attr( $config['stripe']['webhooks']['test']['id'] ) ?>"
											target="_blank"
											class="ts-button ts-faded ts-btn-small"
										>Open in Stripe Dashboard</a>
									</div>
									<template v-if="webhooks.testDetails">
										<div class="ts-col-1-1" :class="{'vx-disabled': !webhooks.editTestDetails}">
											<div class="ts-form-group">
												<label>Endpoint ID</label>
												<input type="text" v-model="config.stripe.webhooks.test.id">
											</div>
											<div class="ts-form-group">
												<label>Endpoint secret</label>
												<input type="text" v-model="config.stripe.webhooks.test.secret">
											</div>
											<div class="ts-form-group">
												<label>Connect endpoint ID</label>
												<input type="text" v-model="config.stripe.webhooks.test_connect.id">
											</div>
											<div class="ts-form-group">
												<label>Connect endpoint secret</label>
												<input type="text" v-model="config.stripe.webhooks.test_connect.secret">
											</div>
										</div>
										<div class="ts-col-1-1 text-right">
											<a
												href="#"
												class="ts-button ts-btn-small ts-icon-btn ts-transparent"
												@click.prevent="webhooks.editTestDetails = !webhooks.editTestDetails"
											>Modify</a>
										</div>
									</template>
								<?php else: ?>
									<div class="ts-form-group ts-col-1-1">
										Test mode API keys are required to setup webhook endpoints.
									</div>
								<?php endif ?>
							</template>

							<template v-else-if="webhooks.tab === 'local'">
								<?php \Voxel\Form_Models\Switcher_Model::render( [
									'v-model' => 'config.stripe.webhooks.local.enabled',
									'label' => 'This is a local installation',
								] ) ?>

								<div v-if="config.stripe.webhooks.local.enabled" class="ts-form-group ts-col-1-1">
									<h3>Follow these steps to setup Stripe webhook events on a local installation:</h3>
									<ol>
										<li>
											<a href="https://stripe.com/docs/stripe-cli" target="_blank">Install the Stripe CLI</a>
											and log in to authenticate your account.
										</li>
										<li>
											Forward webhook events to your local endpoint using the following command:<br>
											<pre class="ts-snippet"><span class="ts-green">stripe</span> listen <span class="ts-italic">--forward-to="<?= home_url('?vx=1&action=stripe.webhooks') ?>"</span></pre>
										</li>
										<li>
											Paste the generated webhook signing secret below.
										</li>
									</ol>
								</div>

								<div v-if="config.stripe.webhooks.local.enabled" class="ts-form-group ts-col-1-1">
									<label>Webhook secret from Stripe CLI</label>
									<input type="text" v-model="config.stripe.webhooks.local.secret">
									<p>Read more about local testing <a href="https://stripe.com/docs/webhooks/test" target="_blank">here.</a></p>
								</div>
							</template>
						</div>
					</div>

					<div v-if="tab === 'stripe.portal'" class="ts-col-1-2">
						<div class="ts-tab-heading no-top-space">
							<h1>Stripe Customer Portal</h1>
							<p>Stripe customer portal allows your customers to edit their payment methods, view invoice history, and update their customer details.</p>
						</div>
						<div class="ts-row wrap-row">
							<?php \Voxel\Form_Models\Switcher_Model::render( [
								'v-model' => 'config.stripe.portal.invoice_history',
								'label' => 'Show invoice history',
							] ) ?>

							<?php \Voxel\Form_Models\Switcher_Model::render( [
								'v-model' => 'config.stripe.portal.customer_update.enabled',
								'label' => 'Allow updating details',
							] ) ?>

							<?php \Voxel\Form_Models\Checkboxes_Model::render( [
								'v-if' => 'config.stripe.portal.customer_update.enabled',
								'v-model' => 'config.stripe.portal.customer_update.allowed_updates',
								'label' => 'Allowed fields',
								'choices' => [
									'email' => 'Email',
									'address' => 'Billing address',
									'shipping' => 'Shipping address',
									'phone' => 'Phone numbers',
									'tax_id' => 'Tax IDs',
								],
							] ) ?>

							<?php if ( \Voxel\is_dev_mode() ): ?>
								<div class="ts-form-group ts-col-1-1">
									<span class="dev-mode"></span>
									<p>Live configuration ID: {{ config.stripe.portal.live_config_id }}</p>
									<p>Test configuration ID: {{ config.stripe.portal.test_config_id }}</p>
								</div>
							<?php endif ?>
						</div>
					</div>

					<div v-if="tab === 'membership'" class="ts-col-1-2">
						<div class="ts-tab-heading no-top-space">
							<h1>Membership</h1>
							<p>Configure registration and membership</p>
						</div>
						<div class="ts-row wrap-row">
							<div class="ts-form-group ts-col-1-1 ts-tab-subheading">
								<h3>Registration</h3>
							</div>
							<?php \Voxel\Form_Models\Switcher_Model::render( [
								'v-model' => 'config.membership.enabled',
								'label' => 'Enable user registration',
							] ) ?>

							<?php \Voxel\Form_Models\Select_Model::render( [
								'v-model' => 'config.membership.after_registration',
								'label' => 'After registration is complete',
								'choices' => [
									'welcome_step' => 'Show welcome screen',
									'redirect_back' => 'Redirect back where the user left off',
								],
							] ) ?>

							<?php \Voxel\Form_Models\Switcher_Model::render( [
								'v-model' => 'config.membership.require_verification',
								'label' => 'Require email verification',
							] ) ?>

							<div class="ts-form-group ts-col-1-1 ts-tab-subheading">
								<h3>Membership</h3>
							</div>

							<?php \Voxel\Form_Models\Switcher_Model::render( [
								'v-model' => 'config.membership.plans_enabled',
								'label' => 'Enable membership plans',
							] ) ?>

							<?php \Voxel\Form_Models\Switcher_Model::render( [
								'v-model' => 'config.membership.show_plans_on_signup',
								'label' => 'Show plans during registration',
							] ) ?>

							<?php \Voxel\Form_Models\Select_Model::render( [
								'v-model' => 'config.membership.update.proration_behavior',
								'label' => 'Proration behavior when switching between subscription plans',
								'choices' => [
									'create_prorations' => 'Create prorations',
									'always_invoice' => 'Create prorations and invoice immediately',
									'none' => 'Disable prorations',
								],
							] ) ?>

							<?php \Voxel\Form_Models\Select_Model::render( [
								'v-model' => 'config.membership.cancel.behavior',
								'label' => 'When a cancel request is submitted, cancel the subscription:',
								'choices' => [
									'at_period_end' => 'At the end of current billing period',
									'immediately' => 'Immediately',
								],
							] ) ?>

							<?php \Voxel\Form_Models\Switcher_Model::render( [
								'v-model' => 'config.membership.trial.enabled',
								'label' => 'Enable free trial',
							] ) ?>

							<?php \Voxel\Form_Models\Number_Model::render( [
								'v-if' => 'config.membership.trial.enabled',
								'v-model' => 'config.membership.trial.period_days',
								'label' => 'Trial period days',
							] ) ?>

							<div class="ts-form-group ts-col-1-1 ts-tab-subheading">
								<h3>Tax collection</h3>
							</div>

							<?php \Voxel\Form_Models\Switcher_Model::render( [
								'v-model' => 'config.membership.checkout.tax.tax_id_collection',
								'label' => 'Enable customer tax ID collection',
								'sublabel' => sprintf(
									'See list of supported countries <a href="%s" target="_blank">here</a>',
									'https://stripe.com/docs/tax/checkout/tax-ids#supported-types'
								),
							] ) ?>

							<?php \Voxel\Form_Models\Select_Model::render( [
								'v-model' => 'config.membership.checkout.tax.mode',
								'label' => 'Tax collection mode',
								'choices' => [
									'auto' => 'Automatic (Stripe Tax)',
									'manual' => 'Manual',
									'none' => 'None',
								],
							] ) ?>

							<template v-if="config.membership.checkout.tax.mode === 'manual'">
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
										v-model="config.membership.checkout.tax.manual.tax_rates"
										mode="live"
										source="backend.list_tax_rates"
									></rate-list>
								</div>

								<div class="ts-form-group ts-col-1-2">
									<h4>Test mode</h4>
									<rate-list
										v-model="config.membership.checkout.tax.manual.test_tax_rates"
										mode="test"
										source="backend.list_tax_rates"
									></rate-list>
								</div>
							</template>
							<div class="ts-form-group ts-col-1-1 ts-tab-subheading">
								<h3>Promotion codes</h3>
							</div>
							<?php \Voxel\Form_Models\Switcher_Model::render( [
								'v-model' => 'config.membership.checkout.promotion_codes.enabled',
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
						</div>
					</div>

					<div v-else-if="tab === 'auth.google'" class="ts-col-1-2">
						<div class="ts-tab-heading no-top-space">
							<h1>Login with Google</h1>
							<p>Configure project and retrieve client id & secret in the <a href="https://console.cloud.google.com/home" target="_blank">Google API Console</a></p>
						</div>
						<div class="ts-row wrap-row">
							<?php \Voxel\Form_Models\Switcher_Model::render( [
								'v-model' => 'config.auth.google.enabled',
								'label' => 'Enable Login with Google',
							] ) ?>

							<?php \Voxel\Form_Models\Text_Model::render( [
								'v-model' => 'config.auth.google.client_id',
								'label' => 'Client ID',
							] ) ?>

							<?php \Voxel\Form_Models\Text_Model::render( [
								'v-model' => 'config.auth.google.client_secret',
								'label' => 'Client secret',
							] ) ?>
						</div>
					</div>

					<div v-else-if="tab === 'maps'" class="ts-col-1-2">
						<div class="ts-tab-heading no-top-space">
							<h1>Maps</h1>
						</div>
						<div class="ts-row wrap-row">
							<?php \Voxel\Form_Models\Select_Model::render( [
								'v-model' => 'config.maps.provider',
								'label' => 'Map provider',
								'choices' => [
									'google_maps' => 'Google Maps',
									'mapbox' => 'Mapbox',
								],
							] ) ?>

							<template v-if="config.maps.provider === 'google_maps'">
								<div class="ts-form-group ts-col-1-1 ts-tab-subheading">
									<h3 style="font-size: 18px;">Google Maps</h3>
								</div>

								<?php \Voxel\Form_Models\Text_Model::render( [
									'v-model' => 'config.maps.google_maps.api_key',
									'label' => 'Google Maps api key',
								] ) ?>

								<div class="ts-form-group ts-col-1-1">
									<label>Custom map skin</label>
									<textarea v-model="config.maps.google_maps.skin" placeholder="Paste the map skin JSON code here" style="height: 100px"></textarea>
									<p>You can create custom map styles through the <a href="https://console.cloud.google.com/google/maps-apis/studio/styles" target="_blank">Google Maps Cloud Console</a>. Leave empty to use default map skin.</p>
								</div>

								<div class="ts-form-group ts-col-1-1 ts-tab-subheading">
									<h3>Localization</h3>
								</div>

								<div class="ts-form-group ts-col-1-1">
									<label>Language</label>
									<select v-model="config.maps.google_maps.language">
										<option value="">Default (browser detected)</option>
										<?php foreach ( \Voxel\Data\Google_Maps\Supported_Languages::all() as $key => $label ): ?>
											<option value="<?= $key ?>"><?= $label ?></option>
										<?php endforeach ?>
									</select>
								</div>

								<div class="ts-form-group ts-col-1-1">
									<label>Region</label>
									<select v-model="config.maps.google_maps.region">
										<option value="">All</option>
										<?php foreach ( \Voxel\Data\Country_List::all() as $country ): ?>
											<option value="<?= $country['alpha-2'] ?>"><?= $country['name'] ?></option>
										<?php endforeach ?>
									</select>
									<p>
										If you set the language of the map, it's important to consider setting the region too.
										This helps ensure that your application complies with local laws. If a region is set,
										address geocoding results will be biased towards that region too.
										<a href="https://developers.google.com/maps/documentation/javascript/localization" target="_blank">Read more</a>
									</p>
								</div>

								<div class="ts-form-group ts-col-1-1 ts-tab-subheading">
									<h3>Autocomplete</h3>
								</div>

								<div class="ts-form-group ts-col-1-1">
									<label>Search form: Autocomplete returns results for</label>
									<select v-model="config.maps.google_maps.autocomplete.feature_types">
										<option value="">All feature types</option>
										<option value="geocode">Geocoding results</option>
										<option value="address">Addresses</option>
										<option value="establishment">Establishments</option>
										<option value="(regions)">Regions</option>
										<option value="(cities)">Cities</option>
									</select>
								</div>

								<div class="ts-form-group ts-col-1-1">
									<label>Post submission form: Autocomplete returns results for</label>
									<select v-model="config.maps.google_maps.autocomplete.feature_types_in_submission">
										<option value="">All feature types</option>
										<option value="geocode">Geocoding results</option>
										<option value="address">Addresses</option>
										<option value="establishment">Establishments</option>
										<option value="(regions)">Regions</option>
										<option value="(cities)">Cities</option>
									</select>
									<p>
										Determine what kind of features should be searched by autocomplete.
										<a href="https://developers.google.com/maps/documentation/javascript/supported_types#table3" target="_blank">Read more</a> &middot;
										<a href="https://developers.google.com/maps/documentation/javascript/examples/places-autocomplete" target="_blank">View demo</a>
									</p>
								</div>

								<div class="ts-form-group ts-col-1-1">
									<label>Autocomplete returns results in</label>
									<select v-model="config.maps.google_maps.autocomplete.countries" multiple="multiple" style="height: 180px; padding-top: 15px; padding-bottom: 15px;">
										<?php foreach ( \Voxel\Data\Country_List::all() as $country ): ?>
											<option value="<?= $country['alpha-2'] ?>"><?= $country['name'] ?></option>
										<?php endforeach ?>
									</select>
									<p>Limit autocomplete results to one or more countries (max: 5).</p>
								</div>
							</template>
							<template v-if="config.maps.provider === 'mapbox'">
								<div class="ts-form-group ts-col-1-1 ts-tab-subheading">
									<h3 style="font-size: 18px;">Mapbox</h3>
								</div>

								<?php \Voxel\Form_Models\Text_Model::render( [
									'v-model' => 'config.maps.mapbox.api_key',
									'label' => 'Mapbox api key',
								] ) ?>

								<div class="ts-form-group ts-col-1-1">
									<label>Custom map skin</label>
									<input type="text" v-model="config.maps.mapbox.skin" placeholder="Paste the style URL here">
									<p>You can create custom map styles through <a href="https://studio.mapbox.com/" target="_blank">Mapbox Studio</a>. Leave empty to use default map skin.</p>
								</div>

								<div class="ts-form-group ts-col-1-1 ts-tab-subheading">
									<h3>Localization</h3>
								</div>

								<div class="ts-form-group ts-col-1-1">
									<label>Language</label>
									<select v-model="config.maps.mapbox.language">
										<option value="">Default (browser detected)</option>
										<optgroup label="Global coverage">
											<?php foreach ( \Voxel\Data\Mapbox\Supported_Languages::global_coverage() as $key => $label ): ?>
												<option value="<?= $key ?>"><?= $label ?></option>
											<?php endforeach ?>
										</optgroup>
										<optgroup label="Local coverage">
											<?php foreach ( \Voxel\Data\Mapbox\Supported_Languages::local_coverage() as $key => $label ): ?>
												<option value="<?= $key ?>"><?= $label ?></option>
											<?php endforeach ?>
										</optgroup>
										<optgroup label="Limited coverage">
											<?php foreach ( \Voxel\Data\Mapbox\Supported_Languages::limited_coverage() as $key => $label ): ?>
												<option value="<?= $key ?>"><?= $label ?></option>
											<?php endforeach ?>
										</optgroup>
									</select>
								</div>

								<div class="ts-form-group ts-col-1-1 ts-tab-subheading">
									<h3>Autocomplete</h3>
								</div>

								<?php \Voxel\Form_Models\Checkboxes_Model::render( [
									'v-model' => 'config.maps.mapbox.autocomplete.feature_types',
									'label' => 'Search form: Autocomplete returns results for',
									'choices' => [
										'country' => 'Countries',
										'region' => 'Regions',
										'postcode' => 'Postcodes',
										'district' => 'Districts',
										'place' => 'Places',
										'locality' => 'Localities',
										'neighborhood' => 'Neighborhoods',
										'address' => 'Addresses',
										'poi' => 'Points of interest',
									],
								] ) ?>

								<?php \Voxel\Form_Models\Checkboxes_Model::render( [
									'v-model' => 'config.maps.mapbox.autocomplete.feature_types_in_submission',
									'label' => 'Post submission form: Autocomplete returns results for',
									'choices' => [
										'country' => 'Countries',
										'region' => 'Regions',
										'postcode' => 'Postcodes',
										'district' => 'Districts',
										'place' => 'Places',
										'locality' => 'Localities',
										'neighborhood' => 'Neighborhoods',
										'address' => 'Addresses',
										'poi' => 'Points of interest',
									],
									'footnote' => <<<HTML
										Determine what kind of features should be searched by autocomplete. If left empty, all available features will be used.
										<a href="https://docs.mapbox.com/api/search/geocoding/#data-types" target="_blank">Read more</a>
									HTML,
								] ) ?>

								<div class="ts-form-group ts-col-1-1">
									<label>Autocomplete returns results in</label>
									<select v-model="config.maps.mapbox.autocomplete.countries" multiple="multiple" style="height: 180px; padding-top: 15px; padding-bottom: 15px;">
										<?php foreach ( \Voxel\Data\Country_List::all() as $country ): ?>
											<option value="<?= $country['alpha-2'] ?>"><?= $country['name'] ?></option>
										<?php endforeach ?>
									</select>
									<p>Limit autocomplete results to one or more countries.</p>
								</div>
							</template>

							<div class="ts-form-group ts-col-1-1 ts-tab-subheading">
								<h3>Post submission: Default map picker location</h3>
							</div>
							<div class="ts-form-group ts-col-1-2">
								<label>Latitude</label>
								<input v-model="config.maps.default_location.lat" type="number" min="-90" max="90" placeholder="42.5" step="any">
							</div>
							<div class="ts-form-group ts-col-1-2">
								<label>Longitude</label>
								<input v-model="config.maps.default_location.lng" type="number" min="-180" max="180" placeholder="21.0" step="any">
							</div>
						</div>
					</div>

					<div v-else-if="tab === 'timeline'" class="ts-col-1-2">
						<div class="ts-tab-heading no-top-space">
							<h1>Timeline</h1>
						</div>
						<div class="ts-row wrap-row">

							<div class="ts-form-group ts-col-1-1 ts-tab-subheading">
								<h3>Statuses</h3>
							</div>

							<?php \Voxel\Form_Models\Switcher_Model::render( [
								'v-model' => 'config.timeline.posts.editable',
								'label' => 'Allow editing',
							] ) ?>

							<?php \Voxel\Form_Models\Number_Model::render( [
								'v-model' => 'config.timeline.posts.maxlength',
								'label' => 'Max length (in characters)',
							] ) ?>

							<?php \Voxel\Form_Models\Switcher_Model::render( [
								'v-model' => 'config.timeline.posts.images.enabled',
								'label' => 'Allow image attachments',
							] ) ?>

							<?php \Voxel\Form_Models\Number_Model::render( [
								'v-if' => 'config.timeline.posts.images.enabled',
								'v-model' => 'config.timeline.posts.images.max_count',
								'label' => 'Max image count',
							] ) ?>

							<?php \Voxel\Form_Models\Number_Model::render( [
								'v-if' => 'config.timeline.posts.images.enabled',
								'v-model' => 'config.timeline.posts.images.max_size',
								'label' => 'Max image size (in kB)',
							] ) ?>

							<div class="ts-form-group ts-col-1-1 ts-tab-subheading">
								<h3>Replies</h3>
							</div>

							<?php \Voxel\Form_Models\Switcher_Model::render( [
								'v-model' => 'config.timeline.replies.editable',
								'label' => 'Allow editing',
							] ) ?>

							<?php \Voxel\Form_Models\Number_Model::render( [
								'v-model' => 'config.timeline.replies.maxlength',
								'label' => 'Max length (in characters)',
							] ) ?>

							<?php \Voxel\Form_Models\Number_Model::render( [
								'v-model' => 'config.timeline.replies.max_nest_level',
								'label' => 'Highest reply nesting level',
							] ) ?>

							<div class="ts-form-group ts-col-1-1 ts-tab-subheading">
								<h3>Post rate limiting</h3>
								<p>Limit the number of statuses a user can publish in a time period</p>
							</div>

							<?php \Voxel\Form_Models\Number_Model::render( [
								'v-model' => 'config.timeline.posts.rate_limit.time_between',
								'label' => 'Minimum time between posts (in seconds)',
							] ) ?>

							<?php \Voxel\Form_Models\Number_Model::render( [
								'v-model' => 'config.timeline.posts.rate_limit.hourly_limit',
								'label' => 'Maximum number of posts allowed in an hour',
							] ) ?>

							<?php \Voxel\Form_Models\Number_Model::render( [
								'v-model' => 'config.timeline.posts.rate_limit.daily_limit',
								'label' => 'Maximum number of posts allowed in a day',
							] ) ?>

							<div class="ts-form-group ts-col-1-1 ts-tab-subheading">
								<h3>Reply rate limiting</h3>
								<p>Limit the number of replies a user can publish in a time period</p>
							</div>

							<?php \Voxel\Form_Models\Number_Model::render( [
								'v-model' => 'config.timeline.replies.rate_limit.time_between',
								'label' => 'Minimum time between replies (in seconds)',
							] ) ?>

							<?php \Voxel\Form_Models\Number_Model::render( [
								'v-model' => 'config.timeline.replies.rate_limit.hourly_limit',
								'label' => 'Maximum number of replies allowed in an hour',
							] ) ?>

							<?php \Voxel\Form_Models\Number_Model::render( [
								'v-model' => 'config.timeline.replies.rate_limit.daily_limit',
								'label' => 'Maximum number of replies allowed in a day',
							] ) ?>
						</div>
					</div>
					<div v-else-if="tab === 'notifications'" class="ts-col-1-2">
						<div class="ts-tab-heading no-top-space">
							<h1>Notifications</h1>
						</div>
						<div class="ts-row wrap-row">
							<?php \Voxel\Form_Models\Select_Model::render( [
								'v-model' => 'config.notifications.admin_user',
								'label' => 'Admin user',
								'description' => 'This user will receive all notifications that are set to "Notify admin"',
								'choices' => array_column( array_map( function( $wp_user ) {
									return [
										'id' => $wp_user->ID,
										'login' => $wp_user->user_login,
									];
								}, get_users( [ 'role' => 'administrator' ] ) ), 'login', 'id'),
							] ) ?>

							<?php \Voxel\Form_Models\Number_Model::render( [
								'v-model' => 'config.notifications.inapp_persist_days',
								'label' => 'Keep in-app notifications in the database for up to (days)',
							] ) ?>
						</div>
					</div>
					<div v-else-if="tab === 'dms'" class="ts-col-1-2">
						<div class="ts-tab-heading no-top-space">
							<h1>Direct Messages</h1>
						</div>
						<div class="ts-row wrap-row">
							<?php \Voxel\Form_Models\Number_Model::render( [
								'v-model' => 'config.messages.maxlength',
								'label' => 'Maximum message length (in characters)',
							] ) ?>

							<div class="ts-tab-subheading ts-col-1-1">
								<h3>Uploads</h3>
							</div>

							<?php \Voxel\Form_Models\Switcher_Model::render( [
								'v-model' => 'config.messages.files.enabled',
								'label' => 'Enable file uploads',
							] ) ?>

							<template v-if="config.messages.files.enabled">
								<?php \Voxel\Form_Models\Number_Model::render( [
									'v-model' => 'config.messages.files.max_size',
									'label' => 'Max file size (kB)',
									'width' => '1/2',
								] ) ?>

								<?php \Voxel\Form_Models\Number_Model::render( [
									'v-model' => 'config.messages.files.max_count',
									'label' => 'Max file count',
									'width' => '1/2',
								] ) ?>

								<?php \Voxel\Form_Models\Checkboxes_Model::render( [
									'v-model' => 'config.messages.files.allowed_file_types',
									'label' => 'Allowed file types',
									'choices' => array_combine( get_allowed_mime_types(), get_allowed_mime_types() ),
								] ) ?>
							</template>

							<div class="ts-tab-subheading ts-col-1-1">
								<h3>Real-time</h3>
							</div>

							<?php \Voxel\Form_Models\Switcher_Model::render( [
								'v-model' => 'config.messages.enable_real_time',
								'label' => 'Update chats in real-time',
							] ) ?>

							<?php \Voxel\Form_Models\Switcher_Model::render( [
								'v-model' => 'config.messages.enable_seen',
								'label' => 'Show "Seen" badge',
							] ) ?>

							<div class="ts-tab-subheading ts-col-1-1">
								<h3>Storage</h3>
							</div>

							<?php \Voxel\Form_Models\Number_Model::render( [
								'v-model' => 'config.messages.persist_days',
								'label' => 'Delete messages older than (days)',
							] ) ?>
						</div>
					</div>
					<div v-else-if="tab === 'emails'" class="ts-col-1-2">
						<div class="ts-tab-heading no-top-space">
							<h1>Emails</h1>
							<p>Configure how emails are received by your customers.</p>
						</div>
						<div class="ts-row wrap-row">
							<div class="ts-form-group ts-col-1-1">
								<label>Sender name</label>
								<input type="text" v-model="config.emails.from_name" placeholder="WordPress">
							</div>
							<div class="ts-form-group ts-col-1-1">
								<label>Sender email</label>
								<input type="email" v-model="config.emails.from_email" placeholder="<?= esc_attr( \Voxel\get_default_from_email() ) ?>">
							</div>
							<div class="ts-form-group ts-col-1-1">
								<label>Email footer text</label>
								<textarea
									v-model="config.emails.footer_text"
									readonly
									placeholder="<?= esc_attr( \Voxel\get_default_email_footer_text() ) ?>"
									@click.prevent="editFooterText"
									style="height: 120px;"
								></textarea>
							</div>
						</div>
					</div>
					<div v-else-if="tab === 'nav_menus'" class="ts-col-1-2">
						<div class="ts-tab-heading no-top-space">
							<h1>Nav menus</h1>
							<p>Manage custom menu locations.</p>
						</div>

						<template v-if="config.nav_menus.custom_locations.length">
							<div v-for="location, location_index in config.nav_menus.custom_locations" class="ts-row flex-width">
								<div class="ts-form-group ts-col-2-5">
									<label>Key</label>
									<input type="text" v-model="location.key" :disabled="!location.is_new">
								</div>
								<div class="ts-form-group ts-col-2-5">
									<label>Label</label>
									<input type="text" v-model="location.label">
								</div>

								<div class="ts-form-group ts-col-1-5 small-col">
									<ul class="basic-ul">
										<a href="#" @click.prevent="removeMenuLocation(location_index)" class="ts-button ts-faded icon-only">
											<i class="lar la-trash-alt icon-sm"></i>
										</a>
									</ul>
									<!-- <a href="#" @click.prevent="removeMenuLocation(location_index)" class="ts-button ts-transparent">
										<i class="lar la-trash-alt icon-sm"></i> Delete
									</a> -->
								</div>
							</div>
						</template>
						<template v-else>
							<p>No custom menu locations added yet.</p>
						</template>

						<div class="ts-row wrap-row">
							<div class="ts-form-group ts-col-1-1">
								<a href="#" @click.prevent="config.nav_menus.custom_locations.push( { key: '', label: '', is_new: true } )" class="ts-button ts-faded">
									<i class="las la-plus icon-sm"></i> Add custom location
								</a>
							</div>
						</div>
					</div>
					<div v-else-if="tab === 'db'" class="ts-col-1-2">
						<div class="ts-tab-heading no-top-space">
							<h1>Database</h1>
						</div>
						<div class="ts-row wrap-row">
							<?php \Voxel\Form_Models\Select_Model::render( [
								'v-model' => 'config.db.type',
								'label' => 'Database type',
								'choices' => [
									'mysql' => 'MySQL',
									'mariadb' => 'MariaDB',
								],
							] ) ?>

							<?php \Voxel\Form_Models\Number_Model::render( [
								'v-model' => 'config.db.max_revisions',
								'label' => 'Max revision count (per post)',
							] ) ?>
						</div>
					</div>
					<div v-else-if="tab === 'other'" class="ts-col-1-2">
						<div class="ts-tab-heading no-top-space">
							<h1>Other</h1>
						</div>
						<div class="ts-row wrap-row">
							<?php \Voxel\Form_Models\Switcher_Model::render( [
								'v-model' => 'config.icons.line_awesome.enabled',
								'label' => 'Enable "Line Awesome" icon pack',
							] ) ?>
						</div>
					</div>
					<!-- <div class="ts-col-1-1">
						<pre debug>{{ config }}</pre>
					</div> -->
				</div>

				<div class="ts-tab-content ts-container">
					<div class="ts-row wrap-row h-center">
						
					</div>
				</div>
			</div>
		</form>
	</div>
</div>

<?php require_once locate_template( 'templates/backend/product-types/components/rate-list-component.php' ) ?>
