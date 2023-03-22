<?php
/**
 * Page templates - component template.
 *
 * @since 1.0
 */
if ( ! defined('ABSPATH') ) {
	exit;
} ?>
<script type="text/html" id="post-type-templates-template">
	<div class="ts-tab-content ts-container">
		<div class="ts-row wrap-row">
			<div class="ts-col-1-1">
				<div class="ts-tab-heading">
					<h1>Base templates</h1>
					<p>Design the base templates for this post type</p>
				</div>
				<div class="inner-tab ts-row wrap-row">
					<div class="ts-col-1-4">
						<div class="single-field tall">
							<a :href="editWithElementor($root.config.templates.single)" target="_blank" class="field-head">
								<img src="<?php echo esc_url( \Voxel\get_image('post-types/single.png') ) ?>" alt="" class="icon-sm">
								<p class="field-name">Single page</p>
								<span class="field-type">Edit with Elementor</span>
							</a>
						</div>
					</div>
					<div class="ts-col-1-4">
						<div class="single-field tall">
							<a :href="editWithElementor($root.config.templates.card)" target="_blank" class="field-head">
								<img src="<?php echo esc_url( \Voxel\get_image('post-types/pcard.png') ) ?>" alt="" class="icon-sm">
								<p class="field-name">Preview card</p>
								<span class="field-type">Edit with Elementor</span>
							</a>
						</div>
					</div>
					<div class="ts-col-1-4">
						<div class="single-field tall">
							<a :href="editWithElementor($root.config.templates.archive)" target="_blank" class="field-head">
								<img src="<?php echo esc_url( \Voxel\get_image('post-types/archive.png') ) ?>" alt="" class="icon-sm">
								<p class="field-name">Archive page</p>
								<span class="field-type">Edit with Elementor</span>
							</a>
						</div>
					</div>
					<div class="ts-col-1-4">
						<div class="single-field tall">
							<a :href="editWithElementor($root.config.templates.form)" target="_blank" class="field-head">
								<img src="<?php echo esc_url( \Voxel\get_image('post-types/submit.png') ) ?>" alt="" class="icon-sm">
								<p class="field-name">Submit page</p>
								<span class="field-type">Edit with Elementor</span>
							</a>
						</div>
					</div>

				</div>
			</div>

			<div class="ts-col-1-1">
				<div class="ts-tab-heading">
					<h1>Additional templates</h1>
					<p>Design additional templates that can used to override base templates in specific locations</p>
				</div>
				<ul class="inner-tabs">
					<li class="current-item"><a href="#">Preview card</a></li>
				</ul>
				<div class="inner-tab ts-row wrap-row">
					<div v-for="template in $root.config.custom_templates.card" class="ts-col-1-4">
						<div class="single-field tall">
							<a :href="editWithElementor(template.id)" target="_blank" class="field-head">
								<img src="<?php echo esc_url( \Voxel\get_image('post-types/pcard.png') ) ?>" alt="" class="icon-sm">
								<p class="field-name">Preview card: {{ template.label }}</p>
								<span class="field-type">Edit with Elementor</span>
							</a>
							<ul class="basic-ul">
								<li>
									<a href="#" @click.prevent="deleteTemplate(template, 'card')" target="_blank" class="ts-button ts-faded icon-only"><i class="las la-trash"></i></a>
								</li>
							</ul>
						</div>
					</div>

					<div class="ts-col-1-4 all-center ts-create-template">
						<a href="#" @click.prevent="createTemplate('card')">
							<i class="las la-plus icon-sm"></i>
							Create template
						</a>
					</div>
				</div>
				<br><br><br>
				<ul class="inner-tabs">
					<li class="current-item"><a href="#">Single page</a></li>
				</ul>
				<div class="inner-tab ts-row wrap-row">
					<div v-for="template in $root.config.custom_templates.single" class="ts-col-1-4">
						<div class="single-field tall">
							<a :href="editWithElementor(template.id)" target="_blank" class="field-head">
								<img src="<?php echo esc_url( \Voxel\get_image('post-types/pcard.png') ) ?>" alt="" class="icon-sm">
								<p class="field-name">Single page: {{ template.label }}</p>
								<span class="field-type">Edit with Elementor</span>
							</a>
							<ul class="basic-ul">
								<li>
									<a href="#" @click.prevent="deleteTemplate(template, 'single')" target="_blank" class="ts-button ts-faded icon-only"><i class="las la-trash"></i></a>
								</li>
							</ul>
						</div>
					</div>

					<div class="ts-col-1-4 all-center ts-create-template">
						<a href="#" @click.prevent="createTemplate('single')">
							<i class="las la-plus icon-sm"></i>
							Create template
						</a>
					</div>
				</div>
			</div>
		</div>
	</div>
</script>
