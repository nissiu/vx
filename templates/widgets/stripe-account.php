<?php
/**
 * Stripe Account widget template.
 *
 * @since 1.0
 */
if ( ! defined('ABSPATH') ) {
	exit;
} ?>
<div class="ts-panel">
	<div class="ac-head">
	   <?= \Voxel\get_icon_markup( $this->get_settings_for_display('ts_connect_ico') ) ?: \Voxel\svg( 'bag.svg' ) ?>
	   <p><?= _x( 'Stripe Connect', 'stripe vendor', 'voxel' ) ?></p>
	</div>
	<div class="ac-body">
		<?php if ( \Voxel\current_user()->has_cap('administrator') && apply_filters( 'voxel/admin-requires-vendor-onboarding', false ) !== true ) : ?>
			<p><?= _x( 'Stripe vendor onboarding is not necessary for admin accounts.', 'stripe vendor', 'voxel' ) ?></p>
		<?php else: ?>
			<?php if ( $account->charges_enabled ): ?>
				<p><?= _x( 'Your account is ready to accept payments.', 'stripe vendor', 'voxel' ) ?></p>
			<?php elseif ( $account->details_submitted ): ?>
				<p><?= _x( 'Your account is pending verification.', 'stripe vendor', 'voxel' ) ?></p>
			<?php else: ?>
				<p><?= _x( 'Setup your Stripe account in order to accept payments.', 'stripe vendor', 'voxel' ) ?></p>
			<?php endif ?>
			<div class="ac-bottom">
				<ul class="simplify-ul current-plan-btn">
					<?php if ( ! $account->exists ): ?>
						<li>
							<a href="<?= esc_url( $onboard_link ) ?>" class="ts-btn ts-btn-1 ts-btn-large">
								 <?= \Voxel\get_icon_markup( $this->get_settings_for_display('ts_setup_ico') ) ?: \Voxel\svg( 'plus.svg' ) ?>
								<?= _x( 'Start setup', 'stripe vendor', 'voxel' ) ?>
							</a>
						</li>
					<?php elseif ( ! $account->details_submitted ): ?>
						<li>
							<a href="<?= esc_url( $onboard_link ) ?>" class="ts-btn ts-btn-1 ts-btn-large">
								 <?= \Voxel\get_icon_markup( $this->get_settings_for_display('ts_submit_ico') ) ?: \Voxel\svg( 'pencil.svg' ) ?>
								<?= _x( 'Submit required information', 'stripe vendor', 'voxel' ) ?>
							</a>
						</li>
					<?php else: ?>
						<li>
							<a href="<?= esc_url( $onboard_link ) ?>" class="ts-btn ts-btn-1 ts-btn-large">
								 <?= \Voxel\get_icon_markup( $this->get_settings_for_display('ts_update_ico') ) ?: \Voxel\svg( 'pencil.svg' ) ?>
								<?= _x( 'Update information', 'stripe vendor', 'voxel' ) ?>
							</a>
						</li>
						<li>
							<a href="<?= esc_url( $dashboard_link ) ?>" target="_blank" class="ts-btn ts-btn-1 ts-btn-large">
								 <?= \Voxel\get_icon_markup( $this->get_settings_for_display('ts_stripe_ico') ) ?: \Voxel\svg( 'link-alt.svg' ) ?>
								<?= _x( 'Stripe dashboard', 'stripe vendor', 'voxel' ) ?>
							</a>
						</li>
					<?php endif ?>
				</ul>
			</div>
		<?php endif ?>
	</div>
	
</div>
