<?php
/**
 * Create post widget template.
 *
 * @since 1.0
 */
if ( ! defined('ABSPATH') ) {
	exit;
}

$deferred_templates = [];
$deferred_templates[] = locate_template( 'templates/widgets/create-post/_media-popup.php' );
?>

<script type="text/json" class="vxconfig"><?= wp_specialchars_decode( wp_json_encode( $config ) ) ?></script>
<div
	class="ts-form ts-create-post create-post-form ts-hidden"
>
	<transition name="fade">
		<template v-if="submission.done">
			<div class="ts-edit-success flexify">
				<?= \Voxel\get_icon_markup( $this->get_settings_for_display('success_icon') ) ?: \Voxel\svg( 'checkmark-circle.svg' ) ?>
				<h4>{{ submission.message }}</h4>
				<!-- <p>{{ submission.message }}</p> -->
				<div class="es-buttons flexify">
					<a v-if="submission.status === 'publish'" :href="submission.viewLink" class="ts-btn ts-btn-2 ts-btn-large create-btn">
						<?= \Voxel\get_icon_markup( $this->get_settings_for_display('view_icon') ) ?: \Voxel\svg( 'eye.svg' ) ?>
						<template v-if="post_type.key === 'profile'">
							<?= _x( 'View your profile', 'create post', 'voxel' ) ?>
						</template>
						<template v-else>
							<?= _x( 'View your post', 'create post', 'voxel' ) ?>
						</template>
					</a>
					<!-- <a v-if="!post" href="#" class="ts-btn ts-btn-1 ts-btn-large">
						<i aria-hidden="true" class="las la-share"></i>
						Share to timeline
					</a> -->
					<a :href="submission.editLink" class="ts-btn ts-btn-1 ts-btn-large create-btn">
						<?= \Voxel\get_icon_markup( $this->get_settings_for_display('prev_icon') ) ?: \Voxel\svg( 'chevron-left.svg' ) ?>
						<?= _x( 'Back to editing', 'create post', 'voxel' ) ?>
					</a>
				</div>
			</div>
		</template>
	</transition>
	<template v-if="!submission.done">
		<div class="ts-form-progres">
			<ul class="step-percentage simplify-ul flexify">
				<template v-for="step_key, index in activeSteps">
					<li :class="{'step-done': step_index >= index}"></li>
				</template>
			</ul>
			<div class="ts-active-step flexify">
				<div class="active-step-details">
					<p>{{ currentStep.label }}</p>
				</div>
				<div v-if="activeSteps.length > 1" class="step-nav flexify">
					<a href="#" @click.prevent="prevStep" class="ts-icon-btn" :class="{'disabled': step_index === 0}">
						<?= \Voxel\get_icon_markup( $this->get_settings_for_display('prev_icon') ) ?: \Voxel\svg( 'chevron-left.svg' ) ?>
					</a>
					<a href="#" @click.prevent="nextStep" class="ts-icon-btn" :class="{'disabled': step_index === (activeSteps.length - 1)}">
						<?= \Voxel\get_icon_markup( $this->get_settings_for_display('next_icon') ) ?: \Voxel\svg( 'chevron-right.svg' ) ?>
					</a>
				</div>
			</div>
		</div>

		<div class="create-form-step form-field-grid">
			<?php
			$hidden_steps = [];
			foreach ( $post_type->get_fields() as $field ):
				try {
					$field->check_dependencies();
				} catch ( \Exception $e ) {
					continue;
				}

				if ( isset( $hidden_steps[ $field->get_step() ] ) || ! $field->passes_visibility_rules() ) {
					if ( $field->get_type() === 'ui-step' ) {
						$hidden_steps[ $field->get_key() ] = true;
					}

					continue;
				}

				if ( $field->get_type() === 'ui-step' ) {
					continue;
				}

				if ( $field_template = locate_template( sprintf( 'templates/widgets/create-post/%s-field.php', $field->get_type() ) ) ) {
					$deferred_templates[] = $field_template;
				}

				if ( $field->get_type() === 'repeater' ) {
					$deferred_templates = array_merge( $deferred_templates, $field->get_field_templates() );
				}

				$field_object = sprintf( '$root.fields[%s]', esc_attr( wp_json_encode( $field->get_key() ) ) );
				?>

				<field-<?= $field->get_type() ?>
					:field="<?= $field_object ?>"
					v-if="conditionsPass( <?= $field_object ?> )"
					:style="<?= $field_object ?>.step === currentStep.key ? '' : 'display: none;'"
					ref="field:<?= esc_attr( $field->get_key() ) ?>"
					:class="'field-key-'+<?= $field_object ?>.key"
				></field-<?= $field->get_type() ?>>
				<?php
			endforeach; ?>

		</div>

		<div class="ts-form-footer flexify">
			<ul v-if="activeSteps.length > 1" class="ts-nextprev simplify-ul flexify">
				<li>
					<a :class="{'disabled': step_index === 0}" href="#" @click.prevent="prevStep" class="ts-prev">
						<div><?= \Voxel\get_icon_markup( $this->get_settings_for_display('prev_icon') ) ?: \Voxel\svg( 'chevron-left.svg' ) ?></div>
						<span><?= _x( 'Previous step', 'create post', 'voxel' ) ?></span>
					</a>
				</li>
				<li>
					<a :class="{'disabled': step_index === (activeSteps.length - 1)}" href="#" @click.prevent="$event.shiftKey ? submit() : nextStep()" class="ts-next">
						<span><?= _x( 'Next step', 'create post', 'voxel' ) ?></span>
						<div><?= \Voxel\get_icon_markup( $this->get_settings_for_display('next_icon') ) ?: \Voxel\svg( 'chevron-right.svg' ) ?></div>
					</a>
				</li>
			</ul>

			<!-- only when submitting  -->
			<a
				v-if="!post && step_index === (activeSteps.length - 1)"
				href="#"
				@click.prevent="submit"
				class="ts-btn ts-btn-2 create-btn ts-btn-large ts-save-changes"
				:class="{'vx-pending': submission.processing}"
			>
				<template v-if="submission.processing">
					<span class="ts-loader"></span>
					<?= _x( 'Please wait', 'create post', 'voxel' ) ?>
				</template>
				<template v-else>
					<?= _x( 'Publish', 'create post', 'voxel' ) ?>
					<?= \Voxel\get_icon_markup( $this->get_settings_for_display('publish_icon') ) ?: \Voxel\svg( 'arrow-right-circle.svg' ) ?>
				</template>
			</a>

			<!-- only when editing -->
			<a v-if="post" href="#" @click.prevent="submit" class="ts-btn ts-btn-2 create-btn ts-btn-large ts-save-changes" :class="{'vx-pending': submission.processing}">
				<template v-if="submission.processing">
					<span class="ts-loader"></span>
					<?= _x( 'Please wait', 'create post', 'voxel' ) ?>
				</template>
				<template v-else>
					<?= \Voxel\get_icon_markup( $this->get_settings_for_display('save_icon') ) ?: \Voxel\svg( 'save.svg' ) ?>
					<?= _x( 'Save changes', 'create post', 'voxel' ) ?>
				</template>
			</a>
		</div>
	</template>
</div>

<?php foreach ( $deferred_templates as $template_path ): ?>
	<?php require_once $template_path ?>
<?php endforeach ?>
