<?php
if ( ! defined('ABSPATH') ) {
	exit;
} ?>
<script type="text/html" id="product-type-rate-list-template">
	<div v-if="modelValue.length">
		<div v-for="rate in modelValue" class="single-field wide">
			<div class="field-head">
				<p class="field-name">{{ rate }}</p>
				<div class="field-actions">
					<a href="#" @click.prevent="remove(rate)" class="field-action all-center">
						<i class="las la-trash"></i>
					</a>
				</div>
			</div>
		</div>
	</div>
	<div v-else>
		<p>No tax rates added.</p>
	</div>

	<a href="#" @click.prevent="show">Manage tax rates</a>

	<teleport to="body">
		<div v-if="open" class="ts-field-modal ts-theme-options">
			<div class="modal-backdrop" @click="open = false"></div>
			<div class="modal-content min-scroll">
				<div class="modal-content-holder">
					<div class="field-modal-head">
						<h2>Select tax rates</h2>
						<a href="#" @click.prevent="open = false" class="ts-button btn-shadow">
							<i class="las la-check icon-sm"></i> Done
						</a>
					</div>

					<div class="field-modal-body">
						<div class="ts-row wrap-row">
							<div class="ts-form-group ts-col-1-1">
								<template v-if="rates === null">
									<p class="text-center">Loading...</p>
								</template>
								<template v-else-if="!(rates && rates.length)">
									<p class="text-center">No tax rates found.</p>
								</template>
								<template v-else>
									<div v-for="rate in rates" class="single-field wide">
										<div class="field-head" @click.prevent="toggle(rate)">
											<p class="field-name">{{ rate.display_name }}</p>
											<span class="field-type">{{ rate.id }}</span>
											<div class="field-actions" v-if="isSelected(rate)">
												<span class="field-action all-center">
													<i class="las la-check icon-sm"></i>
												</span>
											</div>
										</div>
									</div>

									<div class="ts-form-group ts-col-1-1 basic-ul" style="justify-content: space-between;">
										<a href="#" :class="{'vx-disabled':rates[0].id === first_item}" @click.prevent="prev" class="ts-button ts-faded">Prev</a>
										<a href="#" :class="{'vx-disabled':is_last_page}" @click.prevent="next" class="ts-button ts-faded">Next</a>
									</div>
								</template>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</teleport>
</script>