<div id="vx-edit-plan" v-cloak data-config="<?= esc_attr( wp_json_encode( $config ) ) ?>">
	<form method="POST" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ) ?>" @submit.prevent>
		<div class="edit-cpt-header">
			<div class="ts-container cpt-header-container">
				<div class="ts-row wrap-row v-center">
					<div class="ts-col-2-3 v-center">
						<h1><?= $plan->get_label() ?>
							<p>You are editing <?= $plan->get_label() ?> membership plan</p>
						</h1>
					</div>
					<div class="cpt-header-buttons ts-col-1-3">
						<a href="#" @click.prevent="save" class="ts-button ts-save-settings btn-shadow">
							<i class="las la-plus icon-sm"></i>
							Save changes
						</a>
					</div>
				</div>
				<span class="ts-separator"></span>
			</div>
		</div>

		<div class="ts-theme-options ts-container">
			<div class="ts-row ts-theme-options-nav">
				<div class="ts-nav ts-col-1-1">
					<div class="ts-nav-item" :class="{'current-item': tab === 'general'}">
						<a href="#" @click.prevent="tab = 'general'; subtab = 'general';">
							<span class="item-icon all-center">
								<i class="las la-home"></i>
							</span>
							<span class="item-name">
								General
							</span>
						</a>
					</div>
					<div class="ts-nav-item" :class="{'current-item': tab === 'submissions'}">
						<a href="#" @click.prevent="tab = 'submissions'">
							<span class="item-icon all-center">
								<i class="las la-grip-lines"></i>
							</span>
							<span class="item-name">
								Post submissions
							</span>
						</a>
					</div>
					<div class="ts-nav-item" :class="{'current-item': tab === 'pricing' && mode === 'live', 'vx-disabled': plan.key === 'default'}">
						<a href="#" @click.prevent="tab = 'pricing'; mode = 'live';">
							<span class="item-icon all-center">
								<i class="las la-dollar-sign"></i>
							</span>
							<span class="item-name">
								Pricing
							</span>
						</a>
					</div>
					<div class="ts-nav-item" :class="{'current-item': tab === 'pricing' && mode === 'test', 'vx-disabled': plan.key === 'default'}">
						<a href="#" @click.prevent="tab = 'pricing'; mode = 'test';">
							<span class="item-icon all-center">
								<i class="las la-dollar-sign"></i>
							</span>
							<span class="item-name">
								Pricing <code style="font-size: 11px; border: 1px solid #31363b; border-radius: 3px;">TEST MODE</code>
							</span>
						</a>
					</div>
				</div>
			</div>

			<div v-if="tab === 'general'" class="ts-tab-content ts-container">
				<div class="ts-row wrap-row h-center">
					<div class="ts-col-1-2">
						<div class="ts-tab-heading">
							<h1>General</h1>
							<p>General plan details</p>
						</div>

						<div class="inner-tab">
							<div class="ts-row wrap-row">
								<?php \Voxel\Form_Models\Key_Model::render( [
									'v-model' => 'plan.key',
									'label' => 'Key',
									'editable' => false,
								] ) ?>

								<?php \Voxel\Form_Models\Text_Model::render( [
									'v-model' => 'plan.label',
									'label' => 'Label',
								] ) ?>

								<?php \Voxel\Form_Models\Textarea_Model::render( [
									'v-model' => 'plan.description',
									'label' => 'Description',
								] ) ?>

								<div v-show="plan.key !== 'default'" class="ts-col-1-1">
									<a href="#" @click.prevent="showArchive = !showArchive" class="ts-button ts-transparent ts-btn-small mb10">
										<i class="las la-arrow-down icon-sm"></i>
										Advanced
									</a>

									<template v-if="showArchive">
										<div v-if="plan.archived" class="ts-form-group ts-col-1-1">
											<p>Make this membership plan available to new users again.</p><br>
											<div class="basic-ul">
												<a href="#" class="ts-button ts-faded" @click.prevent="archivePlan">
													<i class="las la-box icon-sm"></i>
													Unarchive plan
												</a>
											</div>
										</div>
										<div v-else class="ts-form-group ts-col-1-1">
											<p>Archiving a membership plan will make it unavailable to new users. Users already on this plan will not be affected. Archived plans can be unarchived again.</p><br>
											<div class="basic-ul">
												<a href="#" class="ts-button ts-faded" @click.prevent="archivePlan">
													<i class="las la-box icon-sm"></i>
													Archive this plan
												</a>
											</div>
										</div>
										<div v-if="plan.archived" class="ts-form-group ts-col-1-1">
											<br><p>Delete this plan permanently. Users already on this plan will be assigned the default plan. This action cannot be undone.</p><br>
											<div class="basic-ul">
												<a href="#" class="ts-button ts-faded" @click.prevent="deletePlan">
													<i class="las la-trash icon-sm"></i>
													Delete plan permanently
												</a>
											</div>
										</div>
									</template>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
			<div v-else-if="tab === 'submissions'" class="ts-tab-content ts-container">
				<div class="ts-row wrap-row h-center">
					<div class="ts-col-1-2">
						<div class="ts-tab-heading">
							<h1>Post submission limits</h1>
							<p>Set the post limit (by post type) that users with this plan can create.</p>
						</div>

						<div v-for="postTypeLimit, postType in plan.submissions" class="ts-row wrap-row">
							<div class="ts-form-group ts-col-3-4">
								<label><strong>{{ $root.postTypes[ postType ] }}</strong> Limit</label>
								<input type="number" v-model="plan.submissions[postType]">
							</div>
							<div class="ts-form-group ts-col-1-4">
								<a class="ts-button ts-faded icon-only" href="#" @click.prevent="delete plan.submissions[ postType ]">Delete</a>
							</div>
						</div>

						<div class="ts-row wrap-row">
							<div class="ts-form-group ts-col-3-4">
								<label>Choose post type</label>
								<select v-model="submissionValue">
									<template v-for="label, postType in $root.postTypes">
										<option v-if="!plan.submissions[postType]" :value="postType">{{ label }}</option>
									</template>
								</select>
							</div>
							<div class="ts-form-group ts-col-1-4">
								<a href="#" @click.prevent="addSubmission" class="ts-button ts-faded">Add</a>
							</div>
						</div>
					</div>
				</div>
			</div>
			<div v-else-if="tab === 'pricing'" class="ts-tab-content ts-container">
				<div v-if="!priceSetup[mode]" class="ts-row wrap-row h-center">
					<div class="ts-col-1-2" style="margin-top: 100px;">
						<div v-if="priceSetup[mode] === null" class="ts-form-group text-center">
							<p>Loading...</p>
						</div>
						<div v-else class="ts-form-group">
							<p><strong>Stripe error</strong></p>
							<p>{{ priceSetup[mode+'Error'] }}</p>
							<div class="basic-ul" style="margin-top: 20px;">
								<a href="<?= esc_url( admin_url( 'admin.php?page=voxel-settings&tab=stripe' ) ) ?>" class="ts-button ts-faded ts-btn-small">
									<i class="las la-external-link-alt icon-sm"></i> Configure Stripe
								</a>
							</div>
						</div>
					</div>
				</div>
				<div v-else class="ts-row wrap-row h-center">
					<div class="ts-col-1-2">
						<div class="ts-tab-heading">
							<h1>Pricing</h1>
							<p>Plan pricing settings</p>
						</div>

						<div class="ts-row wrap-row">
							<div v-if="plan.pricing[mode].prices.length" class="field-container ts-col-1-1">
								<div v-for="price in plan.pricing[mode].prices" class="single-field wide" :class="{open: price === activePrice}">
									<div class="field-head" @click.prevent="activePrice = price === activePrice ? null : price">
										<p class="field-name">{{ price.currency.toUpperCase() }} {{ ( price.is_zero_decimal ? price.amount : price.amount / 100 ).toLocaleString() }}</p>
										<span v-if="price.type === 'recurring'" class="field-type">every {{ price.recurring.interval_count }} {{ price.recurring.interval }}s</span>
										<span v-else class="field-type">one time</span>
										<div class="field-actions left-actions">
											<span v-if="!price.active" class="field-action all-center">
												<a href="#" @click.prevent title="This price has been disabled"><i class="las la-minus-circle"></i></a>
											</span>
											<span class="field-action all-center">
												<a href="#" @click.prevent><i class="las la-angle-down"></i></a>
											</span>
										</div>
									</div>
									<div class="field-body" v-if="price === activePrice">
										<div class="ts-row wrap-row">
											<div class="ts-form-group ts-col-1-1">
												<label>Price ID</label>
												<input type="text" :value="price.id" readonly>
											</div>
											<div class="ts-form-group ts-col-1-1">
												<label>Tax behavior</label>
												<input type="text" :value="price.tax_behavior" readonly>
											</div>
											<div class="ts-form-group ts-col-1-1">
												<div class="basic-ul">
													<a class="ts-button ts-faded" :href="stripePriceUrl( price.id )" target="_blank">
														<i class="las la-external-link-alt icon-sm"></i>
														View in Stripe
													</a>
													<a class="ts-button ts-faded" href="#" @click.prevent="togglePrice( price.id )">
														<i class="las icon-sm" :class="price.active ? 'la-minus-circle' : 'la-plus-circle'"></i>
														{{ price.active ? 'Disable price' : 'Enable price' }}
													</a>
												</div>
											</div>
										</div>
									</div>
								</div>
							</div>

							<div class="ts-col-1-1 single-field ts-create-price">
								<div class="ts-row wrap-row">
									<div class="ts-form-group ts-col-1-1">
										<h2 class="mt0">Create price</h2>
									</div>

									<?php \Voxel\Form_Models\Number_Model::render( [
										'v-model' => 'createPrice[mode].amount',
										'label' => 'Amount',
										'width' => '2/3',
									] ) ?>

									<?php \Voxel\Form_Models\Select_Model::render( [
										'v-model' => 'createPrice[mode].currency',
										'label' => 'Currency',
										'choices' => \Voxel\Stripe\Currencies::all(),
										'width' => '1/3',
									] ) ?>

									<?php \Voxel\Form_Models\Radio_Buttons_Model::render( [
										'v-model' => 'createPrice[mode].type',
										'label' => 'Type',
										'columns' => 'two',
										'choices' => [
											'recurring' => 'Recurring',
											'one_time' => 'One time',
										],
									] ) ?>

									<template v-if="createPrice[mode].type === 'recurring'">
										<div class="ts-form-group ts-col-1-1">
											<span>Billing period</span>
										</div>

										<?php \Voxel\Form_Models\Number_Model::render( [
											'v-model' => 'createPrice[mode].intervalCount',
											'label' => 'Every',
											'width' => '1/3',
										] ) ?>

										<?php \Voxel\Form_Models\Select_Model::render( [
											'v-model' => 'createPrice[mode].interval',
											'label' => 'Unit',
											'width' => '2/3',
											'choices' => [
												'day' => 'Day(s)',
												'week' => 'Week(s)',
												'month' => 'Month(s)',
											],
										] ) ?>
									</template>

									<?php \Voxel\Form_Models\Switcher_Model::render( [
										'v-model' => 'createPrice[mode].includeTax',
										'label' => 'Include tax in price',
									] ) ?>

									<div class="ts-col-1-1">
										<a href="#" @click.prevent="insertPrice" class="ts-button">Create</a>
									</div>
								</div>
							</div>

							<div class="ts-col-1-1" style="margin-top: 50px;">
								<a href="#" @click.prevent="showPriceAdvanced = !showPriceAdvanced" class="ts-button ts-transparent ts-btn-small">
									<i class="las la-arrow-down icon-sm"></i>
									Stripe details
								</a>
							</div>
							<div v-if="showPriceAdvanced" class="ts-col-1-1">
								<div class="ts-row wrap-row">
									<div class="ts-form-group ts-col-1-1">
										<label>Product ID</label>
										<input type="text" :value="plan.pricing[mode].product_id" readonly>
									</div>
									<div class="ts-form-group ts-col-1-1">
										<div class="basic-ul">
											<a class="ts-button ts-faded" :href="stripeProductUrl()" target="_blank">
												<i class="las la-external-link-alt icon-sm"></i>
												View in Stripe
											</a>
											<a class="ts-button ts-faded" href="#" @click.prevent="syncPrices">
												<i class="las la-sync icon-sm"></i>
												Sync all prices with Stripe
											</a>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</form>
</div>

<script type="text/html" id="membership-edit-plan">
	<div>
		<div class="inner-tab ts-row wrap-row">
			<div class="ts-col-1-2">
				<div v-if="tab === 'more'" :class="{'vx-disabled': loading}">
					<div class="ts-row wrap-row">
						<div v-if="plan.archived" class="ts-form-group ts-col-1-1">
							<a href="#" class="ts-button ts-faded" @click.prevent="archivePlan">Unarchive plan</a>
						</div>
						<div v-else class="ts-form-group ts-col-1-1">
							<a href="#" class="ts-button ts-faded" @click.prevent="archivePlan">Archive this plan</a>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</script>
