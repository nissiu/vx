<div class="ts-panel active-plan">
	<div class="ac-head">
		<?= \Voxel\get_icon_markup( $this->get_settings_for_display('ts_plan_ico') ) ?: \Voxel\svg( 'badge.svg' ) ?>
		<?php if ( $membership->plan->get_key() === 'default' ): ?>
			<p><?= $membership->plan->get_label() ?></p>
		<?php else: ?>
			<p>
				<?= \Voxel\replace_vars( _x( 'Your current plan is @plan_label', 'current plan', 'voxel' ), [
					'@plan_label' => $membership->plan->get_label(),
				] ) ?>
			</p>
		<?php endif ?>
	</div>

	<?php if ( $membership->get_type() === 'subscription' ): ?>
		<div class="ac-body">
			<div class="ac-plan-pricing">
				<span class="ac-plan-price">
					<?= \Voxel\currency_format( $membership->get_amount(), $membership->get_currency() ) ?>
				</span>
				<div class="ac-price-period">
					/ <?= \Voxel\interval_format( $membership->get_interval(), $membership->get_interval_count() ) ?>
				</div>
			</div>
			<?php if ( $membership->will_cancel_at_period_end() ): ?>
				<p>
					<?= \Voxel\replace_vars(
						_x( 'Your subscription will be cancelled on @period_end. Click <a:reactivate>here</a> to reactivate.', 'current plan', 'voxel' ),
						[
							'@period_end' => \Voxel\date_format( $membership->get_current_period_end() ),
							'<a:reactivate>' => '<a href="'.esc_url( $reactivate_url ).'" vx-action>',
						]
					) ?>
				</p>
			<?php elseif ( $membership->get_status() === 'trialing' ): ?>
				<p>
					<?= \Voxel\replace_vars( _x( 'Your trial ends on @trial_end', 'current plan', 'voxel' ), [
						'@trial_end' => \Voxel\date_format( $membership->get_trial_end() ),
					] ) ?>
				</p>
			<?php elseif ( $membership->get_status() === 'active' ): ?>
				<p>
					<?= \Voxel\replace_vars( _x( 'Your subscription renews on @period_end', 'current plan', 'voxel' ), [
						'@period_end' => \Voxel\date_format( $membership->get_current_period_end() ),
					] ) ?>
				</p>
			<?php elseif ( $membership->get_status() === 'incomplete' ): ?>
				<p>
					<?= \Voxel\replace_vars(
						_x( '<a:update>Update your payment method</a>, then <a:finalize>finalize payment</a> to activate your subscription.', 'current plan', 'voxel' ),
						[
							'<a:update>' => '<a href="'.esc_url( $portal_url ).'" target="_blank">',
							'<a:finalize>' => '<a href="'.esc_url( $retry_payment_url ).'" vx-action>',
						]
					) ?>
				</p>
			<?php elseif ( $membership->get_status() === 'incomplete_expired' ): ?>
				<p>
					<?= \Voxel\replace_vars(
						_x( 'Subscription payment failed. Click <a:choose_plan>here</a> to pick a new plan.', 'current plan', 'voxel' ),
						[
							'<a:choose_plan>' => '<a href="'.esc_url( $switch_url ).'">',
						]
					) ?>
				</p>
			<?php elseif ( $membership->get_status() === 'past_due' ): ?>
				<p>
					<?= \Voxel\replace_vars(
						_x( 'Subscription renewal failed. <a:update>Update payment method</a>, then <a:finalize>finalize payment</a> to reactivate your subscription.', 'current plan', 'voxel' ),
						[
							'<a:update>' => '<a href="'.esc_url( $portal_url ).'" target="_blank">',
							'<a:finalize>' => '<a href="'.esc_url( $retry_payment_url ).'" vx-action>',
						]
					) ?>
				</p>
			<?php elseif ( $membership->get_status() === 'canceled' ): ?>
				<?= \Voxel\replace_vars(
					_x( 'Subscription has been canceled. Click <a:choose_plan>here</a> to pick a new plan.', 'current plan', 'voxel' ),
					[
						'<a:choose_plan>' => '<a href="'.esc_url( $switch_url ).'">',
					]
				) ?>
			<?php elseif ( $membership->get_status() === 'unpaid' ): ?>
				<p>
					<?= \Voxel\replace_vars(
						_x( 'Subscription has been deactivated due to failed renewal payments. <a:update>Update payment method</a>,
							then <a:finalize>finalize payment</a> to reactivate your subscription.', 'current plan', 'voxel' ),
						[
							'<a:update>' => '<a href="'.esc_url( $portal_url ).'" target="_blank">',
							'<a:finalize>' => '<a href="'.esc_url( $retry_payment_url ).'" vx-action>',
						]
					) ?>
				</p>
			<?php endif ?>
			<div class="ac-bottom">
				<ul class="simplify-ul current-plan-btn">
					<li>
						<a href="<?= esc_url( $switch_url ) ?>" class="ts-btn ts-btn-1">
							<?= \Voxel\get_icon_markup( $this->get_settings_for_display('ts_switch_ico') ) ?: \Voxel\svg( 'switch.svg' ) ?>
							<?= _x( 'Switch', 'current plan', 'voxel' ) ?>
						</a>
					</li>
					<?php if ( ! in_array( $membership->get_status(), [ 'canceled', 'incomplete_expired' ], true ) ): ?>
						<li>
							<a href="<?= esc_url( $cancel_url ) ?>" vx-action class="ts-btn ts-btn-1">
								<?= \Voxel\get_icon_markup( $this->get_settings_for_display('ts_cancel_ico') ) ?: \Voxel\svg( 'cross-circle.svg' ) ?>
								<?= _x( 'Cancel', 'current plan', 'voxel' ) ?>
							</a>
						</li>
					<?php endif ?>
					<li>
						<a href="<?= esc_url( $portal_url ) ?>" target="_blank" class="ts-btn ts-btn-1">
							<?= \Voxel\get_icon_markup( $this->get_settings_for_display('ts_stripe_ico') ) ?: \Voxel\svg( 'link-alt.svg' ) ?>
							<?= _x( 'Stripe portal', 'current plan', 'voxel' ) ?>
						</a>
					</li>
				</ul>
			</div>
		</div>
	<?php elseif ( $membership->get_type() === 'payment' ): ?>
		<div class="ac-body">
			<div class="ac-plan-pricing">
				<?php if ( floatval( $membership->get_amount() ) === 0.0 ): ?>
					<span class="ac-plan-price"><?= _x( 'Free', 'current plan', 'voxel' ) ?></span>
				<?php else: ?>
					<span class="ac-plan-price">
						<?= \Voxel\currency_format( $membership->get_amount(), $membership->get_currency() ) ?>
					</span>
					<div class="ac-price-period">
						<?= _x( 'one time payment', 'current plan', 'voxel' ) ?>
					</div>
				<?php endif ?>
			</div>
			<div class="ac-bottom">
				<ul class="simplify-ul current-plan-btn">
					<li>
						<a href="<?= esc_url( $switch_url ) ?>" class="ts-btn ts-btn-1">
							<?= \Voxel\get_icon_markup( $this->get_settings_for_display('ts_switch_ico') ) ?: \Voxel\svg( 'switch.svg' ) ?>
							<?= _x( 'Switch', 'current plan', 'voxel' ) ?>
						</a>
					</li>
					<li>
						<a href="<?= esc_url( $portal_url ) ?>" target="_blank" class="ts-btn ts-btn-1">
							<?= \Voxel\get_icon_markup( $this->get_settings_for_display('ts_stripe_ico') ) ?: \Voxel\svg( 'link-alt.svg' ) ?>
							<?= _x( 'Stripe portal', 'current plan', 'voxel' ) ?>
						</a>
					</li>
				</ul>
			</div>
		</div>
	<?php elseif ( $membership->get_type() === 'default' ): ?>
		<?php if ( $membership->plan->get_key() === 'default' ): ?>
			<div class="ac-body">
				<p><?= _x( 'You do not have an active membership plan.', 'current plan', 'voxel' ) ?></p>
				<div class="ac-bottom">
					<a href="<?= esc_url( $switch_url ) ?>" class="ts-btn ts-btn-1">
						<?= \Voxel\get_icon_markup( $this->get_settings_for_display('ts_plan_ico') ) ?: \Voxel\svg( 'badge.svg' ) ?>
						<?= _x( 'Select plan', 'current plan', 'voxel' ) ?>
					</a>
				</div>
			</div>
		<?php else: ?>
			<div class="ac-body">
				<p><?= _x( 'Your current membership plan was manually assigned.', 'current plan', 'voxel' ) ?></p>
				<div class="ac-bottom">
					<a href="<?= esc_url( $switch_url ) ?>" class="ts-btn ts-btn-1">
						<?= \Voxel\get_icon_markup( $this->get_settings_for_display('ts_switch_ico') ) ?: \Voxel\svg( 'switch.svg' ) ?>
						<?= _x( 'Switch', 'current plan', 'voxel' ) ?>
					</a>
				</div>
			</div>
		<?php endif ?>
	<?php endif ?>
</div>
