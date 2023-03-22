<script type="text/html" id="dtags-visibility-editor">
	<div class="field-conditions modal-output-fields min-scroll">
		<div v-for="group, group_key in conditions" class="condition-group">
			<div class="cg-head ts-form-group">
				<h3>Rule group</h3>
			</div>
			
			<div v-for="condition, condition_key in group" class="single-condition ts-row">
				<div class="ts-form-group ts-col-1-1">
					<label>Condition</label>
					<a
						href="#"
						@click.prevent="activePopup = ( activePopup === condition ? null : condition )"
						class="ts-button ts-outline"
						:class="{'con-active': activePopup === condition}"
					>
						<div v-if="$root.rules[ condition.type ]">
							<span class="dtag">
								<span class="dtag-content">
									{{ $root.rules[ condition.type ].label }}
								</span>
							</span>
						</div>
						<span v-else>Choose condition</span>
					</a>
					<div v-if="activePopup === condition">
						<teleport to="#visibility-sidebar">
							<div class="ts-visibility-source">
								<div class="field-options-control">
									<a href="#" class="ts-button ts-faded ts-btn-small icon-only" @click.prevent="condition.type = null; activePopup = null;">
										<i class="las la-trash-alt icon-sm"></i>
									</a>
									<a href="#" class="ts-button btn-shadow ts-btn-small icon-only" @click.prevent="activePopup = null;">
										<i class="las la-check icon-sm"></i>
									</a>
								</div>
								<template v-for="rule in $root.rules">
									<a
										href="#"
										@click.prevent="condition.type = rule.type; setProps(condition); activePopup = null;"
										class="ts-button ts-faded"
									>{{ rule.label }}</a>
								</template>
								<!-- <div>
									<a href="#" @click.prevent="activePopup = null;" class="ts-button">Save</a>
									<a href="#" @click.prevent="condition.type = null; activePopup = null;" class="ts-button ts-transparent">Clear</a>
								</div> -->
							</div>
						</teleport>
					</div>
				</div>

				<?php foreach ( $visibility_rules as $rule ): ?>
					<template v-if="condition.type === <?= esc_attr( wp_json_encode( $rule->get_type() ) ) ?>">
						<?php $rule->render_settings() ?>
					</template>
				<?php endforeach ?>

				<div class="ts-form-group ts-col-1-4 delete-condition">
					<ul class="basic-ul">
						<a
						href="#"
						@click.prevent="removeCondition( condition_key, group, group_key )"
						class="ts-button ts-faded icon-only"
						>
							<i class="lar la-trash-alt icon-sm"></i>
						
						</a>
					</ul>
					
				</div>
			</div>
			<div class="ts-row">
				<div class="ts-form-group rule-footer ts-col-1-1">
					<ul class="basic-ul">
						<li>
							<a
								href="#"
								@click.prevent="group.push( { type: '' } )"
								class="add-condition ts-button ts-faded"
							>
								<i class="las la-code-branch icon-sm"></i>
								Add condition
							</a>
						</li>
					</ul>
					
				</div>
			</div>
		</div>

		<div class="ts-row">
			<div class="ts-form-group rule-footer ts-col-1-1">
				<ul class="basic-ul">
					<li>
						<a href="#" @click.prevent="addRuleGroup" class="ts-button ts-faded">
							<i class="las la-layer-group icon-sm"></i> Add rule group
						</a>
					</li>
				</ul>

			</div>
		</div>

		<!-- <div class="ts-form-group ts-col-1-1">
			<pre debug>{{ conditions }}</pre>
		</div> -->
	</div>

	<div class="bordered-columns field-select-section min-scroll">

		<div id="visibility-sidebar"></div>
		<div class="nothing-to-show">
			<i class="las la-cog"></i>
			<p>Nothing to show</p>
		</div>
	</div>
</script>
