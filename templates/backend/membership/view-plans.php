<?php
/**
 * Admin membership settings.
 *
 * @since 1.0
 */

if ( ! defined('ABSPATH') ) {
	exit;
} ?>

<?php $displayPlan = function( $plan ) { ?>
	<div class="ts-col-1-4">
		<div class="post-type-card">
			<i class="las la-user-circle"></i>
			<h3><?= $plan->get_label() ?></h3>
			<ul>
				<li>Key: <?= $plan->get_key() ?></li>
				<?php if ( $live_price_count = count( $plan->get_pricing()['live']['prices'] ?? [] ) ): ?>
					<li><?= $live_price_count ?> live prices</li>
				<?php else: ?>
					<li>No live prices created.</li>
				<?php endif ?>

				<?php if ( $test_price_count = count( $plan->get_pricing()['test']['prices'] ?? [] ) ): ?>
					<li><?= $test_price_count ?> test prices</li>
				<?php else: ?>
					<li>No test prices created.</li>
				<?php endif ?>
			</ul>
			<a href="<?= esc_url( $plan->get_edit_link() ) ?>" class="ts-button ts-faded edit-voxel">
				Edit plan <img src="<?php echo esc_url( \Voxel\get_image('post-types/logo.svg') ) ?>">
			</a>
		</div>
	</div>
<?php } ?>

<div class="edit-cpt-header">
	<div class="ts-container cpt-header-container">
		<div class="ts-row wrap-row v-center">
			<div class="ts-col-2-3 v-center">
				<h1>Pricing plans
					<p>Membership plans you created.</p>
				</h1>
			</div>
			<div class="cpt-header-buttons ts-col-1-3">
				<a href="<?= esc_url( $add_plan_url ) ?>" class="ts-button ts-save-settings btn-shadow">
					<i class="las la-plus icon-sm"></i>
					Create plan
				</a>
			</div>
		</div>
		<span class="ts-separator"></span>
	</div>
</div>
<div class="ts-theme-options ts-container">
	<div class="ts-row wrap-row">
		<?php array_map( $displayPlan, $active_plans ) ?>
	</div>

	<?php if ( ! empty( $archived_plans ) ): ?>
		<div class="ts-row wrap-row" style="margin-top: 70px;">
			<div class="ts-col-1-1">
				<a href="#" class="ts-button ts-transparent ts-btn-small" onclick="event.preventDefault(); document.getElementById('vx-archived-plans').classList.toggle('hide')">
					<i class="las la-arrow-down icon-sm"></i>
					Show archived plans
				</a>
			</div>
		</div>
		<div class="ts-row wrap-row hide" id="vx-archived-plans">
			<?php array_map( $displayPlan, $archived_plans ) ?>
		</div>
	<?php endif ?>
</div>
