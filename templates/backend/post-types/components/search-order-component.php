<?php
/**
 * Search filters - component template.
 *
 * @since 1.0
 */
if ( ! defined('ABSPATH') ) {
	exit;
} ?>
<script type="text/html" id="post-type-search-order-template">
    
    <div class="used-fields">
        <div class="sub-heading">
            <p>Ordering options</p>
        </div>
        <div class="field-container" ref="fields-container">
            <draggable v-model="$root.config.search.order" group="order" handle=".field-head" item-key="key" @start="dragStart" @end="dragEnd">
                <template #item="{element: order}">
                    <div :class="{open: isActive(order)}" class="single-field wide">
                        <div class="field-head" @click="toggleActive(order)">

                            <p class="field-name">{{ order.label }}</p>
                            <span class="field-type">{{ order.key }}</span>
                            <div class="field-actions">
	                            <span class="field-action all-center">
	                                <a href="#" @click.prevent="deleteOrderingOption(order)">
	                                   <i class="lar la-trash-alt icon-sm"></i>
	                                </a>
	                            </span>
	                        </div>
                        </div>
                        <div v-if="isActive(order)" class="field-body">
                            <div class="ts-row wrap-row">
								<?php \Voxel\Form_Models\Text_Model::render( [
									'v-model' => 'order.label',
									'label' => 'Label',
									'width' => '1/1',
								] ) ?>

								<?php \Voxel\Form_Models\Text_Model::render( [
									'v-model' => 'order.placeholder',
									'label' => 'Placeholder',
									'width' => '1/1',
								] ) ?>

								<?php \Voxel\Form_Models\Key_Model::render( [
									'v-model' => 'order.key',
									'label' => 'Form Key',
									'description' => 'Enter a unique form key',
									'width' => '1/1',
									'classes' => 'field-key-wrapper',
								] ) ?>

								<?php \Voxel\Form_Models\Icon_Model::render( [
									'v-model' => 'order.icon',
									'label' => 'Icon',
									'width' => '1/1',
								] ) ?>

					            <draggable v-if="order.clauses.length" v-model="order.clauses" group="clauses" handle=".field-head" item-key="key" @start="dragStart" @end="dragEnd" class="ts-col-1-1">
					                <template #item="{element: clause}">
					                    <div :class="{open: activeClause === clause}" class="single-field wide">
					                        <div class="field-head" @click="activeClause = (activeClause === clause) ? null : clause">

					                            <p class="field-name">{{ clause.type }}</p>
					                            <span class="field-type">{{ clause.type }}</span>
	                            				<div class="field-actions">
						                            <span class="field-action all-center">
						                                <a href="#" @click.prevent="deleteClause(clause, order)">
						                                  <i class="lar la-trash-alt icon-sm"></i>
						                                </a>
						                            </span>
						                        </div>
					                        </div>
					                        <div v-if="activeClause === clause" class="field-body">
					                            <div class="ts-row wrap-row">
													<?= $orderby_options_markup ?>
												</div>
											</div>
										</div>
									</template>
								</draggable>

					            <div class="field-container available-fields ts-col-1-1">
					            	<div class="ts-col-1-1">
						                <label v-if="order.clauses.length === 0">Order by:</label>
						                <label v-if="order.clauses.length === 1">Add secondary clause:</label>
						                <label v-if="order.clauses.length >= 2">
						                	Add another clause
						                </label>
						            </div>
					                <div class="ts-col-1-1">
					                    <a
					                    	v-for="clause in $root.options.orderby_types"
					                    	@click.prevent="addClause(clause, order)"
					                    	href="#"
					                    	class="ts-button ts-faded ts-btn-small"
					                    	style="margin-right: 10px; margin-bottom: 10px;"
					                    >
					                        {{ getClauseLabel( clause ) }}
					                    </a>
					                </div>
					            </div>
                            </div>
                        </div>
                    </div>
                </template>
            </draggable>
        </div>
    </div>
    <div class="ts-relative">
        <div class="available-fields-container">
            <div class="sub-heading">
                <p>Presets</p>
            </div>
            <div class="field-container available-fields ts-row wrap-row">
                <div v-for="preset in $root.options.orderby_presets" class="single-field ts-col-1-2">
                    <div @click.prevent="$root.config.search.order.push(preset)" class="field-head">
                        <p class="field-name">{{ preset.label }}</p>
					    <div class="field-actions">
   							<span class="field-action all-center">
   								<a href="#">
   									<i class="las la-plus"></i>
   								</a>
   							</span>
   						</div>
                    </div>
                </div>

                <div class="text-center ts-col-1-1">
                	<a href="#" @click.prevent="addOrderingOption" class="ts-button ts-faded">Add custom order</a>
                </div>
            </div>
        </div>
    </div>
</script>